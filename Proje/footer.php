<?php
                if(admin_girisimi())
                {
                ?>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Kütüphane Yönetim Sistemi <?php echo date('Y'); ?></div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
                <?php
                }
                else
                {
                ?>
                <footer class="pt-3 mt-4 text-muted text-center border-top">
                    &copy; <?php echo date('Y'); ?>
                </footer>
            </div>
        </main>
                <?php 
                }
                ?>

        <script src="<?php echo base_url(); ?>js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="<?php echo base_url(); ?>js/scripts.js"></script>
        <script src="<?php echo base_url(); ?>js/simple-datatables@latest.js" crossorigin="anonymous"></script>
        <script src="<?php echo base_url(); ?>js/datatables-simple-demo.js"></script>

    </body>

</html>
