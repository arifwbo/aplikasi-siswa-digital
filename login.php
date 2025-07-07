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
<html>
<head>
    <meta charset="UTF-8">
    <title>Login Aplikasi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="card shadow" style="min-width:320px;max-width:400px;">
        <div class="card-header text-center bg-primary text-white">
            <h4 class="mb-0">Login Aplikasi Siswa</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <input name="username" class="form-control" placeholder="Username" required autofocus>
                </div>
                <div class="mb-3">
                    <input name="password" type="password" class="form-control" placeholder="Password" required>
                </div>
                <?php if ($msg): ?><div class="alert alert-danger py-1"><?php echo $msg ?></div><?php endif; ?>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

        </div>
    </div>
</div>
</body>
</html>