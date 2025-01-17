<?php
session_start();
include_once("../../config/conn.php");

if (isset($_SESSION['login'])) {
  echo "<meta http-equiv='refresh' content='0; url=../..'>";
  die();
}

if (isset($_POST['klik'])) {
  $username = stripslashes($_POST['nama']);
  $password = $_POST['alamat'];
  if ($username == 'admin') {
    if ($password == 'admin') {
      $_SESSION['login'] = true;
      $_SESSION['id'] = null;
      $_SESSION['username'] = 'admin';
      $_SESSION['akses'] = 'admin';
      echo "<meta http-equiv='refresh' content='0; url=../admin'>";
      die();
    }
  } else {
    $cek_username = $pdo->prepare("SELECT * FROM dokter WHERE nama = '$username'; ");
    try{
        $cek_username->execute();
        if($cek_username->rowCount()==1){
            $baris = $cek_username->fetchAll(PDO::FETCH_ASSOC);
            if($password == $baris[0]['alamat']){
              $_SESSION['login'] = true;
              $_SESSION['id'] = $baris[0]['id'];
              $_SESSION['username'] = $baris[0]['nama'];
              $_SESSION['akses'] = 'dokter';
              echo "<meta http-equiv='refresh' content='0; url=../dokter/index.php'>";
              die();
            }
        }
    } catch(PDOException $e){
      $_SESSION['error'] = $e->getMessage();
      echo "<meta http-equiv='refresh' content='0;'>";
      die();
    }
  }
  $_SESSION['error'] = 'Username dan Password Tidak Cocok';
  echo "<meta http-equiv='refresh' content='0;'>";
  die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LogIn Dokter</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
<style>
  .form{
    background-color : rgba(255, 255, 255, 0.8);
    border-radius: 1rem;
  }
</style>
</head>
  <body>
  <section class="vh-100" style="background-color: #777777;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 2rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
              <img src="../../medical_wemen.jpg" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <?php if (isset($message)) { ?>
                    <div class="alert"><?php echo $message; ?></div>
                  <?php } ?>

                  <form method="POST">
                  
                  
                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Login sebagai Pegawai, Dokter atau Admin</h5>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="text" name="nama" id="form2Example17" class="form-control form-control-lg" required />
                      <label class="form-label" for="form2Example17">Username</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                      <input type="password" name="alamat" id="form2Example27" class="form-control form-control-lg" required />
                      <label class="form-label" for="form2Example27">Password</label>
                    </div>

                    <div class="pt-1 mb-4">
                      <button name="klik" type="submit" class="btn-primary btn-dark btn-lg btn-block" >Login</button>
                    </div>

                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  </body>

<!-- </head>
<body>
<div class="container">
  <div class="left-box">
    <h1 class="fw-bold">BK-Poliklinik</h1>
    <p>Login untuk Dokter</p>
  </div>
  <div class="right-box">
    
    
  </div>
</div>
</body>
</html> -->

