<script src="../../assets/libs/jquery/jquery.min.js"></script>
<script src="../../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/libs/metismenu/metisMenu.min.js"></script>
<script src="../../assets/libs/simplebar/simplebar.min.js"></script>
<script src="../../assets/libs/node-waves/waves.min.js"></script>
<script src="../../assets/libs/feather-icons/feather.min.js"></script>
<!-- pace js -->
<script src="../../assets/libs/pace-js/pace.min.js"></script>
<!-- apexcharts -->
<script src="../../assets/libs/apexcharts/apexcharts.min.js"></script>
<!-- Plugins js-->
<script src="../../assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../../assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<!-- twitter-bootstrap-wizard js -->
<script src="../../assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js">
</script>
<script src="../../assets/libs/twitter-bootstrap-wizard/prettify.js">
</script>
<!-- Required datatable js -->
<script src="../../assets/libs/datatables.net/js/jquery.dataTables.min.js">
</script>
<script src="../../assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js">
</script>
<!-- Responsive examples -->
<script src="../../assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js">
</script>
<script src="../../assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
</script>
<!-- form wizard init -->
<script src="../../assets/js/pages/form-wizard.init.js">
</script>
<!-- dashboard init -->
<script src="../../assets/js/pages/dashboard.init.js"></script>
<!-- Datatable init js -->
<script src="../../assets/js/pages/datatables.init.js">
</script>
<!-- Bootstrap Toasts Js -->
<script src="../../assets/js/pages/bootstrap-toasts.init.js">
</script>
<script src="../../assets/js/app.js"></script>

<!-- CUSTOM SCRIPT UNTUK MOBILE SUBMENU MODAL -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle klik pada menu item dengan has-arrow di mobile
        const hasArrowLinks = document.querySelectorAll('#side-menu li a.has-arrow');
        let submenuModal = null;

        hasArrowLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Cek apakah di mobile
                if (window.innerWidth <= 991) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Get parent li element
                    const parentLi = this.closest('li');

                    // Get submenu
                    const submenu = parentLi.querySelector('.sub-menu');

                    if (submenu) {
                        // Get judul dari menu
                        const menuTitle = this.querySelector('span').innerText;

                        // Get submenu items
                        const submenuItems = submenu.querySelectorAll('li a');

                        // Populate modal
                        const submenuContent = document.getElementById('submenuContent');
                        submenuContent.innerHTML = '';

                        submenuItems.forEach(item => {
                            const link = document.createElement('a');
                            link.href = item.getAttribute('href');
                            link.innerText = item.querySelector('span').innerText;
                            submenuContent.appendChild(link);
                        });

                        // Update modal title
                        document.getElementById('mobileSubmenuLabel').innerText = menuTitle;

                        // Show modal using Bootstrap 5 API
                        const modalElement = document.getElementById('mobileSubmenuModal');
                        submenuModal = new bootstrap.Modal(modalElement);
                        submenuModal.show();
                    }
                }
            });
        });

        // Handle window resize - jika di-resize ke desktop, lepas event listener
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) {
                // Tutup modal jika dibuka
                if (submenuModal) {
                    submenuModal.hide();
                }
            }
        });
    });
</script>
<!-- END CUSTOM SCRIPT SUBMENU MODAL -->