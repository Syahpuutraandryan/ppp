<?php
// Jika sudah terhubung sebelumnya, jangan re-init untuk mencegah redeclare
if (isset($koneksi) && $koneksi instanceof mysqli) { return; }

// Matikan mode exception agar fallback bisa dicoba tanpa fatal error
if (function_exists('mysqli_report')) { mysqli_report(MYSQLI_REPORT_OFF); }

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'SmartMI';
$port = (int)(getenv('DB_PORT') ?: 3308); // default sebelumnya 3308

if (!function_exists('try_connect')) {
    function try_connect($h, $u, $p, $d, $prt) {
        try {
            return mysqli_connect($h, $u, $p, $d, (int)$prt);
        } catch (Throwable $e) {
            return false;
        }
    }
}

// Coba koneksi utama terlebih dahulu
$koneksi = try_connect($host, $user, $pass, $db, $port);

// Fallback otomatis jika koneksi ditolak (mis. MySQL berjalan di 3306)
if (!$koneksi) {
    $fallbackHosts = array_values(array_unique([$host, '127.0.0.1', 'localhost']));
    $fallbackPorts = array_values(array_unique([$port, 3306, 3308]));
    foreach ($fallbackHosts as $h) {
        foreach ($fallbackPorts as $p) {
            $koneksi = try_connect($h, $user, $pass, $db, $p);
            if ($koneksi) { $host = $h; $port = (int)$p; break 2; }
        }
    }
}

if (!$koneksi) {
    $attempts = sprintf('hosts=[%s], ports=[%s]', implode(',', array_values(array_unique([$host, '127.0.0.1', 'localhost']))), implode(',', array_values(array_unique([$port, 3306, 3308]))));
    die("Koneksi gagal. Pastikan MySQL aktif dan port sesuai (config/conn.php atau ENV DB_*). Detail: $attempts. Error: " . mysqli_connect_error());
}
?>
