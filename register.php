<?php
include('includes/header.php');
include('db_connection.php');

if (isset($_POST['signup'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Lakukan validasi, misalnya: pastikan email unik
    // Enkripsi kata sandi sebelum menyimpannya
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
    if (mysqli_query($conn, $query)) {
        // Berhasil registrasi
        $success_message = "Pendaftaran berhasil! Silakan login.";
        // Tambahkan kode JavaScript untuk menampilkan pesan pop-up dan mengarahkan ke halaman login
        echo '<script>
                alert("' . $success_message . '");
                window.location.href = "login.php";
              </script>';
        exit;
    } else {
        // Gagal registrasi, tampilkan pesan kesalahan
        $error_message = "Registration failed. Please try again.";
    }
}
?>

<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        <form class="user" method="POST" action="">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" name="first_name" placeholder="First Name" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" name="last_name" placeholder="Last Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" name="email" placeholder="Email Address" required>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" name="password" placeholder="Password" required>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="exampleRepeatPassword" placeholder="Repeat Password" required>
                                </div>
                            </div>
                            <button type="submit" name="signup" class="btn btn-info btn-user btn-block">
                                Sign Up
                            </button>
                            <hr>
                            <?php
                            if (isset($error_message)) {
                            echo '<div class="text-center text-danger small">' . $error_message . '</div>';
                            }
                            ?>
                            <div class="text-center">
                            <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                            <hr>
                            <div style="position:relative; text-align:center;">
                                        <a href="https://accounts.google.com" class="btn btn-google">
                                            <i class="fab fa-google fa-fw"></i>
                                        </a>
                                        <a href="https://facebook.com" class="btn btn-facebook">
                                            <i class="fab fa-facebook-f fa-fw"></i>
                                        </a>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
?>
