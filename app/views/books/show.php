<!-- Hero Section with blurred background -->
<div class="relative mb-12 rounded-3xl overflow-hidden shadow-2xl">
    <!-- Blurred Background Cover -->
    <div class="absolute inset-0 z-0">
        <?php if (!empty($book['cover_image'])): ?>
            <img src="<?= Image::url($book['cover_image'], 'large') ?>" class="w-full h-full object-cover blur-2xl scale-110 opacity-30">
        <?php else: ?>
            <div class="w-full h-full bg-rajasthan-100"></div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
    </div>

    <!-- Main Content Grid -->
    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 p-8 lg:p-12">
        <!-- Left: Book Cover -->
        <div class="lg:col-span-4 flex flex-col items-center lg:items-start">
            <div class="group relative w-64 lg:w-full max-w-sm aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl ring-1 ring-black/5 transition-transform duration-500 hover:scale-[1.02]">
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="<?= Image::url($book['cover_image'], 'large') ?>" 
                         alt="<?= htmlspecialchars($book['title']) ?>" 
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full bg-rajasthan-50 flex items-center justify-center">
                        <i class="fas fa-book-open text-rajasthan-200 text-7xl"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Quick stats overlay on hover -->
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-6 text-white backdrop-blur-sm">
                    <div class="text-center">
                        <i class="fas fa-eye text-2xl mb-1"></i>
                        <p class="text-xs font-bold uppercase tracking-widest"><?= number_format($book['view_count']) ?></p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-download text-2xl mb-1"></i>
                        <p class="text-xs font-bold uppercase tracking-widest"><?= number_format($book['download_count']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 w-full max-w-sm space-y-4">
                <?php if (!empty($book['file_path'])): ?>
                    <a href="<?= BASE_URL ?>/reader/<?= htmlspecialchars($book['slug']) ?>" 
                       class="flex items-center justify-center gap-3 w-full bg-rajasthan-600 text-white py-4 rounded-xl hover:bg-rajasthan-700 transition-all shadow-lg shadow-rajasthan-600/30 font-bold text-lg group">
                        <i class="fas fa-book-reader group-hover:scale-110 transition-transform"></i>
                        Read Online
                    </a>
                    <a href="<?= BASE_URL ?>/<?= htmlspecialchars(ltrim($book['file_path'], '/')) ?>" 
                       target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-3 w-full bg-white text-gray-700 border border-gray-200 py-3 rounded-xl hover:bg-gray-50 transition-all font-semibold group">
                        <i class="fas fa-cloud-download-alt text-rajasthan-500 group-hover:translate-y-0.5 transition-transform"></i>
                        Download PDF
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Book Details -->
        <div class="lg:col-span-8 space-y-8">
            <div class="space-y-4 text-center lg:text-left">
                <!-- Badges -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-2">
                    <?php if (!empty($book['category_name'])): ?>
                        <a href="<?= BASE_URL ?>/categories/<?= htmlspecialchars($book['category_slug']) ?>" 
                           class="px-4 py-1.5 bg-rajasthan-50 text-rajasthan-700 rounded-full text-xs font-bold uppercase tracking-wider border border-rajasthan-100 hover:bg-rajasthan-100 transition-colors">
                            <?= htmlspecialchars($book['category_name']) ?>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($book['district_name'])): ?>
                        <span class="px-4 py-1.5 bg-blue-50 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wider border border-blue-100">
                            <i class="fas fa-map-marker-alt mr-1.5"></i><?= htmlspecialchars($book['district_name']) ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h1 class="text-4xl lg:text-6xl font-black text-gray-900 tracking-tight leading-tight">
                    <?= htmlspecialchars($book['title']) ?>
                </h1>

                <?php if (!empty($book['author_name'])): ?>
                    <p class="text-xl text-gray-600">
                        by <a href="<?= BASE_URL ?>/authors/<?= htmlspecialchars($book['author_slug']) ?>" 
                              class="font-bold text-rajasthan-600 hover:underline"><?= htmlspecialchars($book['author_name']) ?></a>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Stats Bar -->
            <div class="flex flex-wrap items-center gap-8 py-6 border-y border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rajasthan-50 flex items-center justify-center text-rajasthan-600">
                        <i class="fas fa-language"></i>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Language</p>
                        <p class="text-sm font-bold text-gray-800 capitalize"><?= htmlspecialchars($book['language'] ?? 'Rajasthani') ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Pages</p>
                        <p class="text-sm font-bold text-gray-800"><?= (int)$book['pages'] ?: '150+' ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Published</p>
                        <p class="text-sm font-bold text-gray-800"><?= (int)$book['publication_year'] ?: 'N/A' ?></p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-align-left text-rajasthan-400 text-sm"></i>
                    Synopsis
                </h3>
                <div class="text-gray-600 leading-relaxed text-lg prose prose-rajasthan max-w-none">
                    <?= nl2br(htmlspecialchars($book['description'] ?: 'No description available for this work yet. Discover the rich literary tradition of Rajasthan through its classic texts.')) ?>
                </div>
            </div>

            <!-- Cultural Card -->
            <div class="bg-gradient-to-br from-rajasthan-600 to-rajasthan-800 rounded-2xl p-6 text-white shadow-xl">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-md">
                        <i class="fas fa-landmark text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-lg mb-1">Cultural Significance</h4>
                        <p class="text-white/80 text-sm leading-relaxed">
                            This work represents the authentic voice of <?= htmlspecialchars($book['district_name'] ?? 'Rajasthan') ?>. 
                            Our digital archives aim to preserve such literary treasures for future generations.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Social Share -->
            <div class="flex items-center gap-4 pt-4">
                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Share this treasure</span>
                <div class="flex gap-2">
                    <a href="https://twitter.com/intent/tweet?text=Reading <?= urlencode($book['title']) ?> on RajasthanReads&url=<?= urlencode(BASE_URL . '/books/' . $book['slug']) ?>" 
                       target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-sky-50 hover:text-sky-500 transition-all">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . '/books/' . $book['slug']) ?>" 
                       target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition-all">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://wa.me/?text=Check out this book on RajasthanReads: <?= urlencode($book['title'] . ' - ' . BASE_URL . '/books/' . $book['slug']) ?>" 
                       target="_blank" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-all">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related Books Section -->
<?php if (!empty($relatedBooks)): ?>
    <div class="pt-12 mb-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">More like this</h2>
            <div class="flex gap-2">
                <div class="w-10 h-1 rounded-full bg-rajasthan-500"></div>
                <div class="w-3 h-1 rounded-full bg-rajasthan-200"></div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($relatedBooks as $related): ?>
                <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden">
                    <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($related['slug']) ?>" class="block aspect-[2/3] overflow-hidden">
                        <?php if (!empty($related['cover_image'])): ?>
                            <img src="<?= Image::url($related['cover_image'], 'medium') ?>" 
                                 alt="<?= htmlspecialchars($related['title']) ?>" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full bg-rajasthan-50 flex items-center justify-center">
                                <i class="fas fa-book text-rajasthan-100 text-5xl"></i>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Overlay on hover -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                            <span class="text-white text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                                Read Now <i class="fas fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                    </a>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 line-clamp-2 mb-2 leading-tight group-hover:text-rajasthan-600 transition-colors">
                            <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($related['slug']) ?>">
                                <?= htmlspecialchars($related['title']) ?>
                            </a>
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 italic"><?= htmlspecialchars($related['author_name'] ?? 'Unknown') ?></span>
                            <span class="text-[10px] font-black text-rajasthan-500 uppercase tracking-widest">Free</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
