<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Search Inside Books</h1>
    <p class="text-gray-600 mb-6">Find content within PDF documents</p>
    
    <form action="<?= BASE_URL ?>/search/ocr" method="GET" class="mb-8">
        <div class="flex gap-2">
            <input 
                type="text" 
                name="q" 
                value="<?= htmlspecialchars($query) ?>" 
                placeholder="Search for text inside books..."
                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-rajasthan-500 focus:border-transparent"
                minlength="3"
            >
            <button type="submit" class="px-6 py-3 bg-rajasthan-600 text-white rounded-lg hover:bg-rajasthan-700">
                <i class="fas fa-search mr-2"></i> Search
            </button>
        </div>
    </form>
    
    <?php if ($query && !empty($results)): ?>
        <div class="mb-4 text-gray-600">
            Found <?= (int)$total ?> matches for "<?= htmlspecialchars($query) ?>"
        </div>
        
        <div class="space-y-4">
            <?php foreach ($results as $result): ?>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex gap-4">
                        <!-- Book Cover -->
                        <div class="w-16 flex-shrink-0">
                            <?php if (!empty($result['book']['cover_image'])): ?>
                                <img src="<?= Image::url($result['book']['cover_image'], 'thumbnail') ?>" 
                                     alt="" class="w-full h-20 object-cover rounded">
                            <?php else: ?>
                                <div class="w-full h-20 bg-rajasthan-100 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-rajasthan-300"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Result Info -->
                        <div class="flex-1">
                            <h3 class="font-semibold">
                                <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($result['book']['slug']) ?>" 
                                   class="text-rajasthan-600 hover:text-rajasthan-800">
                                    <?= htmlspecialchars($result['book']['title']) ?>
                                </a>
                            </h3>
                            
                            <div class="flex items-center gap-4 mt-2 text-sm">
                                <span class="text-gray-500">
                                    Page <?= (int)$result['page_number'] ?>
                                </span>
                                <a href="<?= BASE_URL ?>/reader/<?= htmlspecialchars($result['book']['slug']) ?>?page=<?= (int)$result['page_number'] ?>" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-book-open mr-1"></i> Open in Reader
                                </a>
                            </div>
                            
                            <div class="mt-3 p-3 bg-gray-50 rounded text-sm text-gray-700">
                                <?= $result['excerpt'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php elseif ($query): ?>
        <div class="text-center py-12">
            <i class="fas fa-search text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">No matches found for "<?= htmlspecialchars($query) ?>"</p>
            <p class="text-gray-400 text-sm mt-2">
                Try different keywords or check if the book has been indexed.
            </p>
        </div>
    <?php endif; ?>
</div>
