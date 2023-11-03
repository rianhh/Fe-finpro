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

$user_id = $_SESSION['user_id'];

// Ambil data poin pengguna dari database
$query = "SELECT points FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $user_data = mysqli_fetch_assoc($result);
    $points = $user_data['points'];
} else {
    // Handle kesalahan jika data pengguna tidak ditemukan
    echo "Data pengguna tidak ditemukan.";
    exit;
}

// Pesan default
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $reward_id = $_POST['reward_id'];

    // Ambil jumlah poin yang dibutuhkan untuk reward ini dari tabel "rewards"
    $query = "SELECT points_required, reward_name FROM rewards WHERE id = $reward_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $reward_data = mysqli_fetch_assoc($result);
        $points_required = $reward_data['points_required'];
        $reward_name = $reward_data['reward_name'];

        // Ambil jumlah poin pengguna dari tabel "users"
        $query = "SELECT points FROM users WHERE id = $user_id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user_data = mysqli_fetch_assoc($result);
            $points = $user_data['points'];

            if ($points >= $points_required) {
                // Kurangkan poin pengguna
                $points -= $points_required;

                // Simpan jumlah poin yang diperbarui ke dalam tabel "users"
                $updateQuery = "UPDATE users SET points = $points WHERE id = $user_id";
                if (mysqli_query($conn, $updateQuery)) {
                    // Simpan data penukaran ke dalam tabel "redeem_history" dengan reward_name
                    $insertQuery = "INSERT INTO redeem_history (user_id, reward_id, reward_name, redeem_date) VALUES ($user_id, $reward_id, '$reward_name', NOW())";

                    if (mysqli_query($conn, $insertQuery)) {
                        $message = "Poin Anda telah ditukarkan dengan hadiah reward.";
                    } else {
                        $message = "Gagal menyimpan data penukaran ke dalam redeem_history.";
                    }
                } else {
                    $message = "Gagal mengurangkan poin pengguna.";
                }
            } else {
                $message = "Anda tidak memiliki cukup poin untuk menukar hadiah ini.";
            }
        } else {
            $message = "Gagal mengambil jumlah poin pengguna.";
        }
    } else {
        $message = "Reward tidak ditemukan.";
    }
    echo $message;
}
?>


<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Point</h1>
    </div>
    <div class="row">
  <div class="col-xl-6 col-md-6 mb-4">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
              My Points
            </div>
            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $points; ?></div>
          </div>
          <div class="col-auto">
            <i class="fas fa-donate fa-2x text-gray-300"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rewards</h1>
    </div>

    <div class="row">
        <?php
        // Query untuk mengambil daftar reward
        $rewards_query = "SELECT id, reward_name, points_required FROM rewards";
        $rewards_result = mysqli_query($conn, $rewards_query);

        if ($rewards_result && mysqli_num_rows($rewards_result) > 0) {
            while ($row = mysqli_fetch_assoc($rewards_result)) {
                $reward_id = $row['id'];
                $reward_name = $row['reward_name'];
                $points_required = $row['points_required'];
        ?>
                <div class="col-md-3">
                    <!-- Reward Card -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= $reward_name; ?></h5>
                            <p class="card-text">
                                <small class="text-muted">Harga: <?= $points_required; ?> Poin</small>
                            </p>
                            <form method="post" action="rewards.php" onsubmit="return confirmPayment();">
                                <input type="hidden" name="reward_id" value="<?= $reward_id; ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Redeem Reward
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>
</div>

<script>
    function confirmPayment() {
        if (confirm("Anda yakin ingin menukarkan reward?")) {
            // Jika pengguna mengklik OK, lanjutkan dengan pengiriman form
            return true;
        } else {
            // Jika pengguna mengklik Batal, batalkan pengiriman form
            return false;
        }
    }
    
</script>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
