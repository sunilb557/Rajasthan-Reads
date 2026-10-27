<?php
$metaTitle = isset($title) ? $title : 'Admin - ' . SITE_NAME;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->escape($metaTitle) ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= $this->vite_asset('resources/css/app.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="hidden md:flex flex-col w-64 bg-rajasthan-900 text-white">
            <div class="p-6 border-b border-rajasthan-800">
                <h1 class="text-xl font-bold">RajasthanReads</h1>
                <p class="text-rajasthan-200 text-sm">Admin Panel</p>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4">
                <a href="<?= BASE_URL ?>/admin" class="flex items-center gap-3 px-6 py-3 hover:bg-rajasthan-800 transition-colors <?= $_SERVER['REQUEST_URI'] === '/rajasthan_reads/public/admin' || $_SERVER['REQUEST_URI'] === '/rajasthan_reads/public/admin/' ? 'bg-rajasthan-800' : '' ?>">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="<?= BASE_URL ?>/admin/books" class="flex items-center gap-3 px-6 py-3 hover:bg-rajasthan-800 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/admin/books') !== false ? 'bg-rajasthan-800' : '' ?>">
                    <i class="fas fa-book w-5"></i>
                    <span>Books</span>
                </a>
                
                <a href="<?= BASE_URL ?>/admin/authors" class="flex items-center gap-3 px-6 py-3 hover:bg-rajasthan-800 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/admin/authors') !== false ? 'bg-rajasthan-800' : '' ?>">
                    <i class="fas fa-users w-5"></i>
                    <span>Authors</span>
                </a>

                <a href="<?= BASE_URL ?>/admin/categories" class="flex items-center gap-3 px-6 py-3 hover:bg-rajasthan-800 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'bg-rajasthan-800' : '' ?>">
                    <i class="fas fa-tags w-5"></i>
                    <span>Categories</span>
                </a>

                <a href="<?= BASE_URL ?>/admin/contact" class="flex items-center gap-3 px-6 py-3 hover:bg-rajasthan-800 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/admin/contact') !== false ? 'bg-rajasthan-800' : '' ?>">
                    <i class="fas fa-envelope w-5"></i>
                    <span>Contact Messages</span>
                </a>

                <a href="<?= BASE_URL ?>/admin/settings" class="flex items-center gap-3 px-6 py-3 hover:bg-rajasthan-800 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/admin/settings') !== false ? 'bg-rajasthan-800' : '' ?>">
                    <i class="fas fa-cog w-5"></i>
                    <span>Site Settings</span>
                </a>
                
                <a href="<?= BASE_URL ?>/" class="flex items-center gap-3 px-6 py-3 hover:bg-rajasthan-800 transition-colors mt-8">
                    <i class="fas fa-home w-5"></i>
                    <span>View Site</span>
                </a>
                
                <form method="POST" action="<?= BASE_URL ?>/logout" class="mt-auto px-6 py-4 border-t border-rajasthan-800">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                    <button type="submit" class="flex items-center gap-3 text-rajasthan-200 hover:text-white transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <header class="md:hidden bg-rajasthan-900 text-white p-4 flex items-center justify-between">
                <h1 class="font-bold">Admin Panel</h1>
                <button id="mobile-menu-btn" class="text-white">
                    <i class="fas fa-bars"></i>
                </button>
            </header>
            
            <!-- Flash Messages -->
            <?php if (!empty($_SESSION['flash']['success'])): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4">
                    <?= $this->escape($_SESSION['flash']['success']) ?>
                </div>
            <?php unset($_SESSION['flash']['success']); endif; ?>
            
            <?php if (!empty($_SESSION['flash']['error'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4">
                    <?= $this->escape($_SESSION['flash']['error']) ?>
                </div>
            <?php unset($_SESSION['flash']['error']); endif; ?>
            
            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <?= $content ?>
            </main>
        </div>
    </div>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.querySelector('aside').classList.toggle('hidden');
        });
    </script>
</body>
</html>
