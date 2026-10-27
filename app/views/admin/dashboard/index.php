<!-- Admin Header -->
<div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
    <div>
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-rajasthan-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-rajasthan-600/30">
                <i class="fas fa-tachometer-alt"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-rajasthan-400">Administration</span>
        </div>
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Dashboard</h1>
        <p class="text-gray-500 font-medium mt-1">Welcome back, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Admin') ?>. Here's your library overview.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= BASE_URL ?>/admin/books/create" class="px-6 py-3 bg-rajasthan-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-rajasthan-700 transition-all shadow-lg shadow-rajasthan-600/20 flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Book
        </a>
        <a href="<?= BASE_URL ?>/admin/authors/create" class="px-6 py-3 bg-white text-gray-600 border border-gray-100 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-gray-50 transition-all flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Add Author
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Books -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-rajasthan-50 rounded-xl flex items-center justify-center text-rajasthan-600 group-hover:scale-110 transition-transform">
                <i class="fas fa-book text-xl"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-300">Total</span>
        </div>
        <p class="text-3xl font-black text-gray-900"><?= (int)$stats['total_books'] ?></p>
        <p class="text-xs font-bold text-gray-400 mt-1">Books in Archive</p>
        <div class="mt-4 pt-4 border-t border-gray-50 flex items-center gap-4 text-[10px] font-bold">
            <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded"><?= (int)$stats['published_books'] ?> Published</span>
            <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded"><?= (int)$stats['draft_books'] ?> Draft</span>
        </div>
    </div>
    
    <!-- Authors -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                <i class="fas fa-feather-alt text-xl"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-300">Total</span>
        </div>
        <p class="text-3xl font-black text-gray-900"><?= (int)$stats['total_authors'] ?></p>
        <p class="text-xs font-bold text-gray-400 mt-1">Literary Authors</p>
        <div class="mt-4 pt-4 border-t border-gray-50">
            <a href="<?= BASE_URL ?>/admin/authors" class="text-[10px] font-bold text-blue-600 uppercase tracking-widest hover:text-blue-700 flex items-center gap-1">
                Manage <i class="fas fa-arrow-right text-[8px]"></i>
            </a>
        </div>
    </div>
    
    <!-- Categories -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                <i class="fas fa-layer-group text-xl"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-300">Total</span>
        </div>
        <p class="text-3xl font-black text-gray-900"><?= (int)$stats['total_categories'] ?></p>
        <p class="text-xs font-bold text-gray-400 mt-1">Book Categories</p>
        <div class="mt-4 pt-4 border-t border-gray-50">
            <a href="<?= BASE_URL ?>/admin/categories" class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest hover:text-emerald-700 flex items-center gap-1">
                Manage <i class="fas fa-arrow-right text-[8px]"></i>
            </a>
        </div>
    </div>
    
    <!-- Users -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600 group-hover:scale-110 transition-transform">
                <i class="fas fa-shield-alt text-xl"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-300">Total</span>
        </div>
        <p class="text-3xl font-black text-gray-900"><?= (int)$stats['total_users'] ?></p>
        <p class="text-xs font-bold text-gray-400 mt-1">Admin Users</p>
        <div class="mt-4 pt-4 border-t border-gray-50">
            <span class="text-[10px] font-bold text-violet-600 bg-violet-50 px-2 py-0.5 rounded"><?= (int)$stats['admin_users'] ?> Administrators</span>
        </div>
    </div>
</div>

