<?php

/**
 * Certificate Generator Class
 * Generates PDF certificates using DomPDF (HTML-to-PDF)
 * Uses traditional CSS layout (NO flexbox) for DomPDF compatibility
 * 
 * @package App\Classes
 */

require_once __DIR__ . '/../../assets/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class CertificateGenerator
{
    private Dompdf $dompdf;
    private Options $options;
    private bool $debugMode = false;

    public function __construct(bool $debugMode = false)
    {
        $this->debugMode = $debugMode;

        $this->options = new Options();
        $this->options->set('isRemoteEnabled', true);
        $this->options->set('isHtml5ParserEnabled', true);
        $this->options->set('isPhpEnabled', false);
        $this->options->set('defaultFont', 'DejaVu Sans');
        $this->options->set('dpi', 96);
        $this->options->set('enable_font_subsetting', true);

        $this->dompdf = new Dompdf($this->options);
    }

    /**
     * Generate certificate PDF for a user
     *
     * @param array $userData User data from database
     * @param array $progressDetails Progress breakdown details
     * @param string|null $bgImagePath Optional path to background image file
     * @param string|array|null $customLogo Optional path/URL to custom logo image, or array of logos
     * @param array|null $signatures Optional array of signature images with labels and positions
     *        Each entry: ['path' => string, 'label' => string, 'position' => 'left|right']
     */
    public function generate(array $userData, array $progressDetails, string $bgImagePath = null, string|array $customLogo = null, array $signatures = null): string
    {
        $html = $this->buildCertificateHtml($userData, $progressDetails, $bgImagePath, $customLogo, $signatures);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();

        return $this->dompdf->output();
    }

    /**
     * Build certificate HTML template - PRODUCTION VERSION (NO FLEXBOX)
     * Uses absolute positioning and table layout for DomPDF compatibility
     */
    private function buildCertificateHtml(array $userData, array $progressDetails, string $bgImagePath = null, string|array $customLogo = null, array $signatures = null): string
    {
        $userName = htmlspecialchars($userData['name'] ?? 'User');
        $userId = (int)($userData['id'] ?? 0);
        $certificateNumber = 'MAX-' . str_pad($userId, 6, '0', STR_PAD_LEFT) . '-' . date('Ymd');
        $issueDate = date('d F Y');

        // Progress details
        $materi = $progressDetails['materi'] ?? ['completed' => 0, 'total' => 0];
        $pretest = $progressDetails['pretest'] ?? ['completed' => 0, 'total' => 1];
        $kuis = $progressDetails['kuis'] ?? ['completed' => 0, 'total' => 0];
        $posttest = $progressDetails['posttest'] ?? ['completed' => 0, 'total' => 1];

        // Load background image as base64 if provided - use body background for DomPDF reliability
        $bgImageStyle = '';
        $bgOverlayStyle = '';
        if ($bgImagePath && file_exists($bgImagePath)) {
            $bgImageData = file_get_contents($bgImagePath);
            $bgImageMime = mime_content_type($bgImagePath);
            $bgImageBase64 = 'data:' . $bgImageMime . ';base64,' . base64_encode($bgImageData);
            $bgImageStyle = 'background-image: url("' . $bgImageBase64 . '"); background-size: cover; background-position: center; background-repeat: no-repeat;';
            $bgOverlayStyle = 'position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.7); z-index: 1;';
        }

        // Load custom logo(s) if provided (supports local file paths, URLs, single string or array)
        // Each logo can be a string (path/URL) or array with 'src' and optional 'position' keys
        $logoHtml = '';
        $logos = [];

        if ($customLogo !== null) {
            // Normalize to array
            if (is_string($customLogo)) {
                $logos = [$customLogo];
            } elseif (is_array($customLogo)) {
                $logos = $customLogo;
            }

            foreach ($logos as $logo) {
                if (!$logo || empty($logo)) continue;

                // Support both string (path/URL) and array formats
                $logoSrc = '';
                $logoPosition = 'top-center'; // default

                if (is_array($logo)) {
                    $src = $logo['src'] ?? $logo['path'] ?? $logo['url'] ?? '';
                    $logoPosition = $logo['position'] ?? 'top-center';
                } else {
                    $src = $logo;
                }

                if (!$src || empty($src)) continue;

                if (filter_var($src, FILTER_VALIDATE_URL)) {
                    $logoSrc = $src;
                } elseif (file_exists($src)) {
                    $logoData = file_get_contents($src);
                    $logoMime = mime_content_type($src);
                    $logoSrc = 'data:' . $logoMime . ';base64,' . base64_encode($logoData);
                } else {
                    continue;
                }

                if ($logoSrc) {
                    $positionClass = 'custom-logo-' . str_replace('_', '-', $logoPosition);
                    $logoHtml .= '<img src="' . $logoSrc . '" alt="Custom Logo" class="custom-logo ' . $positionClass . '">';
                }
            }
        }

        // Generate signature icon HTML for inline placement in signature-block
        $leftSigHtml = '';
        $rightSigHtml = '';
        if ($signatures && is_array($signatures)) {
            foreach ($signatures as $sig) {
                if (!$sig || empty($sig)) continue;

                $src = $sig['path'] ?? $sig['src'] ?? $sig['url'] ?? '';
                $position = $sig['position'] ?? 'left';

                if (!$src || empty($src)) continue;

                if (filter_var($src, FILTER_VALIDATE_URL)) {
                    $sigSrc = $src;
                } elseif (file_exists($src)) {
                    $sigData = file_get_contents($src);
                    $sigMime = mime_content_type($src);
                    $sigSrc = 'data:' . $sigMime . ';base64,' . base64_encode($sigData);
                } else {
                    continue;
                }

                if ($sigSrc) {
                    if (($position ?? 'left') === 'right') {
                        $rightSigHtml = '<img src="' . $sigSrc . '" alt="Signature" class="signature-icon">';
                    } else {
                        $leftSigHtml = '<img src="' . $sigSrc . '" alt="Signature" class="signature-icon">';
                    }
                }
            }
        }

        // Debug CSS
        $debugCss = $this->debugMode ? '
            * { outline: 1px solid red !important; }
            .certificate { outline: 3px solid blue !important; }
            .header { outline: 2px solid orange !important; }
            .recipient-section { outline: 2px solid purple !important; }
            .footer { outline: 2px solid brown !important; }
            .achievements { outline: 1px solid cyan !important; }
        ' : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kelulusan - {$userName}</title>
    <style>
        @page {
            margin: 5mm;
            padding: 0;
            size: A4 landscape;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #ffffff;
            {$bgImageStyle}
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .page-wrapper {
            width: 100%;
            height: 100%;
            max-width: none;
            max-height: none;
            min-height: 100%;
            position: relative;
            overflow: hidden;
            margin: 0;
        }
        .bg-overlay {
            {$bgOverlayStyle}
        }
        .certificate {
            width: 100%;
            height: 100%;
            max-width: none;
            max-height: none;
            min-height: 100%;
            background: transparent;
            padding: 0;
            position: relative;
            overflow: hidden;
            margin: 0;
            z-index: 2;
        }
        /* Custom logo positioning - supports multiple logos */
        .custom-logo {
            position: absolute;
            max-width: auto;
            max-height: 52px;
            object-fit: contain;
            z-index: 10;
        }
        .custom-logo-top-center {
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
        }
        .custom-logo-top-left {
            top: 30px;
            left: 35%;
        }
        .custom-logo-top-right {
            top: 30px;
            right: 35%;
        }
        .custom-logo-bottom-center {
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
        }
        .custom-logo-bottom-left {
            bottom: 30px;
            left: 30px;
        }
        .custom-logo-bottom-right {
            bottom: 30px;
            right: 30px;
        }
        
        /* Decorative corner elements - closer to edges */
        .corner-tl, .corner-tr, .corner-bl, .corner-br {
            position: absolute;
            width: 30px;
            height: 30px;
            border: 3px solid #1a3c5e;
            z-index: 3;
        }
        .corner-tl { top: 15px; left: 15px; border-right: none; border-bottom: none; border-radius: 4px 0 0 0; }
        .corner-tr { top: 15px; right: 15px; border-left: none; border-bottom: none; border-radius: 0 4px 0 0; }
        .corner-bl { bottom: 15px; left: 15px; border-right: none; border-top: none; border-radius: 0 0 0 4px; }
        .corner-br { bottom: 15px; right: 15px; border-left: none; border-top: none; border-radius: 0 0 4px 0; }
        
        /* HEADER - positioned at top with more padding */
        .header {
            text-align: center;
            padding: 40px 40px 20px 40px;
            border-bottom: none;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #1a3c5e 0%, #2c5f8a 100%);
            border-radius: 50%;
            display: table;
        }
        .logo svg {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .title {
            font-size: 14px;
            color: #4b5155;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.2;
        }
        .main-title {
            font-size: 32px;
            color: #070f18;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .subtitle {
            font-size: 16px;
            color: #4b5155;
            font-weight: 400;
            margin-bottom: 20px;
            line-height: 1.2;
        }
        /* .divider {
            width: 24px;
            height: 3px;
            background: linear-gradient(90deg, #1a3c5e, #c9a84c);
            margin: 0 auto 20px;
            border-radius: 2px;
        } */
        
        /* RECIPIENT SECTION - centered content with generous padding */
        .recipient-section {
            text-align: center;
            padding: 24px 60px 40px 60px;
        }
        .awarded-text {
            font-size: 16px;
            color: #4b5155;
            margin-bottom: 15px;
            font-weight: 400;
            line-height: 1.3;
        }
        .recipient-name {
            font-size: 38px;
            color: #070f18;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
            margin-bottom: 10px;
            line-height: 1.2;
            word-wrap: break-word;
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }
        .description {
            font-size: 15px;
            color: #4b5155;
            line-height: 1.5;
            max-width: 700px;
            margin: 0 auto 15px;
        }
        .description strong {
            color: #070f18;
            font-weight: 600;
        }
        .gold-line {
            width: 100%;
            max-width: 400px;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, #c9a84c, transparent);
            margin: 12px auto;
        }
        .achievements {
            text-align: center;
            margin: 15px 0;
            line-height: 2;
        }
        .achievements-table {
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 6px 6px;
        }
        .achievement-badge {
            background: #070f18;
            color: white;
            padding: 8px 14px;
            border-radius: 25px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
            line-height: 1.3;
        }
        .achievement-badge.gold {
            background: #c9a84c;
            color: #070f18;
        }
        
        /* FOOTER - absolute positioning at bottom with more space */
        .footer {
            position: absolute;
            bottom: 52px;
            left: 40px;
            right: 40px;
            padding-top: 15px;
        }
        .footer-row {
            width: 100%;
            display: table;
            table-layout: fixed;
        }
        .footer-col {
            display: table-cell;
            vertical-align: bottom;
            width: 33.33%;
        }
        .footer-left { text-align: left; padding-left: 20px; }
        .footer-center { text-align: center; }
        .footer-right { text-align: right; padding-right: 20px; }
        
        .signature-block {
            margin-bottom: 4px;
            position: relative;
        }
        .signature-icon {
            display: block;
            max-width: 120px;
            max-height: 60px;
            margin: 0 auto 8px;
            object-fit: contain;
            opacity: 0.8;
        }
        .signature-line {
            width: 160px;
            height: 1.5px;
            background: #070f18;
            margin: 0 0 4px;
        }
        .footer-left .signature-line { margin-right: 0; }
        .footer-center .signature-line { margin-left: auto; margin-right: auto; }
        .footer-right .signature-line { margin-left: auto; margin-right: 0; }
        
        .signature-name {
            font-size: 12px;
            color: #070f18;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.3;
            word-wrap: break-word;
        }
        .signature-title {
            font-size: 9px;
            color: #4b5155;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.3;
            word-wrap: break-word;
        }
        .certificate-info {
            font-size: 9px;
            color: #4b5155;
            line-height: 1.4;
            margin-top: 16px;
        }
        .certificate-info strong {
            color: #070f18;
        }
        .seal {
            width: 42px;
            height: 42px;
            border: 3px solid #b1860f;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: rgba(201, 168, 76, 0.05);
            text-align: center;
        }
        .seal-icon {
            font-size: 24px;
            color: #b1860f;
            line-height: auto;
        }
        .seal-text {
            font-size: 7px;
            color: #b1860f;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1;
            margin-top: 12px;
            display: block;
        }
        {$debugCss}
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="bg-overlay"></div>
        <div class="certificate">
            {$logoHtml}
            <div class="corner-tl"></div>
            <div class="corner-tr"></div>
            <div class="corner-bl"></div>
            <div class="corner-br"></div>
            
            <!-- Header Section -->
            <div class="header">
                <div class="logo">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="title">Sertifikat Kelulusan</div>
                <div class="main-title">Program Edukasi Keluarga Sejahtera</div>
                <div class="subtitle">Certificate of Completion</div>
                <div class="divider"></div>
            </div>
            
            <!-- Recipient Section -->
            <div class="recipient-section">
                <div class="awarded-text">Dengan ini dinyatakan bahwa</div>
                <div class="recipient-name">{$userName}</div>
                <div class="gold-line"></div>
                <div class="description">
                    Telah berhasil menyelesaikan seluruh rangkaian pembelajaran program edukasi
                    <strong>Keluarga Sejahtera</strong> dengan capaian progress <strong>100%</strong>,
                    meliputi Materi, Pre-Test, Kuis, dan Post-Test.
                </div>
                
                <div class="achievements">
                    <table class="achievements-table" style="margin: 0 auto; border-collapse: separate; border-spacing: 4px 4px;">
                        <tr>
                            <td class="achievement-badge" style="background: #1a3c5e; color: white; padding: 6px 10px; border-radius: 20px; font-size: 9px; font-weight: 600; white-space: nowrap; line-height: 1.2;">
                                <span>&#10003;</span> Materi: {$materi['completed']}/{$materi['total']} Modul
                            </td>
                            <td class="achievement-badge" style="background: #1a3c5e; color: white; padding: 6px 10px; border-radius: 20px; font-size: 9px; font-weight: 600; white-space: nowrap; line-height: 1.2;">
                                <span>&#10003;</span> Pre-Test: Selesai
                            </td>
                            <td class="achievement-badge" style="background: #1a3c5e; color: white; padding: 6px 10px; border-radius: 20px; font-size: 9px; font-weight: 600; white-space: nowrap; line-height: 1.2;">
                                <span>&#10003;</span> Kuis: {$kuis['completed']}/{$kuis['total']} Selesai
                            </td>
                            <td class="achievement-badge gold" style="background: #c9a84c; color: #1a3c5e; padding: 6px 10px; border-radius: 20px; font-size: 9px; font-weight: 600; white-space: nowrap; line-height: 1.2;">
                                <span>&#9733;</span> Post-Test: Lulus
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- Footer Section (Absolute Positioned at Bottom) -->
            <div class="footer">
                <div class="footer-row">
                    <div class="footer-col footer-left">
                        <div class="signature-block">
                            {$leftSigHtml}
                            <div class="signature-line"></div>
                            <div class="signature-name">Ketua Program</div>
                            <div class="signature-title">Ahmad Davied Chaniago S.Kom</div>
                        </div>
                    </div>
                    
                    <div class="footer-col footer-center">
                        <div class="seal">
                            <div class="seal-icon">&#10003;</div>
                            <div class="seal-text">Terverifikasi</div>
                        </div>
                        <div class="certificate-info">
                            <strong>Nomor Sertifikat:</strong> {$certificateNumber}<br>
                            <strong>Tanggal Terbit:</strong> {$issueDate}<br>
                            <strong>Masa Berlaku:</strong> Seumur Hidup
                        </div>
                    </div>
                    
                    <div class="footer-col footer-right">
                        <div class="signature-block">
                            {$rightSigHtml}
                            <div class="signature-line"></div>
                            <div class="signature-name">Direktur Eksekutif</div>
                            <div class="signature-title">Muhammad Rafli Aryanto S.Kom</div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Build MINIMAL certificate HTML template - For testing/debugging
     */
    public function buildMinimalHtml(array $userData): string
    {
        $userName = htmlspecialchars($userData['name'] ?? 'User');
        $userId = (int)($userData['id'] ?? 0);
        $certificateNumber = 'MAX-' . str_pad($userId, 6, '0', STR_PAD_LEFT) . '-' . date('Ymd');
        $issueDate = date('d F Y');

        $debugCss = $this->debugMode ? '
            * { outline: 1px solid red !important; }
            .certificate { outline: 3px solid blue !important; }
        ' : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat - {$userName}</title>
    <style>
        @page { margin: 0; size: A4 landscape; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; width: 100%; overflow-x: hidden; }
        body { font-family: 'DejaVu Sans', sans-serif; background: #fff; }
        .certificate {
            width: 297mm; max-width: 297mm; height: 210mm; max-height: 210mm;
            padding: 30px 20px; text-align: center; margin: 0 auto;
        }
        .name { font-size: 36px; color: #1a3c5e; font-weight: 700; margin: 20px 0; text-transform: uppercase; }
        .title { font-size: 18px; color: #666; text-transform: uppercase; letter-spacing: 2px; }
        .info { font-size: 12px; color: #888; margin-top: 30px; line-height: 1.5; }
        {$debugCss}
    </style>
</head>
<body>
    <div class="certificate">
        <div class="title">Sertifikat Kelulusan</div>
        <div class="name">{$userName}</div>
        <div class="info">
            <strong>Nomor:</strong> {$certificateNumber}<br>
            <strong>Tanggal:</strong> {$issueDate}
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Generate minimal certificate (for debugging layout issues)
     */
    public function generateMinimal(array $userData): string
    {
        $html = $this->buildMinimalHtml($userData);
        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'landscape');
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    /**
     * Enable/disable debug mode
     */
    public function setDebugMode(bool $enabled): void
    {
        $this->debugMode = $enabled;
    }
}
