<!-- Category Header -->
<div class="relative mb-12">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Banner -->
        <div class="h-36 bg-gradient-to-br from-rajasthan-600 via-rajasthan-700 to-rajasthan-900 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-4 right-12 w-40 h-40 border-2 border-white rounded-full"></div>
                <div class="absolute -bottom-8 left-8 w-32 h-32 border-2 border-white rounded-full"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-layer-group text-white/10 text-8xl"></i>
            </div>
        </div>
        
        <!-- Category Info -->
        <div class="px-8 pb-8 relative">
            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-rajasthan-600 shadow-xl border-4 border-white -mt-8 relative z-10">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
            
            <div class="mt-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-3xl lg:text-4xl font-black text-gray-900 tracking-tight"><?= htmlspecialchars($category['name']) ?></h1>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-[10px] font-black uppercase tracking-widest text-rajasthan-600 bg-rajasthan-50 px-3 py-1 rounded-full">Category Collection</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400"><?= count($books) ?> Books</span>
                    </div>
                </div>
                
                <a href="<?= BASE_URL ?>/categories" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition-all">
                    <i class="fas fa-arrow-left text-xs"></i> All Categories
                </a>
            </div>
            
            <?php if (!empty($category['description'])): ?>
                <div class="mt-6 text-gray-600 leading-relaxed text-lg max-w-3xl">
                    <?= nl2br(htmlspecialchars($category['description'])) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Books in Category -->
<div class="mb-12">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Books in this Collection</h2>
        <div class="flex gap-2">
            <div class="w-10 h-1 rounded-full bg-rajasthan-500"></div>
            <div class="w-3 h-1 rounded-full bg-rajasthan-200"></div>
        </div>
    </div>

    <?php if (empty($books)): ?>
        <div class="bg-white rounded-3xl p-16 text-center border border-gray-100 shadow-sm">
            <div class="w-16 h-16 bg-rajasthan-50 rounded-full flex items-center justify-center text-rajasthan-200 mx-auto mb-4">
                <i class="fas fa-book text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No books found</h3>
            <p class="text-gray-500">No books have been catalogued in this category yet.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($books as $book): ?>
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 overflow-hidden flex flex-col">
                    <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($book['slug']) ?>" class="block aspect-[2/3] overflow-hidden bg-gray-50 relative">
                        <?php if (!empty($book['cover_image'])): ?>
                            <img src="<?= Image::url($book['cover_image'], 'medium') ?>" 
                                 alt="<?= htmlspecialchars($book['title']) ?>" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        <?php else: ?>
                            <div class="w-full h-full flex flex-col items-center justify-center text-rajasthan-100">
                                <i class="fas fa-book text-6xl mb-3"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">Digital Archive</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-5">
                            <span class="text-white text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                                Read Now <i class="fas fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                    </a>
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-gray-900 line-clamp-2 mb-2 leading-tight group-hover:text-rajasthan-600 transition-colors">
                            <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($book['slug']) ?>"><?= htmlspecialchars($book['title']) ?></a>
                        </h3>
                        <?php if (!empty($book['author_name'])): ?>
                            <p class="text-xs text-gray-500 font-bold flex items-center gap-2 mb-3">
                                <span class="w-4 h-px bg-rajasthan-200"></span>
                                <span class="italic"><?= htmlspecialchars($book['author_name']) ?></span>
                            </p>
                        <?php endif; ?>
                        <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest"><?= htmlspecialchars($book['language'] ?? 'Hindi') ?></span>
                            <span class="text-[10px] font-black text-green-600 uppercase tracking-widest bg-green-50 px-2 py-0.5 rounded">Free</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
