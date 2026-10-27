<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manage Authors</h1>
        <p class="text-gray-600">Total Authors: <?= count($authors) ?></p>
    </div>
    <a href="<?= BASE_URL ?>/admin/authors/create" class="bg-rajasthan-600 hover:bg-rajasthan-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i>
        <span>Add Author</span>
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Name</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Slug</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700 text-center">Books</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Created At</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($authors as $author): ?>
            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900"><?= $this->escape($author['name']) ?></div>
                    <?php if ($author['name_hindi']): ?>
                        <div class="text-xs text-gray-500"><?= $this->escape($author['name_hindi']) ?></div>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600"><?= $this->escape($author['slug']) ?></td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2 py-1 bg-rajasthan-100 text-rajasthan-700 text-xs rounded-full font-medium">
                        <?= $author['book_count'] ?>
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <?= date('M d, Y', strtotime($author['created_at'])) ?>
                </td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="<?= BASE_URL ?>/admin/authors/<?= $author['id'] ?>/edit" class="text-blue-600 hover:text-blue-800 transition-colors">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="<?= BASE_URL ?>/admin/authors/<?= $author['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this author?');">
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($authors)): ?>
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    No authors found. <a href="<?= BASE_URL ?>/admin/authors/create" class="text-rajasthan-600 font-medium">Add your first author</a>.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
