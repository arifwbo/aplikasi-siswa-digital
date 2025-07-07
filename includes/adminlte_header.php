<?php
// AdminLTE Header Template
// This file can be included by other pages to maintain consistent styling
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? $page_title : 'Aplikasi Siswa Digital'; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <?php if (isset($additional_css)) echo $additional_css; ?>
</head>
<body class="<?php echo isset($body_class) ? $body_class : 'hold-transition sidebar-mini'; ?>">
<?php if (isset($show_wrapper) && $show_wrapper): ?>
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo isset($logout_url) ? $logout_url : '../logout.php'; ?>">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="<?php echo isset($home_url) ? $home_url : 'dashboard.php'; ?>" class="brand-link">
            <i class="fas fa-graduation-cap brand-image img-circle elevation-3" style="opacity: .8; margin-left: 10px;"></i>
            <span class="brand-text font-weight-light">Siswa Digital</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php if (isset($menu_items) && is_array($menu_items)): ?>
                        <?php foreach ($menu_items as $item): ?>
                        <li class="nav-item">
                            <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo isset($item['active']) && $item['active'] ? 'active' : ''; ?>">
                                <i class="<?php echo $item['icon']; ?>"></i>
                                <p><?php echo $item['title']; ?></p>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
<?php endif; ?>