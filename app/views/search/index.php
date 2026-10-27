<!-- Search Header -->
<div class="mb-12">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-rajasthan-50 rounded-xl flex items-center justify-center text-rajasthan-600">
            <i class="fas fa-search text-lg"></i>
        </div>
        <span class="text-[10px] font-black uppercase tracking-widest text-rajasthan-400">Advanced Search</span>
    </div>
    <h1 class="text-4xl font-black text-gray-900 tracking-tight">Search the Archive</h1>
    <p class="text-gray-500 font-medium max-w-md leading-relaxed mt-2">Find books by title, author, category, region, or language across our entire collection.</p>
</div>

<!-- Search Form -->
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mb-12">
    <form method="GET" action="<?= BASE_URL ?>/search" class="space-y-6">
        <!-- Main Search Input -->
        <div class="relative">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-300">
                <i class="fas fa-search text-lg"></i>
            </span>
            <input type="text" name="q" id="search-input"
                   value="<?= htmlspecialchars($query) ?>" 
                   placeholder="Search books, authors, topics..."
                   autocomplete="off"
                   class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 pl-14 text-gray-800 font-medium text-lg focus:ring-2 focus:ring-rajasthan-500 focus:border-transparent outline-none transition-all placeholder-gray-300">
        </div>

        <!-- Filter Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Category</label>
                <select name="category" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-rajasthan-500 outline-none transition-all">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= $selectedCategory == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Author</label>
                <select name="author" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-rajasthan-500 outline-none transition-all">
                    <option value="">All Authors</option>
                    <?php foreach ($authors as $a): ?>
                        <option value="<?= (int)$a['id'] ?>" <?= $selectedAuthor == $a['id'] ? 'selected' : '' ?>><?= htmlspecialchars($a['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">District</label>
                <select name="district" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-rajasthan-500 outline-none transition-all">
                    <option value="">All Districts</option>
                    <?php foreach ($districts as $d): ?>
                        <option value="<?= (int)$d['id'] ?>" <?= $selectedDistrict == $d['id'] ? 'selected' : '' ?>><?= htmlspecialchars($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Language</label>
                <select name="language" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-rajasthan-500 outline-none transition-all">
                    <option value="">All Languages</option>
                    <option value="hindi" <?= $selectedLanguage === 'hindi' ? 'selected' : '' ?>>Hindi</option>
                    <option value="english" <?= $selectedLanguage === 'english' ? 'selected' : '' ?>>English</option>
                    <option value="rajasthani" <?= $selectedLanguage === 'rajasthani' ? 'selected' : '' ?>>Rajasthani</option>
                </select>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-8 py-3 bg-rajasthan-600 text-white rounded-xl font-bold text-sm uppercase tracking-widest hover:bg-rajasthan-700 transition-all shadow-lg shadow-rajasthan-600/20 active:scale-[0.98]">
                <i class="fas fa-search mr-2"></i> Search
            </button>
            <a href="<?= BASE_URL ?>/search" class="px-6 py-3 bg-gray-50 text-gray-600 border border-gray-100 rounded-xl font-bold text-sm uppercase tracking-widest hover:bg-gray-100 transition-all">
                Clear Filters
            </a>
        </div>
    </form>
</div>

<!-- Results Section -->
<?php if ($query !== '' || $selectedCategory || $selectedAuthor || $selectedDistrict || $selectedLanguage): ?>
    <!-- Results Count -->
    <div class="mb-8 flex items-center gap-4">
        <?php if ($total > 0): ?>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span class="text-sm font-bold text-gray-600">
                    Found <span class="text-rajasthan-600"><?= (int)$total ?></span> result<?= $total === 1 ? '' : 's' ?>
                    <?php if ($query): ?> for "<span class="text-gray-900"><?= htmlspecialchars($query) ?></span>"<?php endif; ?>
                </span>
            </div>
        <?php else: ?>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                <span class="text-sm font-bold text-gray-500">
                    No results found
                    <?php if ($query): ?> for "<?= htmlspecialchars($query) ?>"<?php endif; ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($results)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($results as $book): ?>
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
                        
                        <div class="absolute top-3 left-3 flex flex-col gap-1.5 opacity-0 group-hover:opacity-100 transition-all">
                            <?php if (!empty($book['category_name'])): ?>
                                <span class="px-3 py-1 bg-white/90 backdrop-blur text-rajasthan-600 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                                    <?= htmlspecialchars($book['category_name']) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-5">
                            <div class="flex gap-2 w-full">
                                <span class="flex-1 py-2 bg-rajasthan-600 text-white rounded-lg text-xs font-bold text-center shadow-lg">View Details</span>
                                <?php if (!empty($book['file_path'])): ?>
                                    <span class="py-2 px-3 bg-white/20 text-white rounded-lg text-xs font-bold backdrop-blur">
                                        <i class="fas fa-book-reader"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-gray-900 line-clamp-2 mb-2 leading-tight group-hover:text-rajasthan-600 transition-colors">
                            <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($book['slug']) ?>">
                                <?= $this->highlight(htmlspecialchars($book['title']), $query) ?>
                            </a>
                        </h3>
                        
                        <?php if (!empty($book['author_name'])): ?>
                            <p class="text-xs text-gray-500 font-bold flex items-center gap-2 mb-3">
                                <span class="w-4 h-px bg-rajasthan-200"></span>
                                <a href="<?= BASE_URL ?>/authors/<?= htmlspecialchars($book['author_slug']) ?>" class="hover:text-rajasthan-600 transition-colors italic">
                                    <?= $this->highlight(htmlspecialchars($book['author_name']), $query) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        
                        <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                <?= htmlspecialchars($book['district_name'] ?? 'Rajasthan') ?>
                            </span>
                            <span class="text-[10px] font-black text-green-600 uppercase tracking-widest bg-green-50 px-2 py-0.5 rounded">Free</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="mt-16 flex justify-center">
                <nav class="inline-flex items-center bg-white rounded-2xl shadow-sm border border-gray-100 p-1.5 gap-1">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php
                        $url = BASE_URL . '/search?' . http_build_query(array_merge($_GET, ['page' => $i]));
                        ?>
                        <a href="<?= $url ?>" 
                           class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-black transition-all <?= $i == $page ? 'bg-rajasthan-600 text-white shadow-lg shadow-rajasthan-600/30 scale-110' : 'text-gray-500 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>

<script>
// Simple autocomplete
(function() {
    const input = document.getElementById('search-input');
    if (!input) return;
    
    let timeout;
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        if (this.value.length < 2) return;
        
        timeout = setTimeout(() => {
            fetch('<?= BASE_URL ?>/search/autocomplete?q=' + encodeURIComponent(this.value))
                .then(r => r.json())
                .then(data => {
                    console.log('Suggestions:', data.suggestions);
                });
        }, 300);
    });
})();
</script>
