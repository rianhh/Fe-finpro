<?php
session_start();
include('includes/header.php');
include('db_connection.php');
include('includes/navbar.php');
?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- 404 Error Text -->
                    <div class="text-center">
                        <div class="error mx-auto" data-text="404">404</div>
                        <p class="lead text-gray-800 mb-5">Page Not Found</p>
                        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
                        <a href="index.php">&larr; Back to Dashboard</a>
                    </div>

                </div>
                <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <?php
include('includes/scripts.php');
include('includes/footer.php');
?>
