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

// Ambil data saldo dan poin pengguna dari database
$query = "SELECT saldo, points FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $user_data = mysqli_fetch_assoc($result);
    $saldo = $user_data['saldo'];
    $points = $user_data['points'];
} else {
    // Handle kesalahan jika data pengguna tidak ditemukan
    echo "Data pengguna tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneNumber = $_POST['phoneNumber'];
    $providers = $_POST['providers'];
    $pulsa = $_POST['pulsa'];
    $metodePembayaran = $_POST['payment'];
    $selectedVoucher = isset($_POST['voucher']) ? $_POST['voucher'] : '0';
    $transaction_date = date('Y-m-d H:i:s');

    // Hitung poin bonus
    $poinBonus = 0;
    if ($pulsa == 5000) {
        $poinBonus = 2;
    } elseif ($pulsa == 10000) {
        $poinBonus = 4;
    } elseif ($pulsa == 15000) {
        $poinBonus = 6;
    }

    if ($metodePembayaran === 'saldo') {
        // Tentukan diskon berdasarkan voucher yang dipilih
        $diskon = 0;
        if ($selectedVoucher == 'Voucher Diskon 10%') {
            $diskon = 0.10;
        } elseif ($selectedVoucher == 'Voucher Diskon 5%') {
            $diskon = 0.05;
        } elseif ($selectedVoucher == 'Voucher Diskon 20%') {
            $diskon = 0.20;
        } 

        if ($selectedVoucher !== '0') {
            // Hapus voucher yang digunakan dari tabel redeem_history
            $deleteVoucherQuery = "DELETE FROM redeem_history WHERE user_id = $user_id AND reward_name = '$selectedVoucher'";
            if (mysqli_query($conn, $deleteVoucherQuery)) {
                echo "Voucher '$selectedVoucher' telah digunakan. <br>";
            } else {
                echo "Gagal menghapus voucher.";
            }
        }

        // Hitung total harga dengan diskon
        $hargaSetelahDiskon = $pulsa - ($pulsa * $diskon);

        // Cek apakah saldo mencukupi
        if ($saldo >= $hargaSetelahDiskon) {
            // Kurangi saldo sesuai harga yang sudah didiskon
            $saldo -= $hargaSetelahDiskon;

            // Tambahkan poin bonus
            $points += $poinBonus;

            // Simpan saldo yang diperbarui ke dalam database
            $updateQuery = "UPDATE users SET saldo = $saldo, points = $points WHERE id = $user_id";
            $insertQuery = "INSERT INTO transactions (user_id, phone_number, providers, amount, payment_method, transaction_date) VALUES ($user_id, '$phoneNumber', '$providers', $hargaSetelahDiskon, 'Saldo', '$transaction_date')";

            if (mysqli_query($conn, $updateQuery) && mysqli_query($conn, $insertQuery)) {
                echo "Pembayaran berhasil menggunakan saldo. Saldo Anda sekarang: $saldo. Poin Anda sekarang + Bonus point setiap transaksi: $points";
            } else {
                echo "Gagal memperbarui saldo.";
            }
        } else {
            echo "Saldo tidak mencukupi untuk pembayaran.";
        }
        
      
    } elseif ($metodePembayaran === 'points') {
        // Konversi harga pulsa ke poin
        $poinYangDibutuhkan = 0;

        if ($pulsa == 5000) {
            $poinYangDibutuhkan = 20;
        } elseif ($pulsa == 10000) {
            $poinYangDibutuhkan = 30;
        } elseif ($pulsa == 15000) {
            $poinYangDibutuhkan = 40;
        }

       // Cek apakah poin mencukupi
       if ($points >= $poinYangDibutuhkan) {
        // Kurangi poin
        $points -= $poinYangDibutuhkan;
        $saldo += $pulsa;

        // Simpan poin dan saldo yang diperbarui ke dalam database
        $updateQuery = "UPDATE users SET points = $points, saldo = $saldo WHERE id = $user_id";
        $insertQuery = "INSERT INTO transactions (user_id, phone_number, providers, amount, payment_method, transaction_date) VALUES ($user_id, '$phoneNumber', '$providers', $pulsa, 'Poin', '$transaction_date')";
        
        if (mysqli_query($conn, $updateQuery) && mysqli_query($conn, $insertQuery)) {
            echo "Pembayaran berhasil menggunakan poin. Poin Anda sekarang: $points. Saldo Anda sekarang: $saldo";
        } else {
            echo "Gagal memperbarui poin atau saldo.";
        }
    } else {
        echo "Poin tidak mencukupi untuk pembayaran.";
    }
}
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4 mx-auto">
            <div class="card shadow h-100">
                <div class="card-body">
                    <h5 class="card-title">Pembayaran Pulsa</h5>
                    <form method="post">
                        <div class="form-group">
                            <label>Nomor Handphone</label>
                            <input type="text" class="form-control" name="phoneNumber" value="<?= $_GET['phoneNumber'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Provider Pulsa</label>
                            <input type="text" class="form-control" name="providers" value="Telkomsel" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nominal Pulsa</label>
                            <input type="text" class="form-control" name="pulsa" value="<?= $_GET['amount'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" value="saldo" id="paymentSaldo">
                                <label class="form-check-label" for="paymentSaldo">
                                    Saldo (Sisa Saldo: Rp <?= $saldo; ?>)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment" value="points" id="paymentPoints">
                                <label class="form-check-label" for="paymentPoin">
                                    Poin (Sisa Poin: <?= $points; ?>)
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="voucherSelect">Voucher</label>
                            <select class="form-control" name="voucher" id="voucherSelect">
                                <option value="0">Tidak menggunakan voucher</option>
                                <?php
                                // Query untuk mengambil daftar voucher yang dimiliki oleh pengguna
                                $voucherQuery = "SELECT reward_name FROM redeem_history WHERE user_id = $user_id";
                                $voucherResult = mysqli_query($conn, $voucherQuery);

                                if ($voucherResult && mysqli_num_rows($voucherResult) > 0) {
                                    while ($voucher = mysqli_fetch_assoc($voucherResult)) {
                                        echo '<option value="' . $voucher['reward_name'] . '">' . $voucher['reward_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="totalHargaAfterDiscount" id="totalHargaAfterDiscountLabel">Total Harga:</label>
                            <input type="text" class="form-control" name="totalHargaAfterDiscount" id="totalHargaAfterDiscount" readonly>
                        </div>
                        <input type="hidden" id="useVoucherBtn"></input>
                        <button type="submit" class="btn btn-primary btn-block">Bayar Pulsa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    var voucherUsed = false; // Status penggunaan voucher
    var diskon = 0; // Inisialisasi diskon

    // Fungsi untuk menampilkan atau menyembunyikan pilihan voucher
    function toggleVoucherSelection() {
        var voucherSelect = document.querySelector('select[name="voucher"]');
        var totalHargaAfterDiscount = document.querySelector('input[name="totalHargaAfterDiscount"]');
        var totalHargaAfterDiscountLabel = document.getElementById('totalHargaAfterDiscountLabel');
        var totalHarga = <?= $_GET['amount'] ?>;
        
        // Periksa apakah pembayaran menggunakan poin atau saldo
        var paymentMethod = document.querySelector('input[name="payment"]:checked').value;

        if (paymentMethod === 'points') {
            // Jika pembayaran menggunakan poin, blokir pilihan voucher
            voucherSelect.disabled = true;
            voucherSelect.value = '0'; // Kembalikan ke opsi "Tidak menggunakan voucher"
            totalHargaAfterDiscount.value = ''; // Hapus total harga setelah diskon
            totalHargaAfterDiscountLabel.style.display = 'none';
        } else {
            // Jika pembayaran menggunakan saldo, aktifkan pilihan voucher
            voucherSelect.disabled = false;
            totalHargaAfterDiscountLabel.style.display = 'block';
            calculateDiscount(); // Hitung diskon saat tombol "Gunakan Voucher" ditekan
        }

        voucherUsed = !voucherUsed; // Ubah status penggunaan voucher
    }

    // Hitung diskon berdasarkan pilihan voucher
    function calculateDiscount() {
        var selectedVoucher = document.querySelector('select[name="voucher"]').value;

        if (selectedVoucher === 'Voucher Diskon 10%') {
            diskon = 0.10;
        } else if (selectedVoucher === 'Voucher Diskon 5%') {
            diskon = 0.05;
        } else if (selectedVoucher === 'Voucher Diskon 20%') {
            diskon = 0.20;
        } else {
            diskon = 0; // Tidak ada diskon jika voucher tidak dipilih
        }

        var totalHargaAfterDiscount = <?= $_GET['amount'] ?> - (<?= $_GET['amount'] ?> * diskon);
        document.querySelector('input[name="totalHargaAfterDiscount"]').value = 'Rp ' + totalHargaAfterDiscount.toFixed(2);
    }

    // Event listener saat tombol "Gunakan Voucher" ditekan
    document.getElementById('useVoucherBtn').addEventListener('click', function() {
        toggleVoucherSelection();
    });

    // Event listener saat pilihan voucher berubah
    document.querySelector('select[name="voucher"]').addEventListener('change', function() {
        calculateDiscount();
    });

    // Validasi form sebelum submit
    function confirmPayment() {
        if (confirm("Anda yakin ingin melanjutkan pembayaran?")) {
            // Jika pengguna mengklik OK, lanjutkan dengan pengiriman form
            return true;
        } else {
            // Jika pengguna mengklik Batal, batalkan pengiriman form
            return false;
        }
    }

    // Event listener saat metode pembayaran berubah
    document.querySelectorAll('input[name="payment"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            toggleVoucherSelection();
        });
    });
</script>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>