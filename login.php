<?php
require 'config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = $conn->query($sql);
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['role'] = $row['role'];
            $_SESSION['id_user'] = $row['id'];
            $_SESSION['id_siswa'] = $row['id_siswa'];
            if ($row['role'] == 'admin') header('Location: admin/dashboard.php');
            else header('Location: siswa/dashboard.php');
            exit;
        } else {
            $msg = 'Password salah!';
        }
    } else {
        $msg = 'User tidak ditemukan!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Aplikasi Siswa Digital</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <div class="login-logo">
                <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                <h1><b>Aplikasi Siswa</b> Digital</h1>
            </div>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Silakan login untuk memulai sesi Anda</p>

            <form method="POST">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
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
                
                <?php if ($msg): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($msg); ?>
                </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Ingat saya
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <p class="mb-1">
                <a href="#" class="text-muted">
                    <i class="fas fa-question-circle"></i> Lupa password?
                </a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Add validation feedback
    $('form').submit(function(e) {
        var username = $('input[name="username"]').val();
        var password = $('input[name="password"]').val();
        
        if (!username || !password) {
            e.preventDefault();
            
            if (!username) {
                $('input[name="username"]').addClass('is-invalid');
            } else {
                $('input[name="username"]').removeClass('is-invalid');
            }
            
            if (!password) {
                $('input[name="password"]').addClass('is-invalid');
            } else {
                $('input[name="password"]').removeClass('is-invalid');
            }
        }
    });
    
    // Remove validation classes on input
    $('input').on('input', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
</body>
</html>