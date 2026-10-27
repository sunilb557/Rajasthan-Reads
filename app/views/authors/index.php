<!-- Header -->
<div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div class="space-y-2">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-rajasthan-50 rounded-xl flex items-center justify-center text-rajasthan-600">
                <i class="fas fa-feather-alt text-lg"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-rajasthan-400">Literary Contributors</span>
        </div>
        <h1 class="text-4xl font-black text-gray-900 tracking-tight">Authors</h1>
        <p class="text-gray-500 font-medium max-w-md leading-relaxed">Celebrated writers and scholars who have shaped the cultural narrative of Rajasthan.</p>
    </div>
    <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
        <span class="px-4 py-2 bg-gray-50 rounded-xl border border-gray-100"><?= count($authors) ?> Authors</span>
    </div>
</div>

<?php if (empty($authors)): ?>
    <div class="bg-white rounded-3xl p-20 text-center border border-gray-100 shadow-sm">
        <div class="w-20 h-20 bg-rajasthan-50 rounded-full flex items-center justify-center text-rajasthan-200 mx-auto mb-6">
            <i class="fas fa-feather-alt text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No authors yet</h2>
        <p class="text-gray-500">Authors will appear here once they're added to the archive.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($authors as $author): ?>
            <a href="<?= BASE_URL ?>/authors/<?= htmlspecialchars($author['slug']) ?>" 
               class="group bg-white rounded-2xl p-6 shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-rajasthan-100 flex flex-col">
                
                <!-- Avatar -->
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-rajasthan-500 to-rajasthan-700 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shadow-rajasthan-600/20 group-hover:scale-110 transition-transform">
                        <?= strtoupper(substr($author['name'], 0, 1)) ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-900 truncate group-hover:text-rajasthan-600 transition-colors"><?= htmlspecialchars($author['name']) ?></h3>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Literary Author</p>
                    </div>
                </div>
                
                <?php if (!empty($author['bio'])): ?>
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-3 mb-5 flex-1"><?= htmlspecialchars(substr($author['bio'], 0, 150)) ?>...</p>
                <?php else: ?>
                    <p class="text-sm text-gray-400 italic mb-5 flex-1">Biography not yet documented.</p>
                <?php endif; ?>
                
                <!-- Footer -->
                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-rajasthan-600">
                        <i class="fas fa-book-open text-xs"></i>
                        <span class="text-xs font-bold"><?= (int)$author['book_count'] ?> <?= (int)$author['book_count'] === 1 ? 'Book' : 'Books' ?></span>
                    </div>
                    <span class="text-xs font-bold text-gray-400 group-hover:text-rajasthan-600 group-hover:translate-x-1 transition-all flex items-center gap-1">
                        View <i class="fas fa-arrow-right text-[10px]"></i>
                    </span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
