<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Book</h1>
    <p class="text-gray-600">Update book details and manage content</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <form action="<?= BASE_URL ?>/admin/books/<?= (int)$book['id'] ?>" method="POST" enctype="multipart/form-data" class="p-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="space-y-2">
                <label for="title" class="block text-sm font-medium text-gray-700">Book Title *</label>
                <input type="text" name="title" id="title" required 
                       value="<?= htmlspecialchars($book['title']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
            </div>
            
            <!-- Slug -->
            <div class="space-y-2">
                <label for="slug" class="block text-sm font-medium text-gray-700">URL Slug *</label>
                <input type="text" name="slug" id="slug" required
                       value="<?= htmlspecialchars($book['slug']) ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
            </div>
            
            <!-- Author -->
            <div class="space-y-2">
                <label for="author_id" class="block text-sm font-medium text-gray-700">Author</label>
                <select name="author_id" id="author_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="">-- None --</option>
                    <?php foreach ($authors as $a): ?>
                        <option value="<?= (int)$a['id'] ?>" <?= $a['id'] == $book['author_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Category -->
            <div class="space-y-2">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" id="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="">-- None --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= $c['id'] == $book['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- District -->
            <div class="space-y-2">
                <label for="district_id" class="block text-sm font-medium text-gray-700">Region/District</label>
                <select name="district_id" id="district_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="">-- None --</option>
                    <?php foreach ($districts as $d): ?>
                        <option value="<?= (int)$d['id'] ?>" <?= $d['id'] == $book['district_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($d['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Status -->
            <div class="space-y-2">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="published" <?= $book['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="draft" <?= $book['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="archived" <?= $book['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
            
            <!-- Files Info -->
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 py-4 bg-gray-50 rounded-lg px-4 border border-gray-100">
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase">Current PDF File</p>
                    <?php if (!empty($book['file_path'])): ?>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                            <a href="<?= BASE_URL ?>/<?= htmlspecialchars(ltrim($book['file_path'], '/')) ?>" target="_blank" class="text-sm text-rajasthan-600 hover:underline">
                                View Current PDF
                            </a>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 italic">No PDF uploaded</p>
                    <?php endif; ?>
                </div>
                
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase">Current Cover Image</p>
                    <?php if (!empty($book['cover_image'])): ?>
                        <div class="flex items-center gap-3">
                            <img src="<?= Image::url($book['cover_image'], 'thumb') ?>" class="w-10 h-14 object-cover rounded shadow-sm border border-gray-200">
                            <span class="text-sm text-gray-600"><?= basename($book['cover_image']) ?></span>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 italic">No cover image</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Replace Cover Image -->
            <div class="space-y-2">
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Replace Cover Image</label>
                <input id="cover_image" name="cover_image" type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" accept="image/*">
            </div>
            
            <!-- Replace PDF -->
            <div class="space-y-2">
                <label for="pdf_file" class="block text-sm font-medium text-gray-700">Replace PDF Document</label>
                <input id="pdf_file" name="pdf_file" type="file" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" accept="application/pdf">
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2 space-y-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="5"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
            <a href="<?= BASE_URL ?>/admin/books" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-rajasthan-600 text-white rounded-lg hover:bg-rajasthan-700 transition-colors font-medium">
                Update Book
            </button>
        </div>
    </form>
</div>
