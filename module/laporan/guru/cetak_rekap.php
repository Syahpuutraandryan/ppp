<?php
    // === PANGGIL AUTOLOAD COMPOSER DAN NAMESPACE DI SINI (BENAR) ===
    require_once 'C:/xampp/htdocs/SmartMI/vendor/autoload.php';
    use Spipu\Html2Pdf\Html2Pdf;
    use Spipu\Html2Pdf\Exception\Html2PdfException;
    session_start();
if (!empty($_SESSION['nama'])) {
    $uidi = $_SESSION['idu'];
    $usre = $_SESSION['nama'];
    $level = $_SESSION['level'];
    $klss = $_SESSION['idk'];
    $ortu = $_SESSION['ortu'];
    $idd = $_SESSION['id'];

    include "../../../config/conn.php";
    include "../../../config/fungsi.php";


    // === MULAI AMBIL DATA ===
    $acuan = $_POST['idj'];
    $tgl_lengkap = $_POST['tgl_lengkap'];
    $sqlacuan = mysqli_query($koneksi, "SELECT * FROM jadwal WHERE idj='$acuan'");
    $rss = mysqli_fetch_array($sqlacuan);
    $sqlkss = mysqli_query($koneksi, "SELECT * FROM kelas WHERE idk='$rss[idk]'");
    $kss = mysqli_fetch_array($sqlkss);
    $sqlmp = mysqli_query($koneksi, "SELECT * FROM mata_pelajaran WHERE idm='$rss[idm]'");
    $nama_mp = mysqli_fetch_array($sqlmp);
    $sqlidh = mysqli_query($koneksi, "SELECT * FROM hari WHERE idh='$rss[idh]'");
    $nama_hari = mysqli_fetch_array($sqlidh);

    $pecah = explode(" ", $tgl_lengkap);
    $satu = $pecah[0];
    $dua = $pecah[1];
    $tahun1 = $pecah[2];

    if (in_array($dua, ["Juli", "Agustus", "September", "Oktober", "November", "Desember"])) {
        $tahun2 = $tahun1 + 1;
        $tahun_ajaran = "Tahun Ajaran $tahun1 - $tahun2";
    } else {
        $tahun2 = $tahun1 - 1;
        $tahun_ajaran = "Tahun Ajaran $tahun2 - $tahun1";
    }

    $sqlabsen = mysqli_query($koneksi, "SELECT DISTINCT tgl FROM absen WHERE idj='$acuan'");
    $jumlahtanggal = mysqli_num_rows($sqlabsen);
    $content = "<h3>Laporan Data Absensi Kelas $kss[nama] | $nama_mp[nama_mp]</h3>
                <p><b>$tahun_ajaran</b><br>$nama_hari[hari], $rss[jam_mulai] - $rss[jam_selesai]</p>
                <table cellpadding=0 cellspacing=0>
                <tr>
                    <td align='center' style='border: 1px solid #000; padding: 5px; background-color:#d0e9c6;' rowspan=2><b>Siswa</b></td>
                    <td align='center' style='border: 1px solid #000; padding: 5px; background-color:#d0e9c6;' colspan='$jumlahtanggal'><b>Tanggal (TGL/BLN)</b></td>
                </tr>
                <tr>";

    while ($tglnya = mysqli_fetch_array($sqlabsen)) {
        $pecah = explode("-", $tglnya['tgl']);
        $tiga = $pecah[2];
        $dua = $pecah[1];
        $content .= "<td align='center' style='border: 1px solid #000; padding: 5px; background-color:#faf2cc;'><b>$tiga/$dua</b></td>";
    }

    $content .= "</tr>";
    $sqlrss = mysqli_query($koneksi, "SELECT * FROM siswa WHERE idk='$rss[idk]'");
    while ($siswanya = mysqli_fetch_array($sqlrss)) {
        $content .= "<tr>
                        <td align='center' style='border: 1px solid #000; padding: 5px; background-color:#faf2cc;'>$siswanya[nama]</td>";
        $sqlabsen2 = mysqli_query($koneksi, "SELECT ket FROM absen WHERE ids='$siswanya[ids]' AND idj='$acuan'");
        while ($ketnya = mysqli_fetch_array($sqlabsen2)) {
            $content .= "<td align='center' style='border: 1px solid #000; padding: 5px;'>$ketnya[ket]</td>";
        }
        $content .= "</tr>";
    }

    $content .= "</table>
                <br><br>
                <b>Keterangan Absensi</b>
                <p>A = Tidak Masuk Tanpa Keterangan<br>
                I = Tidak Masuk Ada Surat Ijin<br>
                S = Tidak Masuk Ada Surat Dokter<br>
                M = Hadir</p>";

    // === KONVERSI KE PDF ===
    try {
        $html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', [10, 10, 10, 10]);
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->output('Laporan_Absensi_Kelas.pdf');
    } catch (Html2PdfException $e) {
        echo $e->getMessage();
    }

} else {
    echo "<center><h2>Anda Harus Login Terlebih Dahulu</h2>
          <a href='index.php'><b>Klik ini untuk Login</b></a></center>";
}
?>
