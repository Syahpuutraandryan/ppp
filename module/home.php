<?php echo "<br>"; ?>
<?php echo "<h3>Selamat Datang di Website SmartMI</h3>"; ?>
<?php
include "config/conn.php";

// ===== Statistik Dasar =====
$q_guru = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM guru");
$guru = mysqli_fetch_assoc($q_guru)['total'];

$q_siswa = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa");
$siswa = mysqli_fetch_assoc($q_siswa)['total'];

$q_kelas = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kelas");
$kelas = mysqli_fetch_assoc($q_kelas)['total'];

$q_absen = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM absen");
$absen = mysqli_fetch_assoc($q_absen)['total'];

// ===== Data Siswa per Kelas =====
$chart_labels = [];
$data_kelas = [];

$q = mysqli_query($koneksi, "
  SELECT k.idk, k.nama, COUNT(s.ids) AS total 
  FROM kelas k 
  LEFT JOIN siswa s ON s.idk = k.idk 
  GROUP BY k.idk, k.nama 
  ORDER BY k.idk ASC
");

if ($q) {
  while ($r = mysqli_fetch_assoc($q)) {
    $chart_labels[] = $r['nama'];
    $data_kelas[] = $r['total'];
  }
}

// ===== Data Absensi per Hari (Senin–Jumat) =====
$hari_labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'];
$absen_per_hari = [0, 0, 0, 0, 0];

$q_absen_per_hari = mysqli_query($koneksi, "
  SELECT DAYOFWEEK(tgl) AS hari, COUNT(*) AS total
  FROM absen
  GROUP BY DAYOFWEEK(tgl)
");

while ($r = mysqli_fetch_assoc($q_absen_per_hari)) {
  // DAYOFWEEK: Minggu=1, Senin=2, ... Sabtu=7
  $idx = (int)$r['hari'] - 2; // Senin = index 0
  if ($idx >= 0 && $idx <= 4) {
    $absen_per_hari[$idx] = (int)$r['total'];
  }
}
?>

<link rel="stylesheet" href="css/home.css">

<div class="container-fluid mt-4 dashboard-wrapper">
  <h3 class="mb-4">Dashboard Statistik</h3>

  <!-- Ringkasan Data -->
  <div class="row text-center">
    <div class="col-md-3 mb-3">
      <div class="card shadow-sm stat-card bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title">Guru</h5>
          <h2 class="stat-number"><?= number_format($guru) ?></h2>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm stat-card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">Siswa</h5>
          <h2 class="stat-number"><?= number_format($siswa) ?></h2>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm stat-card bg-warning text-white">
        <div class="card-body">
          <h5 class="card-title">Kelas</h5>
          <h2 class="stat-number"><?= number_format($kelas) ?></h2>
        </div>
      </div>
    </div>

    <div class="col-md-3 mb-3">
      <div class="card shadow-sm stat-card bg-danger text-white">
        <div class="card-body">
          <h5 class="card-title">Absensi</h5>
          <h2 class="stat-number"><?= number_format($absen) ?></h2>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart Row -->
  <div class="row mt-4 justify-content-center">
    <div class="col-lg-6 mb-3">
      <div class="card shadow-sm chart-card">
        <div class="card-header bg-dark"></div>
        <div class="card-body chart-container">
          <canvas id="chartKelas"></canvas>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mb-3">
      <div class="card shadow-sm chart-card">
        <div class="card-header bg-dark"></div>
        <div class="card-body chart-container">
          <canvas id="chartAbsensi"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctxKelas = document.getElementById('chartKelas').getContext('2d');
new Chart(ctxKelas, {
  type: 'pie',
  data: {
    labels: <?= json_encode($chart_labels) ?>,
    datasets: [{
      label: 'Jumlah Siswa',
      data: <?= json_encode($data_kelas) ?>,
      backgroundColor: ['#2563eb','#16a34a','#f59e0b','#dc2626','#6f42c1','#20c997'],
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom' },
      title: { display: true, text: 'Distribusi Siswa per Kelas' }
    }
  }
});

const ctxAbsensi = document.getElementById('chartAbsensi').getContext('2d');
new Chart(ctxAbsensi, {
  type: 'bar',
  data: {
    labels: <?= json_encode($hari_labels) ?>,
    datasets: [{
      label: 'Jumlah Absensi',
      data: <?= json_encode($absen_per_hari) ?>,
      backgroundColor: '#2563eb',
      borderRadius: 8,
      borderSkipped: false
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
      tooltip: { mode: 'index', intersect: false },
      title: { display: true, text: 'Grafik absensi' }
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: { color: '#333', font: { size: 13 } }
      },
      y: {
        beginAtZero: true,
        grid: { color: 'rgba(0,0,0,0.05)' },
        ticks: { color: '#333' }
      }
    }
  }
});
</script>
