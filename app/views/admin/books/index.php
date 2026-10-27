<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manage Books</h1>
        <p class="text-gray-600">Total books available: <?= count($books) ?></p>
    </div>
    <a href="<?= BASE_URL ?>/admin/books/create" class="inline-flex items-center justify-center gap-2 bg-rajasthan-600 text-white px-5 py-2.5 rounded-lg hover:bg-rajasthan-700 transition-all shadow-sm font-medium">
        <i class="fas fa-plus"></i>
        <span>Add New Book</span>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if (empty($books)): ?>
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 rounded-full mb-4 text-gray-400">
                <i class="fas fa-book-open text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No books found</h3>
            <p class="mt-1 text-gray-500">Get started by adding your first literary work to the collection.</p>
            <div class="mt-6">
                <a href="<?= BASE_URL ?>/admin/books/create" class="text-rajasthan-600 font-medium hover:text-rajasthan-700">
                    Add a book <i class="fas fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Book Details</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Metadata</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($books as $book): ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-16 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 border border-gray-200">
                                        <?php if (!empty($book['cover_image'])): ?>
                                            <img src="<?= Image::url($book['cover_image'], 'thumb') ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fas fa-book"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 group-hover:text-rajasthan-700 transition-colors">
                                            <?= htmlspecialchars($book['title']) ?>
                                        </div>
                                        <div class="text-xs text-gray-500 font-mono mt-0.5">
                                            <?= htmlspecialchars($book['slug']) ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                        <i class="fas fa-user-edit text-[10px] text-gray-400"></i>
                                        <span>Author: <?= htmlspecialchars($book['author_name'] ?? 'None') ?></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                        <i class="fas fa-tags text-[10px] text-gray-400"></i>
                                        <span>Category: <?= htmlspecialchars($book['category_name'] ?? 'None') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $statusClass = match($book['status']) {
                                    'published' => 'bg-green-100 text-green-700 border-green-200',
                                    'draft' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'archived' => 'bg-gray-100 text-gray-700 border-gray-200',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                                ?>
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full border <?= $statusClass ?>">
                                    <?= htmlspecialchars($book['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="<?= BASE_URL ?>/admin/books/<?= (int)$book['id'] ?>/edit" 
                                       class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                       title="Edit Book">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>/admin/books/<?= (int)$book['id'] ?>/delete" class="inline">
                                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this book?')"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                title="Delete Book">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
