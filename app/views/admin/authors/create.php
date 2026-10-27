<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Add New Author</h1>
    <p class="text-gray-600">Create a new author profile in the library</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <form action="<?= BASE_URL ?>/admin/authors" method="POST" class="p-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Author Name (English) *</label>
                <input type="text" name="name" id="name" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                       placeholder="e.g. Vijaydan Detha">
            </div>
            
            <div class="space-y-2">
                <label for="name_hindi" class="block text-sm font-medium text-gray-700">Author Name (Hindi/Rajasthani)</label>
                <input type="text" name="name_hindi" id="name_hindi"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                       placeholder="e.g. विजयदान देथा">
            </div>
            
            <div class="space-y-2">
                <label for="slug" class="block text-sm font-medium text-gray-700">URL Slug (Leave blank for auto-generate)</label>
                <input type="text" name="slug" id="slug"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                       placeholder="e.g. vijaydan-detha">
            </div>
            
            <div class="space-y-2">
                <label for="district_id" class="block text-sm font-medium text-gray-700">Region/District</label>
                <select name="district_id" id="district_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500">
                    <option value="">Select Region</option>
                    <?php foreach ($districts as $district): ?>
                        <option value="<?= $district['id'] ?>"><?= $this->escape($district['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="md:col-span-2 space-y-2">
                <label for="bio" class="block text-sm font-medium text-gray-700">Biography</label>
                <textarea name="bio" id="bio" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-rajasthan-500 focus:border-rajasthan-500"
                          placeholder="Write a brief biography about the author..."></textarea>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
            <a href="<?= BASE_URL ?>/admin/authors" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-rajasthan-600 text-white rounded-lg hover:bg-rajasthan-700 transition-colors font-medium">
                Create Author
            </button>
        </div>
    </form>
</div>
