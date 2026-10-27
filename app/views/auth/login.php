<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-br from-rajasthan-700 to-rajasthan-900 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/10 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-black text-white tracking-tight">Admin Access</h2>
                    <p class="text-rajasthan-200 text-sm mt-2 font-medium">Authorized personnel only</p>
                </div>
            </div>

            <!-- Form Body -->
            <div class="p-8">
                <form method="POST" action="<?= BASE_URL ?>/login" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" required
                                   class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 pl-12 text-gray-800 font-medium focus:ring-2 focus:ring-rajasthan-500 focus:border-transparent outline-none transition-all placeholder-gray-300"
                                   placeholder="admin@rajasthanreads.org">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" name="password" required
                                   class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3.5 pl-12 text-gray-800 font-medium focus:ring-2 focus:ring-rajasthan-500 focus:border-transparent outline-none transition-all placeholder-gray-300"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-rajasthan-600 text-white py-4 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-rajasthan-700 transition-all shadow-lg shadow-rajasthan-600/20 active:scale-[0.98]">
                        <i class="fas fa-sign-in-alt mr-2"></i> Authenticate
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-50 text-center">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-300">
                        <i class="fas fa-lock text-[8px] mr-1"></i> Secure administrative gateway
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
