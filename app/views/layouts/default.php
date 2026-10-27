<?php
$metaTitle = isset($title) ? $title : DEFAULT_TITLE;
$metaDescription = isset($description) ? $description : DEFAULT_DESCRIPTION;
$metaKeywords = isset($keywords) ? $keywords : DEFAULT_KEYWORDS;
$canonical = isset($canonical) ? $canonical : BASE_URL . $_SERVER['REQUEST_URI'];
?><!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Primary Meta Tags -->
    <title><?= $this->escape($metaTitle) ?></title>
    <meta name="title" content="<?= $this->escape($metaTitle) ?>">
    <meta name="description" content="<?= $this->escape($metaDescription) ?>">
    <meta name="keywords" content="<?= $this->escape($metaKeywords) ?>">
    <meta name="author" content="<?= SITE_NAME ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= $this->escape($canonical) ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $this->vite_asset('resources/css/app.css') ?>">

    <!-- PWA Manifest -->
    <link rel="manifest" href="<?= BASE_URL ?>/manifest.json">
    <meta name="theme-color" content="<?= PWA_THEME_COLOR ?>">
    <meta name="mobile-web-app-capable" content="yes">

    <?php if (!empty($schemaJson)): ?>
    <script type="application/ld+json">
    <?= json_encode($schemaJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?>
    </script>
    <?php endif; ?>
</head>
<body>
    <!-- Top Bar -->
    <div class="bg-rajasthan-900 py-2 hidden md:block">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-4 text-rajasthan-100 text-[10px] font-black uppercase tracking-widest">
                <span class="flex items-center gap-1.5"><i class="fas fa-landmark text-rajasthan-400"></i> Preserving Digital History</span>
                <span class="w-1 h-1 bg-rajasthan-400 rounded-full"></span>
                <span class="flex items-center gap-1.5"><i class="fas fa-globe text-rajasthan-400"></i> Open Access Archive</span>
            </div>
            <div class="flex items-center gap-4 text-rajasthan-100 text-[10px] font-black uppercase tracking-widest">
                <?php if (!empty($_SESSION['user'])): ?>
                    <span class="text-rajasthan-400 italic">Logged in as <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-[100] bg-white/80 backdrop-blur-xl border-b border-gray-100">
        <nav class="container mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <a href="<?= BASE_URL ?>/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-rajasthan-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-rajasthan-600/30 group-hover:rotate-12 transition-transform">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <span class="text-xl font-black text-gray-900 tracking-tighter block leading-none">RAJASTHAN<span class="text-rajasthan-600">READS</span></span>
                    <span class="text-[8px] font-black uppercase tracking-[0.3em] text-gray-400 block mt-1">Cultural Digital Archive</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <ul class="hidden lg:flex items-center gap-2">
                <li><a href="<?= BASE_URL ?>/" class="nav-link <?= $_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == parse_url(BASE_URL, PHP_URL_PATH) . '/' ? 'active' : '' ?>">Home</a></li>
                <li><a href="<?= BASE_URL ?>/books" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/books') && !str_contains($_SERVER['REQUEST_URI'], '/admin') ? 'active' : '' ?>">Library</a></li>
                <li><a href="<?= BASE_URL ?>/authors" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/authors') ? 'active' : '' ?>">Authors</a></li>
                <li><a href="<?= BASE_URL ?>/categories" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/categories') ? 'active' : '' ?>">Categories</a></li>
                <li><a href="<?= BASE_URL ?>/about-us" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/about-us') ? 'active' : '' ?>">About</a></li>
                <li><a href="<?= BASE_URL ?>/contact-us" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], '/contact-us') ? 'active' : '' ?>">Contact</a></li>
                
                <div class="w-px h-6 bg-gray-100 mx-4"></div>
                
                <?php if (!empty($_SESSION['user'])): ?>
                    <li><a href="<?= BASE_URL ?>/admin/books" class="nav-link">Admin Panel</a></li>
                    <li>
                        <form method="POST" action="<?= BASE_URL ?>/logout" class="inline">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                            <button type="submit" class="ml-4 px-6 py-2.5 bg-gray-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-gray-800 transition-all shadow-lg shadow-gray-900/10">Logout</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>/login" class="ml-4 px-6 py-2.5 bg-rajasthan-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rajasthan-700 transition-all shadow-lg shadow-rajasthan-600/10">Admin Login</a></li>
                <?php endif; ?>
            </ul>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="lg:hidden w-10 h-10 flex items-center justify-center text-gray-600 focus:outline-none">
                <i class="fas fa-bars-staggered text-xl"></i>
            </button>
        </nav>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" id="mobile-menu-backdrop"></div>
        <div class="absolute right-0 top-0 bottom-0 w-80 bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-out" id="mobile-menu-content">
            <div class="p-6 flex items-center justify-between border-b border-gray-100">
                <span class="text-lg font-black text-rajasthan-900 tracking-tighter">NAVIGATION</span>
                <button id="mobile-menu-close" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-900">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 overflow-y-auto p-6">
                <ul class="space-y-4">
                    <li><a href="<?= BASE_URL ?>/" class="block text-sm font-bold text-gray-600 hover:text-rajasthan-600 transition-colors uppercase tracking-widest">Home</a></li>
                    <li><a href="<?= BASE_URL ?>/books" class="block text-sm font-bold text-gray-600 hover:text-rajasthan-600 transition-colors uppercase tracking-widest">Library Repository</a></li>
                    <li><a href="<?= BASE_URL ?>/authors" class="block text-sm font-bold text-gray-600 hover:text-rajasthan-600 transition-colors uppercase tracking-widest">Literary Authors</a></li>
                    <li><a href="<?= BASE_URL ?>/categories" class="block text-sm font-bold text-gray-600 hover:text-rajasthan-600 transition-colors uppercase tracking-widest">Thematic Categories</a></li>
                    <li><a href="<?= BASE_URL ?>/about-us" class="block text-sm font-bold text-gray-600 hover:text-rajasthan-600 transition-colors uppercase tracking-widest">About Archive</a></li>
                    <li><a href="<?= BASE_URL ?>/contact-us" class="block text-sm font-bold text-gray-600 hover:text-rajasthan-600 transition-colors uppercase tracking-widest">Contact Us</a></li>
                </ul>

                <div class="my-8 h-px bg-gray-100"></div>

                <div class="space-y-4">
                    <?php if (!empty($_SESSION['user'])): ?>
                        <a href="<?= BASE_URL ?>/admin/books" class="block w-full px-6 py-4 bg-gray-900 text-white rounded-xl text-center text-xs font-black uppercase tracking-widest">Admin Panel</a>
                        <form method="POST" action="<?= BASE_URL ?>/logout">
                            <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                            <button type="submit" class="block w-full px-6 py-4 bg-gray-100 text-gray-900 rounded-xl text-center text-xs font-black uppercase tracking-widest hover:bg-gray-200 transition-all">Logout</button>
                        </form>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/login" class="block w-full px-6 py-4 bg-rajasthan-600 text-white rounded-xl text-center text-xs font-black uppercase tracking-widest shadow-lg shadow-rajasthan-600/20">Admin Login</a>
                    <?php endif; ?>
                </div>
            </nav>

            <div class="p-6 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-center gap-4">
                    <a href="<?= htmlspecialchars($site_settings['social_twitter'] ?? '#') ?>" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-400 hover:text-rajasthan-600 shadow-sm border border-gray-100 transition-all"><i class="fab fa-twitter text-sm"></i></a>
                    <a href="<?= htmlspecialchars($site_settings['social_facebook'] ?? '#') ?>" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-400 hover:text-rajasthan-600 shadow-sm border border-gray-100 transition-all"><i class="fab fa-facebook-f text-sm"></i></a>
                    <a href="<?= htmlspecialchars($site_settings['social_instagram'] ?? '#') ?>" target="_blank" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-gray-400 hover:text-rajasthan-600 shadow-sm border border-gray-100 transition-all"><i class="fab fa-instagram text-sm"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    <div class="container mx-auto px-6 mt-6">
        <?php if (!empty($_SESSION['flash']['success'])): ?>
            <div class="flex items-center gap-3 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 shadow-sm animate-fade-in-down">
                <i class="fas fa-check-circle"></i>
                <span class="text-sm font-bold uppercase tracking-wider"><?= $this->escape($_SESSION['flash']['success']) ?></span>
            </div>
        <?php unset($_SESSION['flash']['success']); endif; ?>

        <?php if (!empty($_SESSION['flash']['error'])): ?>
            <div class="flex items-center gap-3 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 shadow-sm animate-fade-in-down">
                <i class="fas fa-exclamation-circle"></i>
                <span class="text-sm font-bold uppercase tracking-wider"><?= $this->escape($_SESSION['flash']['error']) ?></span>
            </div>
        <?php unset($_SESSION['flash']['error']); endif; ?>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-12">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-950 text-white pt-20 pb-10 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-rajasthan-600/10 rounded-full blur-[120px] -mr-48 -mt-48"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <!-- Branding -->
                <div class="space-y-6">
                    <a href="<?= BASE_URL ?>/" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-rajasthan-600">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span class="text-xl font-black text-white tracking-tighter">RAJASTHAN<span class="text-rajasthan-500">READS</span></span>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed">Dedicated to the preservation and digital accessibility of Rajasthan's immense literary and cultural wealth for researchers and enthusiasts worldwide.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-rajasthan-500 mb-6">Archive Explorer</h4>
                    <ul class="space-y-4">
                        <li><a href="<?= BASE_URL ?>/books" class="text-gray-400 hover:text-white transition-colors text-sm">Library Repository</a></li>
                        <li><a href="<?= BASE_URL ?>/authors" class="text-gray-400 hover:text-white transition-colors text-sm">Literary Authors</a></li>
                        <li><a href="<?= BASE_URL ?>/categories" class="text-gray-400 hover:text-white transition-colors text-sm">Thematic Categories</a></li>
                    </ul>
                </div>

                <!-- Legal & Organization -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-rajasthan-500 mb-6">Organization & Legal</h4>
                    <ul class="space-y-4">
                        <li><a href="<?= BASE_URL ?>/about-us" class="text-gray-400 hover:text-white transition-colors text-sm">About RajasthanReads</a></li>
                        <li><a href="<?= BASE_URL ?>/privacy-policy" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a></li>
                        <li><a href="<?= BASE_URL ?>/terms-and-conditions" class="text-gray-400 hover:text-white transition-colors text-sm">Terms & Conditions</a></li>
                        <li><a href="<?= BASE_URL ?>/disclaimer" class="text-gray-400 hover:text-white transition-colors text-sm">Disclaimer</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-rajasthan-500 mb-6">Contact Archive</h4>
                    <p class="text-gray-400 text-sm mb-4 italic">For archival inquiries or contributions</p>
                    <a href="<?= BASE_URL ?>/contact-us" class="text-white font-bold hover:text-rajasthan-400 transition-colors block mb-2">Contact Us</a>
                    <a href="mailto:<?= htmlspecialchars($site_settings['contact_email'] ?? 'archive@rajasthanreads.org') ?>" class="text-gray-400 hover:text-rajasthan-400 transition-colors"><?= htmlspecialchars($site_settings['contact_email'] ?? 'archive@rajasthanreads.org') ?></a>
                </div>
            </div>

            <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">&copy; <?= date('Y') ?> RajasthanReads Digital Archive. All Heritage Reserved.</p>
                <div class="flex gap-4">
                    <a href="<?= htmlspecialchars($site_settings['social_twitter'] ?? '#') ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-gray-400 hover:bg-rajasthan-600 hover:text-white transition-all"><i class="fab fa-twitter text-xs"></i></a>
                    <a href="<?= htmlspecialchars($site_settings['social_facebook'] ?? '#') ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-gray-400 hover:bg-rajasthan-600 hover:text-white transition-all"><i class="fab fa-facebook-f text-xs"></i></a>
                    <a href="<?= htmlspecialchars($site_settings['social_instagram'] ?? '#') ?>" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 text-gray-400 hover:bg-rajasthan-600 hover:text-white transition-all"><i class="fab fa-instagram text-xs"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- PWA Service Worker -->
    <script src="<?= $this->asset('js/sw-register.js') ?>"></script>

    <script>
        // Mobile Menu Toggle Logic
        const mobileMenuBtn = document.getElementById('mobile-menu-button');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuContent = document.getElementById('mobile-menu-content');
        const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');

        function openMobileMenu() {
            mobileMenu.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                mobileMenuContent.classList.remove('translate-x-full');
            }, 10);
        }

        function closeMobileMenu() {
            mobileMenuContent.classList.add('translate-x-full');
            document.body.style.overflow = '';
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openMobileMenu);
        }

        if (mobileMenuClose) {
            mobileMenuClose.addEventListener('click', closeMobileMenu);
        }

        if (mobileMenuBackdrop) {
            mobileMenuBackdrop.addEventListener('click', closeMobileMenu);
        }

        // Close on Esc key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                closeMobileMenu();
            }
        });
    </script>
</body>
</html>
