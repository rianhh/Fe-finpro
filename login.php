<?php
session_start();

include('includes/header.php');
include('db_connection.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ambil kata sandi terenkripsi dari database berdasarkan email pengguna
    $query = "SELECT id, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user_data = mysqli_fetch_assoc($result);
        $hashedPassword = $user_data['password'];

        // Memeriksa apakah kata sandi cocok dengan kata sandi terenkripsi
        if (password_verify($password, $hashedPassword)) {
            // Berhasil login, set session user_id dan redirect ke halaman yang sesuai
            $_SESSION['user_id'] = $user_data['id'];
            header("Location: index.php");
        } else {
            // Gagal login, tampilkan pesan kesalahan
            $error_message = "Email or password is incorrect.";
        }
    } else {
        // Gagal login, tampilkan pesan kesalahan
        $error_message = "Email or password is incorrect.";
    }
}

?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 mx-auto"> <!-- Add mx-auto class to center the content -->
                            <div class="p-5">
                            <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                <form class="user" method="POST" action="">
                                <form class="user" method="POST" action="">
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user"
                                            name="email" aria-describedby="emailHelp"
                                            placeholder="Enter Email Address..." required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                            name="password" placeholder="Password" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Remember Me</label>
                                        </div>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-info btn-user btn-block">
                                        Sign In
                                    </button>
                                    <hr>
                                    <?php
                                    if (isset($error_message)) {
                                        echo '<div class="text-center text-danger small">' . $error_message . '</div>';
                                    }
                                    ?>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                    <hr >
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
    </div>
</div>


<?php
include('includes/scripts.php');
?>
