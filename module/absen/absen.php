<?php
$today = hari_ina(date("l")); // misalnya hasilnya: Senin, Selasa, dst.
$query = mysqli_query($koneksi, "SELECT * FROM hari WHERE hari='$today'");
$id_hari = mysqli_fetch_array($query);

$now = date("H:i:s");

// Aktifkan jadwal yang sedang berlangsung
$aktifkan = mysqli_query($koneksi, "UPDATE jadwal 
    SET aktif=1 
    WHERE idh={$id_hari['idh']} 
    AND jam_mulai <= '$now' 
    AND jam_selesai >= '$now'");

// Nonaktifkan semua jadwal di luar hari ini
$nonaktifkan = mysqli_query($koneksi, "UPDATE jadwal 
    SET aktif=0 
    WHERE idh <> {$id_hari['idh']}");

// Nonaktifkan jadwal di luar jam pelajaran aktif
$nonaktifkan2 = mysqli_query($koneksi, "UPDATE jadwal 
    SET aktif=0 
    WHERE idh={$id_hari['idh']} 
    AND (jam_mulai >= '$now' OR jam_selesai <= '$now')");

// Cek apakah jadwal yang diakses sedang aktif
$tentukan = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE idj='{$_GET['idj']}'");
$aktifgak = mysqli_fetch_array($tentukan);

if ($aktifgak['aktif'] == 1) {
    include "input_absen.php";
} else {
    echo "
    <center>
        <br><h3>Maaf, Anda tidak bisa mengabsen siswa di luar jam pelajaran.</h3>
        <a href='media.php?module=jadwal_mengajar'><b>Kembali</b></a>
    </center>";
}
?>