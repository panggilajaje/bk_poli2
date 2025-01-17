<?php
include_once("../../../config/conn.php");
session_start();

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}
$id_pasien = $_SESSION['id'];
$no_rm = $_SESSION['no_rm'];
$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id_poli = $url[count($url) - 1];

if ($akses != 'pasien') {
  echo "<meta http-equiv='refresh' content='0; url=..'>";
  die();
}
?>

<div class="card-body">
  <?php
                    $poli = $pdo->prepare("SELECT d.nama_poli as poli_nama,
                                                  c.nama as dokter_nama, 
                                                  b.hari as jadwal_hari, 
                                                  b.jam_mulai as jadwal_mulai, 
                                                  b.jam_selesai as jadwal_selesai,
                                                  a.no_antrian as antrian,
                                                  a.id as poli_id

                                                  FROM daftar_poli as a

                                                  INNER JOIN jadwal_periksa as b
                                                    ON a.id_jadwal = b.id
                                                  INNER JOIN dokter as c
                                                    ON b.id_dokter = c.id
                                                  INNER JOIN poli as d
                                                    ON c.id_poli = d.id
                                                  WHERE a.id = $id_poli");
                    ?>

<?php
// Query untuk mengambil detail poli
$poli = $pdo->prepare("SELECT d.nama_poli as poli_nama,
c.nama as dokter_nama, 
b.hari as jadwal_hari, 
b.jam_mulai as jadwal_mulai, 
b.jam_selesai as jadwal_selesai,
a.no_antrian as antrian,
a.id as poli_id

FROM daftar_poli as a

INNER JOIN jadwal_periksa as b
  ON a.id_jadwal = b.id
INNER JOIN dokter as c
  ON b.id_dokter = c.id
INNER JOIN poli as d
  ON c.id_poli = d.id
WHERE a.id = $id_poli"); // Kode query detail poli Anda
$poli->execute();

// Query untuk mengambil riwayat periksa
$riwayat = $pdo->prepare(" SELECT pr.tgl_periksa, d.nama AS dokter_nama, pr.catatan, pr.biaya_periksa
    FROM periksa pr
    JOIN daftar_poli dp ON pr.id_daftar_poli = dp.id
    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
    JOIN dokter d ON jp.id_dokter = d.id
    WHERE dp.id_pasien = ?
    ORDER BY pr.tgl_periksa DESC
");
$riwayat->execute([$id_pasien]);

// Tampilkan detail poli
while($p = $poli->fetch()) {
    // Tampilkan detail poli seperti sebelumnya
}

// Tampilkan riwayat periksa
?>
<div class="card">
  <div class="card-header bg-info">
    <h3 class="card-title">Riwayat Periksa Anda</h3>
  </div>
  <div class="card-body">
    <?php if ($riwayat->rowCount() == 0): ?>
      <h5>Tidak ada riwayat periksa ditemukan.</h5>
    <?php else: ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Tanggal Periksa</th>
            <th>Dokter</th>
            <th>Catatan</th>
            <th>Biaya</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $riwayat->fetch()): ?>
            <tr>
              <td><?= $row['tgl_periksa']; ?></td>
              <td><?= $row['dokter_nama']; ?></td>
              <td><?= $row['catatan']; ?></td>
              <td><?= formatRupiah($row['biaya_periksa']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>