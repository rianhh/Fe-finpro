<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Pengguna belum login, maka arahkan ke halaman login
    header('Location: login.php');
    exit(); // Pastikan untuk menghentikan eksekusi skrip setelah mengarahkan
}
include('includes/header.php');
include('db_connection.php');
include('includes/navbar.php');


// Inisialisasi ID pengguna yang sedang login (misalnya, dari sesi)
$user_id = $_SESSION['user_id']; // Pastikan ID pengguna telah disimpan dalam sesi sebelumnya

// Buat query SQL untuk mengambil data pengguna
// Ambil data pengguna dari database
$query = "SELECT first_name, last_name, email  FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if ($result) {
    $user_data = mysqli_fetch_assoc($result);
    $nama = $user_data['first_name'] . ' ' . $user_data['last_name'];
    $email = $user_data['email'];
}


?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Profile Card -->
    <div class="card shadow mb-4">

        <!-- Card Header -->
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">My Profile</h6>
        </div>

        <!-- Card Body -->
        <div class="card-body">

            <div class="text-center mb-3">
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" width="200px">
            </div>
            <form>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="form-control" value="<?php echo $nama; ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" value="<?php echo $email; ?>" readonly>
                </div>

            </form>

            <!-- Profile Info -->

            <a href="edit-profile.php" class="btn btn-primary btn-user btn-block">
                Edit Profile
            </a>

        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
