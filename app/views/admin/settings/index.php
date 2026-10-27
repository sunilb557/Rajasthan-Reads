<div class="max-w-4xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Site Settings</h1>
        <p class="text-gray-500">Configure global site parameters, contact details, and social links.</p>
    </div>

    <form action="<?= BASE_URL ?>/admin/settings/update" method="POST" class="space-y-6">
        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
        
        <!-- Contact Details -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                <i class="fas fa-address-book text-rajasthan-600"></i>
                <h3 class="font-bold text-gray-900">Contact Information</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Public Email</label>
                        <input type="email" name="settings[contact_email]" value="<?= htmlspecialchars($settings['contact_email']['setting_value'] ?? '') ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rajasthan-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Public Phone</label>
                        <input type="text" name="settings[contact_phone]" value="<?= htmlspecialchars($settings['contact_phone']['setting_value'] ?? '') ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rajasthan-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Office Address</label>
                    <textarea name="settings[contact_address]" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rajasthan-500"><?= htmlspecialchars($settings['contact_address']['setting_value'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">WhatsApp Number</label>
                    <input type="text" name="settings[contact_whatsapp]" value="<?= htmlspecialchars($settings['contact_whatsapp']['setting_value'] ?? '') ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rajasthan-500">
                </div>
            </div>
        </div>

        <!-- Social Links -->
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center gap-3">
                <i class="fas fa-share-alt text-rajasthan-600"></i>
                <h3 class="font-bold text-gray-900">Social Media Links</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Twitter / X URL</label>
                        <input type="url" name="settings[social_twitter]" value="<?= htmlspecialchars($settings['social_twitter']['setting_value'] ?? '') ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rajasthan-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Facebook URL</label>
                        <input type="url" name="settings[social_facebook]" value="<?= htmlspecialchars($settings['social_facebook']['setting_value'] ?? '') ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rajasthan-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Instagram URL</label>
                        <input type="url" name="settings[social_instagram]" value="<?= htmlspecialchars($settings['social_instagram']['setting_value'] ?? '') ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rajasthan-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-rajasthan-600 text-white rounded-xl font-bold hover:bg-rajasthan-700 transition-all shadow-lg shadow-rajasthan-100">
                <i class="fas fa-save mr-2"></i>
                Save All Settings
            </button>
        </div>
    </form>
</div>
