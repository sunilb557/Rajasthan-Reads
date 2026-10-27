<meta name="csrf-token" content="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">
<script>document.body.setAttribute('data-book-slug', "<?= htmlspecialchars($book['slug']) ?>");</script>

<style>
    #reader-container {
        height: calc(100vh - 120px);
        min-height: 600px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    #pdf-canvas {
        transition: transform 0.2s ease-out;
        image-rendering: high-quality;
    }
    .thumbnail-item.active {
        border-color: #d97706; /* rajasthan-600 */
        background-color: rgba(217, 119, 6, 0.1);
    }
    /* Hide scrollbar for thumbnails */
    #thumbnails-sidebar::-webkit-scrollbar {
        width: 4px;
    }
    #thumbnails-sidebar::-webkit-scrollbar-track {
        background: #1f2937;
    }
    #thumbnails-sidebar::-webkit-scrollbar-thumb {
        background: #4b5563;
        border-radius: 10px;
    }
    /* Pulse animation for loading */
    .reader-loading {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }
</style>

<!-- Reader Shell -->
<div id="reader-container" class="flex flex-col md:flex-row bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">
    
    <!-- Left Sidebar: Thumbnails & Navigation -->
    <div id="thumbnails-sidebar" class="w-full md:w-64 bg-gray-950 border-r border-gray-800 overflow-y-auto hidden md:flex flex-col">
        <div class="p-4 border-b border-gray-800 flex items-center justify-between">
            <h3 class="text-gray-400 text-xs font-black uppercase tracking-widest">Library / Pages</h3>
            <span class="bg-gray-800 text-gray-400 text-[10px] px-2 py-0.5 rounded-full font-bold">PDF</span>
        </div>
        <div id="thumbnails-list" class="flex-1 p-4 space-y-4">
            <!-- Loading State -->
            <div class="space-y-4">
                <div class="aspect-[2/3] bg-gray-800 rounded-lg animate-pulse"></div>
                <div class="aspect-[2/3] bg-gray-800 rounded-lg animate-pulse opacity-50"></div>
                <div class="aspect-[2/3] bg-gray-800 rounded-lg animate-pulse opacity-20"></div>
            </div>
        </div>
    </div>
    
    <!-- Right Panel: Viewer -->
    <div class="flex-1 flex flex-col relative">
        
        <!-- Premium Toolbar -->
        <div class="bg-gray-900/80 backdrop-blur-md border-b border-gray-800 p-3 flex flex-wrap items-center justify-between gap-4 sticky top-0 z-20">
            <!-- Left: Page Controls -->
            <div class="flex items-center bg-gray-950 rounded-xl p-1 border border-gray-800">
                <button id="toggle-thumbnails" class="p-2.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all" title="Toggle Sidebar">
                    <i class="fas fa-columns"></i>
                </button>
                <div class="w-px h-6 bg-gray-800 mx-1"></div>
                <button id="prev" class="p-2.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all" title="Previous Page">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="px-3 flex items-center gap-1.5">
                    <input type="text" id="page-num-input" value="1" class="w-10 bg-gray-900 border border-gray-700 text-white text-center text-xs py-1 rounded focus:border-rajasthan-500 outline-none">
                    <span class="text-gray-600 text-xs">/</span>
                    <span id="page-count" class="text-gray-400 text-xs font-bold">?</span>
                </div>
                <button id="next" class="p-2.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all" title="Next Page">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
            
            <!-- Middle: Zoom Controls -->
            <div class="hidden lg:flex items-center bg-gray-950 rounded-xl p-1 border border-gray-800">
                <button id="zoom-out" class="p-2.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all" title="Zoom Out">
                    <i class="fas fa-search-minus"></i>
                </button>
                <span id="zoom-level" class="text-gray-400 text-xs font-bold w-16 text-center tabular-nums">100%</span>
                <button id="zoom-in" class="p-2.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all" title="Zoom In">
                    <i class="fas fa-search-plus"></i>
                </button>
                <div class="w-px h-6 bg-gray-800 mx-1"></div>
                <button id="fit-width" class="p-2.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all" title="Fit to Width">
                    <i class="fas fa-arrows-alt-h"></i>
                </button>
            </div>
            
            <!-- Right: Actions -->
            <div class="flex items-center gap-2">
                <button id="bookmark" class="px-5 py-2.5 bg-rajasthan-600 hover:bg-rajasthan-700 text-white rounded-xl font-bold text-xs flex items-center gap-2 shadow-lg shadow-rajasthan-900/20 transition-all active:scale-95">
                    <i class="fas fa-bookmark"></i>
                    <span class="hidden sm:inline">Bookmark</span>
                </button>
                <div class="flex items-center bg-gray-950 rounded-xl p-1 border border-gray-800">
                    <button id="fullscreen" class="p-2.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-all" title="Fullscreen">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </button>
                    <a href="<?= BASE_URL ?>/books/<?= htmlspecialchars($book['slug']) ?>" class="p-2.5 text-gray-400 hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-all" title="Close Reader">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Immersive Canvas Area -->
        <div id="canvas-container" class="flex-1 overflow-auto bg-gray-950 scroll-smooth flex flex-col items-center p-8">
            <!-- Reader Loading Overlay -->
            <div id="reader-loader" class="absolute inset-0 z-10 bg-gray-950 flex flex-col items-center justify-center gap-4">
                <div class="w-16 h-16 border-4 border-rajasthan-600/20 border-t-rajasthan-600 rounded-full animate-spin"></div>
                <p class="text-gray-500 font-bold text-xs uppercase tracking-widest animate-pulse">Initializing Reader...</p>
            </div>
            
            <!-- PDF Canvas -->
            <div class="relative shadow-[0_0_100px_rgba(0,0,0,0.8)] rounded-sm overflow-hidden group bg-white">
                <canvas id="pdf-canvas" class="max-w-full h-auto bg-white"></canvas>
                <!-- Page hover info -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 px-4 py-2 bg-black/60 backdrop-blur-md rounded-full text-white/80 text-[10px] font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    Page <span id="current-page-display">1</span> of <span id="total-pages-display">?</span>
                </div>
            </div>
        </div>
        
        <!-- Modernized Bottom Progress Bar -->
        <div class="bg-gray-900 border-t border-gray-800 px-6 py-3 flex items-center justify-between text-[10px] font-bold tracking-widest text-gray-500">
            <div class="flex items-center gap-4">
                <span id="status" class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    READY
                </span>
                <div class="w-px h-3 bg-gray-800"></div>
                <span class="uppercase"><?= htmlspecialchars($book['title']) ?></span>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2">
                    <i class="fas fa-mouse text-xs"></i>
                    <span>USE SCROLL OR ARROWS</span>
                </div>
                <div id="progress-container" class="w-48 h-1 bg-gray-800 rounded-full overflow-hidden">
                    <div id="read-progress" class="h-full bg-rajasthan-600 transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Premium Bookmark Modal -->
