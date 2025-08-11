<?php
session_start();
include('conf/config.php'); // Get configuration file

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $mysqli->prepare("SELECT email, password, admin_id FROM iB_admin WHERE email=?");
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($db_email, $db_password, $admin_id);
    $stmt->fetch();

    // Direct plain text password check
    if ($password === $db_password) {
      $_SESSION['admin_id'] = $admin_id;
      header("location:pages_dashboard.php");
      exit();
    } else {
      $err = "Access Denied. Please check your credentials.";
    }
  } else {
    $err = "Access Denied. Please check your credentials.";
  }
}

// Fetch system settings
$ret = "SELECT * FROM `iB_SystemSettings` ";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($auth = $res->fetch_object()) {
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<?php include("dist/_partials/head.php"); ?>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <p><?php echo $auth->sys_name; ?></p>
    </div>
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Log In To Start Administrator Session</p>

        <?php if (isset($err)) { ?>
          <div class="alert alert-danger"><?php echo $err; ?></div>
        <?php } ?>

        <form method="post">
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4"></div>
            <div class="col-8">
              <button type="submit" name="login" class="btn btn-danger btn-block">Log In as Admin</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
</body>
</html>
<?php
}
?>
