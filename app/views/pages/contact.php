<div class="max-w-4xl mx-auto bg-white rounded-[2rem] shadow-xl p-8 lg:p-12 mt-8 mb-20">
    <div class="text-center mb-12">
        <h1 class="text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-4">Contact Us</h1>
        <div class="w-20 h-1 bg-rajasthan-600 mx-auto rounded-full mb-6"></div>
        <p class="text-gray-600 font-medium">Have questions, feedback, or want to contribute? We'd love to hear from you.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Contact Information -->
        <div class="space-y-8">
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Get in Touch</h3>
                <p class="text-gray-600">We are always open to discussing new collaborations, answering inquiries, or receiving feedback to improve our archive.</p>
            </div>

            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-rajasthan-50 flex items-center justify-center text-rajasthan-600 flex-shrink-0">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Email</h4>
                        <p class="text-gray-600"><a href="mailto:<?= htmlspecialchars($settings['contact_email'] ?? 'archive@rajasthanreads.org') ?>" class="hover:text-rajasthan-600 transition-colors"><?= htmlspecialchars($settings['contact_email'] ?? 'archive@rajasthanreads.org') ?></a></p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-rajasthan-50 flex items-center justify-center text-rajasthan-600 flex-shrink-0">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Location</h4>
                        <p class="text-gray-600"><?= nl2br(htmlspecialchars($settings['contact_address'] ?? "Rajasthan, India\nDigital operations serving global researchers.")) ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                <h4 class="font-bold text-gray-900 mb-2">Contribute to the Archive</h4>
                <p class="text-sm text-gray-600">If you possess rare manuscripts or books and wish to have them digitized and preserved on our platform, please reach out via email with the subject line <strong>"Archive Contribution"</strong>.</p>
            </div>
        </div>

        <!-- Contact Form -->
        <div>
            <form action="<?= BASE_URL ?>/contact-us" method="POST" class="space-y-6">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-rajasthan-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-rajasthan-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-bold text-gray-700 mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-rajasthan-500 focus:border-transparent transition-all">
                </div>

                <div>
                    <label for="message" class="block text-sm font-bold text-gray-700 mb-2">Message</label>
                    <textarea id="message" name="message" rows="5" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-rajasthan-500 focus:border-transparent transition-all resize-none"></textarea>
                </div>

                <button type="submit" class="w-full px-8 py-4 bg-rajasthan-600 text-white rounded-xl font-black uppercase tracking-widest hover:bg-rajasthan-700 transition-all shadow-lg shadow-rajasthan-600/30 active:scale-95">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