<div id="bookmark-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" id="modal-overlay"></div>
    <div class="relative bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl transform transition-all">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-rajasthan-50 rounded-2xl flex items-center justify-center text-rajasthan-600">
                    <i class="fas fa-bookmark text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-gray-900 leading-none">Add Bookmark</h3>
                    <p class="text-sm text-gray-500 mt-1">Saving Page <span id="bookmark-page" class="font-bold text-rajasthan-600">1</span></p>
                </div>
            </div>
            <button id="bookmark-close" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="space-y-6">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Private Note</label>
                <textarea id="bookmark-note" placeholder="Write something about this passage..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl p-4 h-32 focus:ring-2 focus:ring-rajasthan-500 outline-none transition-all resize-none text-gray-700"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button id="bookmark-cancel" class="flex-1 px-6 py-4 text-gray-500 font-bold hover:bg-gray-50 rounded-2xl transition-all">Discard</button>
                <button id="bookmark-save" class="flex-1 px-6 py-4 bg-rajasthan-600 text-white font-bold rounded-2xl shadow-lg shadow-rajasthan-600/20 hover:bg-rajasthan-700 transition-all active:scale-95">Save Bookmark</button>
            </div>
        </div>
    </div>
</div>

<script>
  window.PDFJS_WORKER_SRC = "<?= BASE_URL ?>/assets/js/pdf.worker.min.js";
  window.READER_CONFIG = {
      bookSlug: "<?= htmlspecialchars($book['slug']) ?>",
      filePath: "<?= BASE_URL ?>/<?= htmlspecialchars(ltrim($book['file_path'], '/')) ?>",
      csrfToken: "<?= htmlspecialchars((new CSRF())->generateToken()) ?>",
      baseUrl: "<?= BASE_URL ?>"
  };
  
  // Quick fix for UI elements not in standard viewer but used here
  document.addEventListener('DOMContentLoaded', () => {
      const loader = document.getElementById('reader-loader');
      const container = document.getElementById('canvas-container');
      
      // Hide loader when PDF is loaded (will be handled by viewer script too)
      window.addEventListener('pdf-loaded', () => {
          if(loader) loader.style.display = 'none';
      });
  });
</script>
<script src="<?= BASE_URL ?>/assets/js/pdf.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/pdfjs-viewer.js"></script>
