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


// Check if the user is not logged in, then redirect to the login page

// Check if the user is not logged in, then redirect to the login page

$user_id = $_SESSION['user_id'];

// Inisialisasi pesan
$message = '';

// Cek apakah formulir telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tangani perubahan profil
    $nama = $_POST['nama']; // Ini adalah nama lengkap (first name + last name)
    $email = $_POST['email'];

   // Pisahkan nama lengkap menjadi `first name` dan `last name`
list($first_name, $last_name) = explode(' ', $nama);


    // Tambahkan kode untuk mengupdate data pengguna di database sesuai dengan nilai yang diambil dari formulir
    $query = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email' WHERE id = $user_id";

    if (mysqli_query($conn, $query)) {
        $message = "Profil berhasil diperbarui!";
    } else {
        $message = "Terjadi kesalahan saat memperbarui profil.";
    }
}

// Ambil data pengguna dari database
$query = "SELECT first_name, last_name, email  FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if ($result) {
    $user_data = mysqli_fetch_assoc($result);
    $first_name = $user_data['first_name'];
    $last_name = $user_data['last_name'];
    $nama = $first_name . ' ' . $last_name;
    $email = $user_data['email'];
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Profile</h6>
        </div>
        <div class="card-body">
            <div class="alert <?php echo (empty($message) ? 'd-none' : 'alert-success'); ?>">
                <?php echo $message; ?>
            </div>
            <div class="text-center mb-3">
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg" width="200px" id="profile-image" alt="Foto Profil">
                <label for="file-input" class="btn btn-primary">Edit Foto</label>
                <input type="file" id="file-input" style="display: none;">
            </div>
            <form method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" value="<?php echo $nama; ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