<!-- Quick Access Panel -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
    <a href="<?= BASE_URL ?>/admin/books" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:border-rajasthan-100 transition-all group text-center">
        <i class="fas fa-book-open text-rajasthan-400 text-2xl group-hover:scale-110 transition-transform block mb-2"></i>
        <span class="text-xs font-bold text-gray-600">Manage Books</span>
    </a>
    <a href="<?= BASE_URL ?>/admin/authors" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:border-blue-100 transition-all group text-center">
        <i class="fas fa-users text-blue-400 text-2xl group-hover:scale-110 transition-transform block mb-2"></i>
        <span class="text-xs font-bold text-gray-600">Manage Authors</span>
    </a>
    <a href="<?= BASE_URL ?>/admin/categories" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:border-emerald-100 transition-all group text-center">
        <i class="fas fa-tags text-emerald-400 text-2xl group-hover:scale-110 transition-transform block mb-2"></i>
        <span class="text-xs font-bold text-gray-600">Manage Categories</span>
    </a>
    <a href="<?= BASE_URL ?>/" class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-lg hover:border-amber-100 transition-all group text-center" target="_blank">
        <i class="fas fa-globe text-amber-400 text-2xl group-hover:scale-110 transition-transform block mb-2"></i>
        <span class="text-xs font-bold text-gray-600">View Public Site</span>
    </a>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Monthly Uploads</h3>
            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Last 12 Months</span>
        </div>
        <canvas id="monthlyChart" height="200"></canvas>
    </div>
    
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Category Distribution</h3>
            <span class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">All Time</span>
        </div>
        <canvas id="categoryChart" height="200"></canvas>
    </div>
</div>

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Books -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Recent Books</h3>
            <a href="<?= BASE_URL ?>/admin/books" class="text-[10px] font-bold text-rajasthan-600 uppercase tracking-widest hover:text-rajasthan-700 flex items-center gap-1">
                View All <i class="fas fa-arrow-right text-[8px]"></i>
            </a>
        </div>
        <div class="divide-y divide-gray-50">
            <?php foreach ($recentBooks as $book): ?>
            <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                <div class="w-10 h-14 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?= Image::url($book['cover_image'], 'thumbnail') ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <i class="fas fa-book text-xs"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate"><?= htmlspecialchars($book['title']) ?></p>
                    <p class="text-[10px] font-bold text-gray-400"><?= htmlspecialchars($book['author_name'] ?? 'No Author') ?></p>
                </div>
                <div class="flex items-center gap-2">
                    <?php
                    $statusColor = match($book['status']) {
                        'published' => 'bg-green-50 text-green-600',
                        'draft' => 'bg-amber-50 text-amber-600',
                        default => 'bg-gray-50 text-gray-600'
                    };
                    ?>
                    <span class="px-2 py-0.5 text-[9px] font-black uppercase tracking-widest rounded <?= $statusColor ?>"><?= ucfirst($book['status']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Popular Books -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50">
            <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Most Viewed</h3>
        </div>
        <div class="divide-y divide-gray-50">
            <?php foreach ($popularBooks as $i => $book): ?>
            <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition-colors">
                <span class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-xs font-black text-gray-400 flex-shrink-0">#<?= $i + 1 ?></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate"><?= htmlspecialchars($book['title']) ?></p>
                    <p class="text-[10px] font-bold text-gray-400"><?= htmlspecialchars($book['author_name'] ?? 'No Author') ?></p>
                </div>
                <div class="flex items-center gap-4 text-[10px] font-bold text-gray-400">
                    <span class="flex items-center gap-1"><i class="fas fa-eye text-rajasthan-400"></i> <?= number_format($book['view_count']) ?></span>
                    <span class="flex items-center gap-1"><i class="fas fa-download text-blue-400"></i> <?= number_format($book['download_count']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Monthly Uploads Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($monthlyData['labels']) ?>,
        datasets: [{
            label: 'Books Uploaded',
            data: <?= json_encode($monthlyData['data']) ?>,
            backgroundColor: 'rgba(234, 88, 12, 0.8)',
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                grid: { color: 'rgba(0,0,0,0.03)' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Category Distribution Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_column($categoryStats, 'name')) ?>,
        datasets: [{
            data: <?= json_encode(array_column($categoryStats, 'count')) ?>,
            backgroundColor: [
                '#ea580c', '#f97316', '#fb923c', '#fbbf24',
                '#3b82f6', '#8b5cf6', '#10b981', '#ef4444'
            ],
            borderWidth: 0,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'rectRounded',
                    padding: 16,
                    font: { size: 11, weight: 'bold' }
                }
            }
        }
    }
});
</script>
