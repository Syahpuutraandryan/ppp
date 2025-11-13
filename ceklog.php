<?php
// FILE KONEKSI LOGIN / CEK LOGIN

include "config/conn.php"; // Pastikan conn.php berisi variabel $koneksi dari mysqli_connect()

$pass  = md5($_POST['password']);
$passw = $_POST['password'];
$user  = $_POST['username'];

// --- CEK USER ADMIN ---
$sql   = mysqli_query($koneksi, "SELECT * FROM user WHERE nama='$user' AND pass='$pass'");
$count = mysqli_num_rows($sql);
$rs    = mysqli_fetch_array($sql);

if ($count > 0) {
    session_start();
    $_SESSION['idu']   = $rs['idu'];
    $_SESSION['nama']  = $rs['nama'];
    $_SESSION['level'] = $rs['level'];
    $_SESSION['idk']   = "";
    $_SESSION['ortu']  = "";
    $_SESSION['id']    = $rs['id'];

    header('location:media.php?module=home');
} else {
    // --- CEK USER SISWA ---
    $mr    = md5($_POST['password']);
    $sqla  = mysqli_query($koneksi, "SELECT * FROM siswa WHERE nama='$user' AND pass='$mr'");
    $counta = mysqli_num_rows($sqla);
    $rsa   = mysqli_fetch_array($sqla);

    if ($counta > 0) {
        session_start();
        $_SESSION['idu']   = $rsa['nis'];
        $_SESSION['nama']  = $rsa['nama'];
        $_SESSION['level'] = "user";
        $_SESSION['ortu']  = $passw;
        $_SESSION['idk']   = $rsa['idk'];
        $_SESSION['id']    = "2";

        header('location:media.php?module=home');
    } else {
        // --- CEK USER GURU ---
        $gr    = md5($_POST['password']);
        $sqlz  = mysqli_query($koneksi, "SELECT * FROM guru WHERE nip='$user' AND pass='$gr'");
        $countz = mysqli_num_rows($sqlz);
        $rsz   = mysqli_fetch_array($sqlz);

        if ($countz > 0) {
            session_start();
            $_SESSION['idu']   = $rsz['nip'];
            $_SESSION['nama']  = $rsz['nama'];
            $_SESSION['idk']   = $rsz['idk'];
            $_SESSION['level'] = "guru";
            $_SESSION['ortu']  = "";
            $_SESSION['id']    = "2";

            header('location:media.php?module=home');
        } else {
            echo "<script>alert('Mohon periksa kembali Username & Password Anda'); location.href='login.php';</script>";
        }
    }
}
?>
