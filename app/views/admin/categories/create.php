<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Add New Category</h1>
    <p class="text-gray-600">Create a new book category in the library</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <form action="<?= BASE_URL ?>/admin/categories" method="POST" class="p-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Category Name (English) *</label>
                <input type="text" name="name" id="name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                       placeholder="e.g. History">
            </div>
            
            <div class="space-y-2">
                <label for="name_hindi" class="block text-sm font-medium text-gray-700">Category Name (Hindi/Rajasthani)</label>
                <input type="text" name="name_hindi" id="name_hindi"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                       placeholder="e.g. इतिहास">
            </div>
            
            <div class="space-y-2 md:col-span-2">
                <label for="slug" class="block text-sm font-medium text-gray-700">URL Slug (Leave blank for auto-generate)</label>
                <input type="text" name="slug" id="slug"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                       placeholder="e.g. history">
            </div>
            
            <div class="md:col-span-2 space-y-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                          placeholder="Describe this category..."></textarea>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
            <a href="<?= BASE_URL ?>/admin/categories" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-rajasthan-600 text-white rounded-lg hover:bg-rajasthan-700 transition-colors font-medium">
                Create Category
            </button>
        </div>
    </form>
</div>
