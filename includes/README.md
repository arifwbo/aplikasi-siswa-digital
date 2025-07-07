# AdminLTE Template Integration

This document explains how to use the AdminLTE template system in the Aplikasi Siswa Digital project.

## Template Files

- `includes/adminlte_header.php` - Contains the HTML head, navbar, and sidebar
- `includes/adminlte_footer.php` - Contains the closing HTML tags and scripts

## Usage

### For Login Page
The login page uses a standalone AdminLTE implementation with the `login-page` class.

### For Dashboard/Admin Pages
To use the AdminLTE template system for admin pages:

```php
<?php
// Set page variables before including header
$page_title = "Dashboard Admin";
$show_wrapper = true;
$menu_items = [
    ['title' => 'Dashboard', 'url' => 'dashboard.php', 'icon' => 'fas fa-tachometer-alt nav-icon', 'active' => true],
    ['title' => 'Manajemen Siswa', 'url' => 'siswa.php', 'icon' => 'fas fa-users nav-icon'],
    ['title' => 'Manajemen Mapel', 'url' => 'mapel.php', 'icon' => 'fas fa-book nav-icon'],
    ['title' => 'Manajemen Nilai', 'url' => 'nilai.php', 'icon' => 'fas fa-chart-bar nav-icon']
];

// Include header
include '../includes/adminlte_header.php';
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Your page content here -->
    </div>
</section>

<?php include '../includes/adminlte_footer.php'; ?>
```

## Features

- Responsive design
- Consistent branding with graduation cap icon
- Sidebar navigation
- Professional AdminLTE styling
- Easy to extend and maintain
- CDN-based assets for fast loading