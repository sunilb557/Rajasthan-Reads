<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Add New Book</h1>
    <p class="text-gray-600">Upload a new book and its details to the library</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <form action="<?= BASE_URL ?>/admin/books" method="POST" enctype="multipart/form-data" class="p-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="space-y-2">
                <label for="title-input" class="block text-sm font-medium text-gray-700">Book Title *</label>
                <input type="text" name="title" id="title-input" required 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                       placeholder="Enter book title" onblur="generateSlug()">
            </div>
            
            <!-- Slug -->
            <div class="space-y-2">
                <label for="slug-input" class="block text-sm font-medium text-gray-700">URL Slug</label>
                <div class="flex gap-2">
                    <input type="text" name="slug" id="slug-input" required
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                           placeholder="book-title-slug">
                    <button type="button" onclick="generateSlug()" 
                            class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
            
            <!-- Author -->
            <div class="space-y-2">
                <label for="author_id" class="block text-sm font-medium text-gray-700">Author</label>
                <select name="author_id" id="author_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="">-- Select Author --</option>
                    <?php foreach ($authors as $a): ?>
                        <option value="<?= (int)$a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Category -->
            <div class="space-y-2">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" id="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- District -->
            <div class="space-y-2">
                <label for="district_id" class="block text-sm font-medium text-gray-700">Region/District</label>
                <select name="district_id" id="district_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="">-- Select Region --</option>
                    <?php foreach ($districts as $d): ?>
                        <option value="<?= (int)$d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Status -->
            <div class="space-y-2">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            
            <!-- Cover Image -->
            <div class="space-y-2">
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover Image</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="cover_image" class="relative cursor-pointer bg-white rounded-md font-medium text-rajasthan-600 hover:text-rajasthan-500 focus-within:outline-none">
                                <span>Upload a file</span>
                                <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
            </div>
            
            <!-- PDF File -->
            <div class="space-y-2">
                <label for="pdf_file" class="block text-sm font-medium text-gray-700">PDF Document</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                    <div class="space-y-1 text-center">
                        <i class="fas fa-file-pdf text-red-400 text-3xl mb-2"></i>
                        <div class="flex text-sm text-gray-600">
                            <label for="pdf_file" class="relative cursor-pointer bg-white rounded-md font-medium text-rajasthan-600 hover:text-rajasthan-500 focus-within:outline-none">
                                <span>Upload a file</span>
                                <input id="pdf_file" name="pdf_file" type="file" class="sr-only" accept="application/pdf">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF documents only</p>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2 space-y-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                          placeholder="Write a brief description about the book..."></textarea>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
            <a href="<?= BASE_URL ?>/admin/books" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-rajasthan-600 text-white rounded-lg hover:bg-rajasthan-700 transition-colors font-medium">
                Create Book
            </button>
        </div>
    </form>
</div>

<script>
async function generateSlug() {
    const titleInput = document.getElementById('title-input');
    const slugInput = document.getElementById('slug-input');
    
    if (titleInput.value.trim() === '') return;
    
    try {
        const response = await fetch(`<?= BASE_URL ?>/api/slug?text=${encodeURIComponent(titleInput.value)}&table=books`);
        const data = await response.json();
        if (data.slug) {
            slugInput.value = data.slug;
        }
    } catch (error) {
        // Fallback simple slug generation if API fails
        slugInput.value = titleInput.value
            .toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    }
}

// Display filename when selected
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function(e) {
        const fileName = e.target.files[0].name;
        const label = e.target.parentElement.parentElement.querySelector('p');
        if (label) label.textContent = fileName;
    });
});
</script>
