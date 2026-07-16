<?php
// 1. Proteksi Halaman
session_start();
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/classes/tests.php'; // Sesuaikan path

$testManager = new tests();

$type = $_GET['type'] ?? 'pre';

$dataTest = $testManager->getTestQuestion();

if (!$dataTest) {
    die("<h2>Maaf, soal untuk test ini belum tersedia.</h2>");
}

$testInfo = $dataTest['test'];
$questions = $dataTest['questions'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($testInfo['judul_test']) ?></title>
    <style>
        /* Sedikit styling dasar agar rapi */
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }

        .card-soal {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .opsi {
            margin-bottom: 10px;
        }

        #resultBox {
            display: none;
            background: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:disabled {
            background: #ccc;
        }
    </style>
</head>

<body>

    <h1><?= htmlspecialchars($testInfo['judul_test']) ?></h1>
    <p><i><?= htmlspecialchars($testInfo['deskripsi']) ?></i></p>
    <hr>

    <!-- Area untuk menampilkan hasil setelah submit -->
    <div id="resultBox">
        <h2 id="teksSkor"></h2>
        <p id="teksDetail"></p>
        <button class="btn" onclick="window.location.href='index.php'">Kembali ke Dashboard</button>
    </div>

    <!-- Form Soal -->
    <form id="formTest">
        <!-- Input tersembunyi untuk mengirim ID Test ke backend -->
        <input type="hidden" name="test_id" value="<?= $testInfo['id'] ?>">
        <input type="hidden" name="tipe_sesi" value="<?= htmlspecialchars($type) ?>">

        <?php
        // Lakukan perulangan untuk mencetak semua soal
        $nomor = 1;
        foreach ($questions as $q):
        ?>
            <div class="card-soal">
                <p><strong><?= $nomor ?>. <?= htmlspecialchars($q['pertanyaan']) ?></strong></p>

                <!-- Perhatikan attribute name="answers[ID_SOAL]". Ini kunci agar dibaca sebagai array di PHP -->
                <div class="opsi">
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="a" required> A. <?= htmlspecialchars($q['opsi_a']) ?></label>
                </div>
                <div class="opsi">
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="b"> B. <?= htmlspecialchars($q['opsi_b']) ?></label>
                </div>
                <div class="opsi">
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="c"> C. <?= htmlspecialchars($q['opsi_c']) ?></label>
                </div>
                <div class="opsi">
                    <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="d"> D. <?= htmlspecialchars($q['opsi_d']) ?></label>
                </div>
            </div>
        <?php
            $nomor++;
        endforeach;
        ?>

        <button type="submit" id="btnSubmit" class="btn">Kumpulkan Jawaban</button>
    </form>

    <script>
        const formTest = document.getElementById('formTest');
        const btnSubmit = document.getElementById('btnSubmit');
        const resultBox = document.getElementById('resultBox');
        const teksSkor = document.getElementById('teksSkor');
        const teksDetail = document.getElementById('teksDetail');

        formTest.addEventListener('submit', async function(e) {
            e.preventDefault(); // Cegah reload halaman

            // Konfirmasi sebelum submit
            if (!confirm('Apakah kamu yakin ingin mengumpulkan jawaban?')) return;

            btnSubmit.disabled = true;
            btnSubmit.innerText = 'Memproses...';

            const formData = new FormData(formTest);

            try {
                // Kirim data ke proses_test.php menggunakan path yang sesuai
                const response = await fetch('actions/proses_test.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // Sembunyikan form
                    formTest.style.display = 'none';

                    // Tampilkan kotak hasil dan datanya
                    resultBox.style.display = 'block';
                    teksSkor.innerText = `Skor Kamu: ${result.data.score}`;
                    teksDetail.innerText = `Benar: ${result.data.benar} | Salah: ${result.data.salah} | Total Soal: ${result.data.total}`;

                    // Otomatis scroll ke atas agar user melihat hasil
                    window.scrollTo(0, 0);
                } else {
                    alert('Error: ' + result.message);
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Kumpulkan Jawaban';
                }
            } catch (error) {
                alert('Terjadi kesalahan koneksi jaringan.');
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'Kumpulkan Jawaban';
            }
        });
    </script>

</body>

</html>