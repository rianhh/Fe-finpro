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
            <div class="row">
            <div class="col-xl-6 col-md-6 mb-4 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="text-center">69 Wallet</h1>
                            <p class="text-center">Jl. Taman Melati, Bekasi, West Java<br>(021)8475937582</p>
                            <p class="text-center">{{ str_pad('', 41, '=') }}</p>
                            <table>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>: {{ $data->format('d M Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td>OrderID</td>
                                    <td>: {{ $transaksi->id_transaksi }}</td>
                                </tr>
                                <tr>
                                    <td>Jumlah</td>
                                    <td>: {{ $transaksi->total_item }}</td>
                                </tr>
                            </table>
                            {{ str_pad('', 41, '=') }}

                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 40%">{{ $tranDetail->produk->nama_produk }}</td>
                                    <td style="width: 50%">{{ $tranDetail->jumlah }}</td>
                                    <td>Rp{{ number_format($tranDetail->harga_satuan * $tranDetail->jumlah) }}</td>
                                </tr>
                            </table>
                            <br>
                            {{ str_pad('', 41, '=') }}
                            <div class="d-flex justify-content-between">
                                <p>Total :</p>
                                <p>Rp.{{ number_format($transaksi->total_harga) }}</p>
                            </div>
                            <p class="text-center pt-5">Terimakasih dan semoga harimu menyenangkan!</p>
                            {{-- <div class="d-flex justify-content-center">{!! DNS1D::getBarcodeHTML($transaksi->id_transaksi, 'C128', 3, 60) !!}</div> --}}
                            <p class="text-center pt-3"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
include('includes/scripts.php');
include('includes/footer.php');
?>
