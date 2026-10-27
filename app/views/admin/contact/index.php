<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contact Messages</h1>
            <p class="text-gray-500">View and manage inquiries from users.</p>
        </div>
    </div>

    <!-- Messages List -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Sender</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-gray-200"></i>
                                    <p>No messages found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $message): ?>
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-6 py-4">
                                    <?php 
                                    $statusClasses = [
                                        'unread' => 'bg-blue-50 text-blue-600',
                                        'read' => 'bg-gray-50 text-gray-600',
                                        'replied' => 'bg-green-50 text-green-600',
                                        'archived' => 'bg-orange-50 text-orange-600'
                                    ];
                                    $statusClass = $statusClasses[$message['status']] ?? 'bg-gray-50 text-gray-600';
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                        <?= ucfirst($message['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900"><?= htmlspecialchars($message['name']) ?></span>
                                        <span class="text-xs text-gray-500"><?= htmlspecialchars($message['email']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 truncate max-w-xs block"><?= htmlspecialchars($message['subject']) ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <?= date('M d, Y H:i', strtotime($message['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="<?= BASE_URL ?>/admin/contact/<?= $message['id'] ?>" class="text-rajasthan-600 hover:text-rajasthan-700 font-medium text-sm">View</a>
                                    <form action="<?= BASE_URL ?>/admin/contact/<?= $message['id'] ?>/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-sm opacity-0 group-hover:opacity-100 transition-opacity">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
