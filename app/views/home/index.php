<!-- Hero Section -->
<div class="relative rounded-[2rem] overflow-hidden mb-20 shadow-2xl">
    <div class="absolute inset-0 bg-gradient-to-r from-rajasthan-900 via-rajasthan-800/90 to-transparent z-10"></div>
    <img src="https://images.unsplash.com/photo-1590050752117-23aae297119a?q=80&w=2000&auto=format&fit=crop" 
         class="absolute inset-0 w-full h-full object-cover z-0 grayscale-[0.2]" alt="Rajasthan Heritage">
    
    <div class="relative z-20 px-8 py-24 lg:px-20 lg:py-32 max-w-3xl">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-rajasthan-500/20 backdrop-blur-md rounded-full border border-rajasthan-400/30 text-rajasthan-200 text-[10px] font-black uppercase tracking-[0.2em] mb-8">
            <span class="w-2 h-2 bg-rajasthan-400 rounded-full animate-pulse"></span>
            Preserving Digital Heritage
        </div>
        
        <h1 class="text-5xl lg:text-7xl font-black text-white leading-[1.1] mb-8 tracking-tighter">
            Discover the <span class="text-rajasthan-400">Soul</span> of <br>Rajasthan
        </h1>
        
        <p class="text-xl text-white/70 mb-10 leading-relaxed font-medium">
            Immerse yourself in thousands of rare manuscripts, folk literature, and cultural archives from the Land of Kings.
        </p>
        
        <!-- Search Bar -->
        <form action="<?= BASE_URL ?>/books" method="GET" class="relative group max-w-xl">
            <input type="text" name="q" placeholder="Search by book title, author or district..." 
                   class="w-full bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl px-8 py-5 text-white placeholder-white/40 focus:bg-white focus:text-gray-900 transition-all outline-none text-lg shadow-2xl">
            <button type="submit" class="absolute right-3 top-3 bottom-3 px-8 bg-rajasthan-600 text-white rounded-xl font-bold shadow-lg shadow-rajasthan-900/40 hover:bg-rajasthan-700 transition-all active:scale-95">
                Explore
            </button>
        </form>
        
        <div class="mt-12 flex items-center gap-8 text-white/50 text-xs font-bold uppercase tracking-widest">
             <div class="flex flex-col">
                <span class="text-white text-xl">5,000+</span>
                <span>Manuscripts</span>
             </div>
             <div class="w-px h-8 bg-white/10"></div>
             <div class="flex flex-col">
                <span class="text-white text-xl">33+</span>
                <span>Districts</span>
             </div>
             <div class="w-px h-8 bg-white/10"></div>
             <div class="flex flex-col">
                <span class="text-white text-xl">100%</span>
                <span>Digital Access</span>
             </div>
        </div>
    </div>
</div>

<!-- Featured Categories -->
<div class="mb-24">
    <div class="flex items-center justify-between mb-12">
        <div class="space-y-2">
            <h2 class="text-4xl font-black text-gray-900 tracking-tight">Cultural Arcs</h2>
            <p class="text-gray-500 font-medium italic">Hand-picked collections representing our diverse heritage</p>
        </div>
        <a href="<?= BASE_URL ?>/categories" class="px-6 py-3 bg-white border border-gray-100 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all flex items-center gap-2">
            View All <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Category Card 1 -->
        <a href="<?= BASE_URL ?>/categories/folk-stories" class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-xl">
            <img src="https://images.unsplash.com/photo-1595180017128-406180598a87?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
            <div class="absolute bottom-8 left-8 right-8 text-white">
                <span class="px-3 py-1 bg-rajasthan-500 text-[9px] font-black uppercase tracking-widest rounded mb-3 inline-block">Popular</span>
                <h3 class="text-2xl font-black mb-2">Folk Stories</h3>
                <p class="text-white/60 text-sm font-medium">Legends passed through generations of oral history.</p>
            </div>
        </a>

        <!-- Category Card 2 -->
        <a href="<?= BASE_URL ?>/categories/manuscripts" class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-xl mt-8">
            <img src="https://images.unsplash.com/photo-1544640805-35c0fa947728?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-orange-900/90 via-transparent to-transparent"></div>
            <div class="absolute bottom-8 left-8 right-8 text-white">
                <span class="px-3 py-1 bg-orange-500 text-[9px] font-black uppercase tracking-widest rounded mb-3 inline-block">Rare</span>
                <h3 class="text-2xl font-black mb-2">Manuscripts</h3>
                <p class="text-white/60 text-sm font-medium">Original texts and ancient scrolls preserved digitally.</p>
            </div>
        </a>

        <!-- Category Card 3 -->
        <a href="<?= BASE_URL ?>/categories/poetry" class="group relative aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-xl">
            <img src="https://images.unsplash.com/photo-1471107340929-a87cd0f5b5f3?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-transparent to-transparent"></div>
            <div class="absolute bottom-8 left-8 right-8 text-white">
                <span class="px-3 py-1 bg-blue-500 text-[9px] font-black uppercase tracking-widest rounded mb-3 inline-block">Literary</span>
                <h3 class="text-2xl font-black mb-2">Modern Poetry</h3>
                <p class="text-white/60 text-sm font-medium">The evolving voice of Rajasthan's contemporary poets.</p>
            </div>
        </a>
    </div>
