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

// Query untuk mengambil data transaksi pengguna
$transactionQuery = "SELECT * FROM transactions WHERE user_id = $user_id";
$transactionResult = mysqli_query($conn, $transactionQuery);

// Query untuk mengambil data history redeem pengguna
$redeemQuery = "SELECT redeem_history.redeem_date, redeem_history.reward_name
                FROM redeem_history
                WHERE redeem_history.user_id = $user_id";
$redeemResult = mysqli_query($conn, $redeemQuery);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-striped" style="color: black">
                                <thead>
                                    <tr>
                                        <th>No.</th>
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
                                    while ($row = mysqli_fetch_assoc($transactionResult)) {
                                        echo '<tr>';
                                        echo '<td>' . $counter . '</td>';
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

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-striped" style="color: black">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Tanggal Redeem</th>
                                        <th>Nama Reward</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $counter = 1;
                                    while ($row = mysqli_fetch_assoc($redeemResult)) {
                                        echo '<tr>';
                                        echo '<td>' . $counter . '</td>';
                                        echo '<td>' . $row['redeem_date'] . '</td>';
                                        echo '<td>' . $row['reward_name'] . '</td>';
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
</div>
</div>
</div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
