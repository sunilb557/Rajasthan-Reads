<div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div class="space-y-2">
        <h1 class="text-4xl font-black text-gray-900 tracking-tight">The Library</h1>
        <p class="text-gray-500 font-medium max-w-md leading-relaxed">Explore the vast digital repository of Rajasthani literature and historical archives.</p>
    </div>
    
    <!-- Filter Badges (Static for now, but looking good) -->
    <div class="flex flex-wrap gap-2">
        <span class="px-4 py-2 bg-rajasthan-600 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-lg shadow-rajasthan-600/20">All Books</span>
        <a href="<?= BASE_URL ?>/categories" class="px-4 py-2 bg-white text-gray-600 border border-gray-100 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-50 transition-colors">Categories</a>
        <a href="<?= BASE_URL ?>/authors" class="px-4 py-2 bg-white text-gray-600 border border-gray-100 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-50 transition-colors">Authors</a>
    </div>
</div>

<?php if (empty($books)): ?>
    <div class="bg-white rounded-3xl p-20 text-center border border-gray-100 shadow-sm">
        <div class="w-20 h-20 bg-rajasthan-50 rounded-full flex items-center justify-center text-rajasthan-200 mx-auto mb-6">
            <i class="fas fa-book-open text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No books found</h2>
        <p class="text-gray-500 mb-8">We couldn't find any books matching your criteria.</p>
        <a href="<?= BASE_URL ?>/books" class="px-8 py-3 bg-rajasthan-600 text-white rounded-xl font-bold shadow-lg shadow-rajasthan-600/20">Browse All</a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php foreach ($books as $book): ?>
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 overflow-hidden flex flex-col">
                <!-- Cover Image Container -->
                <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($book['slug']) ?>" class="block aspect-[2/3] overflow-hidden bg-gray-50 relative">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?= Image::url($book['cover_image'], 'medium') ?>" 
                             alt="<?= htmlspecialchars($book['title']) ?>" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                    <?php else: ?>
                        <div class="w-full h-full flex flex-col items-center justify-center text-rajasthan-100 group-hover:text-rajasthan-200 transition-colors">
                            <i class="fas fa-book text-7xl mb-4"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Digital Archive</span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Top Badge -->
                    <div class="absolute top-3 left-3 flex flex-col gap-1.5 opacity-0 group-hover:opacity-100 transform -translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                        <span class="px-3 py-1 bg-white/90 backdrop-blur text-rajasthan-600 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                            <?= htmlspecialchars($book['category_name'] ?? 'General') ?>
                        </span>
                    </div>

                    <!-- Overlay on hover -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                        <div class="space-y-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 w-full">
                             <div class="flex items-center gap-3 text-white/80 text-[10px] font-bold uppercase tracking-widest">
                                <span class="flex items-center gap-1.5"><i class="fas fa-eye text-rajasthan-400"></i> <?= number_format($book['view_count'] ?? 0) ?></span>
                                <span class="w-1 h-1 bg-white/20 rounded-full"></span>
                                <span><?= htmlspecialchars($book['language'] ?? 'Rajasthani') ?></span>
                             </div>
                             <button class="w-full py-2.5 bg-rajasthan-600 text-white rounded-lg text-xs font-bold shadow-lg shadow-rajasthan-900/50">Read Now</button>
                        </div>
                    </div>
                </a>
                
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-lg font-black text-gray-900 line-clamp-2 mb-2 leading-tight group-hover:text-rajasthan-600 transition-colors">
                        <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($book['slug']) ?>">
                            <?= htmlspecialchars($book['title']) ?>
                        </a>
                    </h3>
                    
                    <?php if (!empty($book['author_name'])): ?>
                        <p class="text-xs text-gray-500 font-bold flex items-center gap-2 mb-4">
                            <span class="w-4 h-px bg-rajasthan-200"></span>
                            <a href="<?= BASE_URL ?>/authors/<?= htmlspecialchars($book['author_slug']) ?>" class="hover:text-rajasthan-600 transition-colors italic"><?= htmlspecialchars($book['author_name']) ?></a>
                        </p>
                    <?php endif; ?>

                    <!-- Bottom metadata -->
                    <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-gray-400">
                             <i class="fas fa-map-marker-alt text-[10px]"></i>
                             <span class="text-[10px] font-bold uppercase tracking-tighter"><?= htmlspecialchars($book['district_name'] ?? 'Rajasthan') ?></span>
                        </div>
                        <span class="text-[10px] font-black text-green-600 uppercase tracking-widest bg-green-50 px-2 py-0.5 rounded">Free</span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Premium Pagination -->
    <?php if (!empty($totalPages) && $totalPages > 1): ?>
        <div class="mt-16 flex justify-center">
            <nav class="inline-flex items-center bg-white rounded-2xl shadow-sm border border-gray-100 p-1.5 gap-1">
                <?php if ($page > 1): ?>
                    <a href="<?= BASE_URL ?>/books?page=<?= $page - 1 ?>" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-500 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == 1 || $i == $totalPages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                        <a href="<?= BASE_URL ?>/books?page=<?= $i ?>" 
                           class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-black transition-all <?= $i == $page ? 'bg-rajasthan-600 text-white shadow-lg shadow-rajasthan-600/30 scale-110' : 'text-gray-500 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                        <span class="w-10 text-center text-gray-400">...</span>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="<?= BASE_URL ?>/books?page=<?= $page + 1 ?>" class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-500 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
<?php endif; ?>