</div>

<!-- Recently Added Section -->
<div class="mb-24">
    <div class="flex items-center justify-between mb-12">
        <div class="space-y-2">
            <h2 class="text-4xl font-black text-gray-900 tracking-tight">New Arrivals</h2>
            <p class="text-gray-500 font-medium italic">Latest additions to our ever-growing collection</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php foreach ($recentBooks as $book): ?>
            <div class="group bg-white rounded-3xl p-4 shadow-sm hover:shadow-2xl transition-all duration-500 border border-transparent hover:border-gray-100 flex flex-col">
                <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($book['slug']) ?>" class="block aspect-[3/4] overflow-hidden rounded-2xl relative mb-6 shadow-md">
                    <?php if (!empty($book['cover_image'])): ?>
                        <img src="<?= Image::url($book['cover_image'], 'medium') ?>" alt="<?= htmlspecialchars($book['title']) ?>" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full bg-rajasthan-50 flex items-center justify-center text-rajasthan-200">
                            <i class="fas fa-book-open text-5xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                        <span class="px-6 py-2 bg-white text-gray-900 rounded-full text-xs font-black uppercase tracking-widest scale-75 group-hover:scale-100 transition-transform">Read Details</span>
                    </div>
                </a>
                
                <div class="px-2 pb-2">
                    <span class="text-[10px] font-black text-rajasthan-600 uppercase tracking-widest mb-1 block"><?= htmlspecialchars($book['category_name'] ?? 'General') ?></span>
                    <h3 class="font-bold text-gray-900 line-clamp-1 mb-1 group-hover:text-rajasthan-600 transition-colors"><?= htmlspecialchars($book['title']) ?></h3>
                    <p class="text-[10px] text-gray-400 font-bold italic"><?= htmlspecialchars($book['author_name'] ?? 'Various Authors') ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="mt-16 text-center">
        <a href="<?= BASE_URL ?>/books" class="inline-flex items-center gap-3 px-10 py-5 bg-rajasthan-600 text-white rounded-2xl font-black shadow-2xl shadow-rajasthan-600/30 hover:bg-rajasthan-700 transition-all hover:translate-y-[-2px] active:scale-95 group">
            Discover Full Library
            <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
        </a>
    </div>
</div>

<!-- Newsletter / CTA -->
<div class="bg-gray-900 rounded-[3rem] p-12 lg:p-20 relative overflow-hidden text-center">
    <div class="absolute top-0 right-0 w-64 h-64 bg-rajasthan-600/10 rounded-full blur-3xl -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-rajasthan-600/10 rounded-full blur-3xl -ml-32 -mb-32"></div>
    
    <div class="relative z-10 max-w-2xl mx-auto">
        <h2 class="text-4xl lg:text-5xl font-black text-white mb-6">Stay Connected</h2>
        <p class="text-white/60 text-lg mb-10 font-medium italic">Join our community to receive updates on rare manuscript digitalization and new literary arrivals.</p>
        
        <form class="flex flex-col sm:flex-row gap-4">
            <input type="email" placeholder="Your email address" class="flex-1 bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none focus:bg-white/10 transition-all text-lg">
            <button type="submit" class="px-10 py-5 bg-white text-gray-900 rounded-2xl font-black hover:bg-gray-100 transition-all active:scale-95 whitespace-nowrap">Join Archive</button>
        </form>
    </div>
</div>
