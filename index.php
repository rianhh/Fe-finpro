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


// Inisialisasi variabel $user_data
$user_data = [
    'saldo' => 0,
    'points' => 0,
];

// Ganti dengan cara Anda mengambil $user_id dari sesi (sesuai dengan implementasi login Anda)
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Ganti dengan query yang sesuai untuk mengambil saldo dan poin pengguna dari database
    $query = "SELECT saldo, points FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        $user_data['saldo'] = $data['saldo'];
        $user_data['points'] = $data['points'];
    }
} else {
    // Tampilkan pesan atau arahkan pengguna ke halaman login jika mereka belum login
    header('Location: login.php');
    exit();
}

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Balance -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">My Saldo</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $user_data['saldo']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Points -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">My Points</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $user_data['points']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-donate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Voucher Saya</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <?php
        // Query untuk mengambil data hasil redeem
        $user_id = $_SESSION['user_id']; // Ganti dengan cara Anda mengambil user ID dari sesi
        $redeem_query = "SELECT reward_name FROM redeem_history WHERE user_id = $user_id";
        $redeem_result = mysqli_query($conn, $redeem_query);

        if ($redeem_result && mysqli_num_rows($redeem_result) > 0) {
            while ($row = mysqli_fetch_assoc($redeem_result)) {
                $reward_name = $row['reward_name'];
        ?>
            <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <h7 class="card-title"><?php echo $reward_name; ?></h7>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        <?php
            }
        } else {
            // Tampilkan pesan jika tidak ada hasil redeem
            echo "Anda belum memiliki hasil redeem.";
        }
        ?>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">History</h1>
    </div>
    <?php
    // Query untuk mengambil data transaksi pengguna
    $query = "SELECT * FROM transactions WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);
    ?>

        <div class="row">
            <div class="col-xl-12 col-md-6 mb-4">
            <div>
                

            <div class="table-responsive">
                <table class="table table-striped table-borderless" style="color:black">
                    <thead>
                    <tr>
                    <th>Nomor HP</th>
                    <th>Provider</th>
                    <th>Jumlah</th>
                    <th>Metode Pembayaran</th>
                    <th>Tanggal Transaksi</th>
                    <th>Receipt</th>
                        </tr>
                    </thead>
                        <tbody>
                        <?php
                        $counter = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['phone_number'] . '</td>';
                        echo '<td>' . $row['providers'] . '</td>';
                        echo '<td>' . number_format($row['amount']) . '</td>';
                        echo '<td>' . $row['payment_method'] . '</td>';
                        echo '<td>' . $row['transaction_date'] . '</td>';
                        echo '<td> <a href="receipt.php?id=' . $row['id'] . '">Lihat Receipt</a></td>';
                        echo '</tr>';
                        $counter++;
                        }
                        ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
