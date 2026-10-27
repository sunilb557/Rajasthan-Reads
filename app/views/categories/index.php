<!-- Header -->
<div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
    <div class="space-y-2">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-rajasthan-50 rounded-xl flex items-center justify-center text-rajasthan-600">
                <i class="fas fa-layer-group text-lg"></i>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-rajasthan-400">Thematic Collections</span>
        </div>
        <h1 class="text-4xl font-black text-gray-900 tracking-tight">Categories</h1>
        <p class="text-gray-500 font-medium max-w-md leading-relaxed">Browse our curated literary collections spanning centuries of Rajasthani culture and heritage.</p>
    </div>
    <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-widest text-gray-400">
        <span class="px-4 py-2 bg-gray-50 rounded-xl border border-gray-100"><?= count($categories) ?> Collections</span>
    </div>
</div>

<?php if (empty($categories)): ?>
    <div class="bg-white rounded-3xl p-20 text-center border border-gray-100 shadow-sm">
        <div class="w-20 h-20 bg-rajasthan-50 rounded-full flex items-center justify-center text-rajasthan-200 mx-auto mb-6">
            <i class="fas fa-layer-group text-3xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No categories yet</h2>
        <p class="text-gray-500">Categories will appear here once they're created.</p>
    </div>
<?php else: ?>
    <?php 
    $colors = [
        ['from-rajasthan-500', 'to-rajasthan-700', 'bg-rajasthan-50', 'text-rajasthan-600', 'shadow-rajasthan-600/20'],
        ['from-blue-500', 'to-blue-700', 'bg-blue-50', 'text-blue-600', 'shadow-blue-600/20'],
        ['from-emerald-500', 'to-emerald-700', 'bg-emerald-50', 'text-emerald-600', 'shadow-emerald-600/20'],
        ['from-violet-500', 'to-violet-700', 'bg-violet-50', 'text-violet-600', 'shadow-violet-600/20'],
        ['from-amber-500', 'to-amber-700', 'bg-amber-50', 'text-amber-600', 'shadow-amber-600/20'],
        ['from-rose-500', 'to-rose-700', 'bg-rose-50', 'text-rose-600', 'shadow-rose-600/20'],
    ];
    $icons = ['fa-book-open', 'fa-scroll', 'fa-feather-alt', 'fa-pen-nib', 'fa-landmark', 'fa-theater-masks'];
    ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($categories as $i => $category): ?>
            <?php $c = $colors[$i % count($colors)]; $icon = $icons[$i % count($icons)]; ?>
            <a href="<?= BASE_URL ?>/categories/<?= htmlspecialchars($category['slug']) ?>" 
               class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-gray-200 overflow-hidden flex flex-col">
                
                <!-- Color Banner -->
                <div class="h-28 bg-gradient-to-br <?= $c[0] ?> <?= $c[1] ?> relative overflow-hidden flex items-center justify-center">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute -top-6 -right-6 w-24 h-24 border-2 border-white rounded-full"></div>
                        <div class="absolute -bottom-4 -left-4 w-16 h-16 border-2 border-white rounded-full"></div>
                    </div>
                    <i class="fas <?= $icon ?> text-white/30 text-5xl group-hover:scale-125 transition-transform duration-500"></i>
                </div>
                
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-black text-gray-900 mb-2 group-hover:text-rajasthan-600 transition-colors"><?= htmlspecialchars($category['name']) ?></h3>
                    
                    <?php if (!empty($category['description'])): ?>
                        <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-5 flex-1"><?= htmlspecialchars(substr($category['description'], 0, 120)) ?></p>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 italic mb-5 flex-1">Explore this collection of literary treasures.</p>
                    <?php endif; ?>
                    
                    <!-- Footer -->
                    <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 <?= $c[2] ?> rounded-lg flex items-center justify-center <?= $c[3] ?>">
                                <i class="fas fa-book text-[10px]"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-600"><?= (int)$category['book_count'] ?> <?= (int)$category['book_count'] === 1 ? 'Book' : 'Books' ?></span>
                        </div>
                        <span class="text-xs font-bold text-gray-400 group-hover:text-rajasthan-600 group-hover:translate-x-1 transition-all flex items-center gap-1">
                            Explore <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
