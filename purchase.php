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
?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Pembelian Pulsa</h1>
  </div>

  <div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-body">
                <div class="form-group">
            <b for="phone">Masukkan Nomor HP</b>
            <input type="text" class="form-control" id="phone" placeholder="Masukkan nomor" required>
            <div id="phone-error" class="text-danger"></div>
          </div>

          <div class="row">
            <?php
            $products = [
              ["<img src='img/telkomsel.png' alt='Telkomsel' class='img-fluid' style='width: 25px;'><b> 5.000</b> <br> <sub>Bayar: Rp.5.000 / 20 Point</sub> <br> <sub>(Bonus 2 point)</sub>", 5000],
              ["<img src='img/telkomsel.png' alt='Telkomsel' class='img-fluid' style='width: 25px;'><b> 10.000</b> <br> <sub>Bayar: Rp.10.000 / 30 Point</sub> <br> <sub>(Bonus 4 point)</sub>", 10000],
              ["<img src='img/telkomsel.png' alt='Telkomsel' class='img-fluid' style='width: 25px;'><b> 15.000</b> <br> <sub>Bayar: Rp.15.000 / 40 Point</sub> <br> <sub>(Bonus 6 point)</sub>", 15000],
            ];
            
            foreach ($products as $product) {
            ?>
              <div class="col-4">
              <div class="card text-center mb-3">
             <div class="card-body">
              <div class="row">
            <div class="col-10">
                <h5 class="card-title"><?= $product[0] ?></h5>
            </div>
            <div class="col-2">
                <span data-toggle="tooltip" data-placement="top" title="Bonus hanya berlaku untuk metode pembayaran saldo">
                    <i class="fa fa-info-circle" style="font-size: 20px; color: #6c757d;"></i>
                </span>
            </div>
             </div>
            <button class="btn btn-primary btn-block buyBtn" data-amount="<?= $product[1] ?>" disabled>Beli</button>
            </div>
            </div>
              </div>
            <?php
            }
            ?>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Validasi form nomor HP
  $('#phone').on('input', function() {
    var phoneNumber = $(this).val().trim();
    // Gunakan ekspresi reguler untuk memeriksa apakah nomor dimulai dengan "08" atau "+62"
    var isValid = /^(\+62|08)[0-9]{9,}$/.test(phoneNumber);
    $('.buyBtn').prop('disabled', !isValid);
    $('#phone-error').text(isValid ? '' : 'Masukkan nomor HP yang valid (awalan 08 atau +62)');
  });

  // Submit pembelian
  $('.buyBtn').click(function() {
    var nomor = $('#phone').val();
    var pulsa = $(this).data('amount');
    // Redirect ke halaman pembayaran.php dengan mengirim nomor HP dan jumlah pulsa
    window.location.href = 'pembayaran.php?phoneNumber=' + nomor + '&amount=' + pulsa; // Tambahkan parameter amount
  });
</script>
