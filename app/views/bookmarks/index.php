<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">My Bookmarks</h1>
    
    <?php if (empty($bookmarks)): ?>
        <div class="bg-white rounded-lg shadow p-8 text-center">
            <i class="fas fa-bookmark text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500 mb-4">You haven't saved any bookmarks yet.</p>
            <a href="<?= BASE_URL ?>/books" class="inline-flex items-center gap-2 text-rajasthan-600 hover:text-rajasthan-700">
                <i class="fas fa-book"></i>
                <span>Browse Books</span>
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($bookmarks as $bookmark): ?>
                <div class="bg-white rounded-lg shadow p-4 flex gap-4">
                    <!-- Cover -->
                    <div class="w-24 flex-shrink-0">
                        <?php if (!empty($bookmark['cover_image'])): ?>
                            <img src="<?= Image::url($bookmark['cover_image'], 'small') ?>" 
                                 alt="" class="w-full h-32 object-cover rounded">
                        <?php else: ?>
                            <div class="w-full h-32 bg-rajasthan-100 rounded flex items-center justify-center">
                                <i class="fas fa-book text-rajasthan-300 text-2xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">
                            <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($bookmark['book_slug']) ?>" 
                               class="text-gray-900 hover:text-rajasthan-600">
                                <?= htmlspecialchars($bookmark['book_title']) ?>
                            </a>
                        </h3>
                        <?php if (!empty($bookmark['author_name'])): ?>
                            <p class="text-gray-500 text-sm"><?= htmlspecialchars($bookmark['author_name']) ?></p>
                        <?php endif; ?>
                        
                        <div class="mt-3 flex items-center gap-4">
                            <span class="inline-flex items-center gap-1 text-rajasthan-600 bg-rajasthan-50 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-page"></i>
                                Page <?= (int)$bookmark['page_number'] ?>
                            </span>
                            
                            <a href="<?= BASE_URL ?>/reader/<?= htmlspecialchars($bookmark['book_slug']) ?>?page=<?= (int)$bookmark['page_number'] ?>" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-book-open mr-1"></i> Continue Reading
                            </a>
                        </div>
                        
                        <?php if (!empty($bookmark['note'])): ?>
                            <p class="mt-2 text-gray-600 text-sm italic">
                                "<?= htmlspecialchars($bookmark['note']) ?>"
                            </p>
                        <?php endif; ?>
                        
                        <p class="mt-2 text-gray-400 text-xs">
                            Saved <?= date('M d, Y', strtotime($bookmark['created_at'])) ?>
                        </p>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex flex-col gap-2">
                        <form method="POST" action="<?= BASE_URL ?>/bookmarks/<?= (int)$bookmark['id'] ?>/delete" 
                              class="inline" onsubmit="return confirm('Delete this bookmark?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700 p-2" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
