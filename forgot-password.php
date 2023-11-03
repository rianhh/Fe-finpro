<?php
include('includes/header.php');
include('db_connection.php');

if (isset($_POST['reset_password'])) {
    $email = $_POST['email'];

    // Lakukan validasi, misalnya: pastikan email ada dalam database

    // Generate token reset password
    $token = bin2hex(random_bytes(32));

    // Simpan token reset password dalam database
    $query = "INSERT INTO password_reset (email, token) VALUES ('$email', '$token')";
    if (mysqli_query($conn, $query)) {
        // Kirim email berisi link reset password
        $reset_link = "http://yourwebsite.com/reset-password.php?token=$token";
        // Implementasi pengiriman email reset link bisa menggunakan PHPMailer atau library email lainnya
        // Contoh sederhana:
        mail($email, "Reset Password", "Silakan klik link berikut untuk reset password: $reset_link");
        $success_message = "Reset password link has been sent to your email.";
    } else {
        $error_message = "Password reset request failed. Please try again.";
    }
}
?>

<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-6 d-none d-lg-block">
                    <img style="width: 80%; height: 80%; position: relative" src="img/3dicons.png" />
                </div>
                <div class="col-lg-6">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                            <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                and we'll send you a link to reset your password!</p>
                        </div>
                        <form class="user" method="POST" action="">
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user"
                                    name="email" id="exampleInputEmail" aria-describedby="emailHelp"
                                    placeholder="Enter Email Address..." required>
                            </div>
                            <button type="submit" name="reset_password" class="btn btn-info btn-user btn-block">
                                Reset Password
                            </button>
                        </form>
                        <hr>
                        <?php
                        if (isset($error_message)) {
                            echo '<div class="text-center text-danger small">' . $error_message . '</div>';
                        }
                        if (isset($success_message)) {
                            echo '<div class="text-center text-success small">' . $success_message . '</div>';
                        }
                        ?>
                        <div class="text-center">
                            <a class="small" href="register.php">Create an Account!</a>
                        </div>
                        <div class="text-center">
                            <a class="small" href="login.php">Already have an account? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
?>
