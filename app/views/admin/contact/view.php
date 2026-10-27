<div class="max-w-4xl space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= BASE_URL ?>/admin/contact" class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:text-rajasthan-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Message from <?= htmlspecialchars($message['name']) ?></h1>
            <p class="text-gray-500">Received on <?= date('F j, Y \a\t H:i', strtotime($message['created_at'])) ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Message Content -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Subject</h3>
                    <p class="text-lg font-bold text-gray-900"><?= htmlspecialchars($message['subject']) ?></p>
                </div>
                <div class="pt-4 border-t border-gray-50">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Message</h3>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($message['message']) ?></div>
                </div>
            </div>

            <!-- Reply Box (Placeholder for now) -->
            <div class="bg-gray-50 rounded-2xl border border-dashed border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-rajasthan-100 text-rajasthan-600 flex items-center justify-center">
                        <i class="fas fa-reply"></i>
                    </div>
                    <h3 class="font-bold text-gray-900">Reply to Sender</h3>
                </div>
                <p class="text-gray-500 text-sm mb-4">You can reply to this message directly using your email client.</p>
                <a href="mailto:<?= htmlspecialchars($message['email']) ?>?subject=Re: <?= htmlspecialchars($message['subject']) ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-rajasthan-600 text-white rounded-xl font-bold hover:bg-rajasthan-700 transition-all">
                    <i class="fas fa-paper-plane"></i>
                    Send Email Reply
                </a>
            </div>
        </div>

        <!-- Sender Info & Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Sender Details</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-user text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Name</p>
                            <p class="text-sm text-gray-900"><?= htmlspecialchars($message['name']) ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-envelope text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Email</p>
                            <p class="text-sm text-gray-900"><?= htmlspecialchars($message['email']) ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-network-wired text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">IP Address</p>
                            <p class="text-sm text-gray-900"><?= htmlspecialchars($message['ip_address'] ?? 'Unknown') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
                <form action="<?= BASE_URL ?>/admin/contact/<?= $message['id'] ?>/status" method="POST" class="space-y-4">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                    <div>
                        <label for="status" class="block text-xs font-bold text-gray-400 uppercase mb-2">Update Status</label>
                        <select name="status" id="status" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rajasthan-500">
                            <option value="unread" <?= $message['status'] === 'unread' ? 'selected' : '' ?>>Unread</option>
                            <option value="read" <?= $message['status'] === 'read' ? 'selected' : '' ?>>Read</option>
                            <option value="replied" <?= $message['status'] === 'replied' ? 'selected' : '' ?>>Replied</option>
                            <option value="archived" <?= $message['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                        </select>
                    </div>
                </form>
                
                <div class="pt-4 mt-4 border-t border-gray-50">
                    <form action="<?= BASE_URL ?>/admin/contact/<?= $message['id'] ?>/delete" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                        <button type="submit" class="w-full px-4 py-2 border border-red-100 text-red-600 rounded-xl text-sm font-bold hover:bg-red-50 transition-colors">
                            <i class="fas fa-trash-alt mr-2"></i>
                            Delete Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
