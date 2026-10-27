(function () {
  'use strict';

  // Helper
  const $ = (id) => document.getElementById(id);

  // Configuration from window
  const config = window.READER_CONFIG || {};
  const url = config.filePath;
  const workerSrc = window.PDFJS_WORKER_SRC;

  if (!url) {
    console.error('PDF file path not provided');
    const status = $('status');
    if (status) status.textContent = 'Error: PDF path missing';
    return;
  }

  const pdfjsLib = window['pdfjs-dist/build/pdf'] || window['pdfjsLib'];
  if (!pdfjsLib) {
    const status = $('status');
    if (status) status.textContent = 'PDF.js not found. Check connectivity.';
    return;
  }

  pdfjsLib.GlobalWorkerOptions.workerSrc = workerSrc;

  // State
  let pdfDoc = null;
  let pageNum = 1;
  let pageRendering = false;
  let pageNumPending = null;
  let scale = 1.0;
  let canvas = $('pdf-canvas');
  let ctx = canvas ? canvas.getContext('2d') : null;

  if (!canvas || !ctx) {
      console.error('Canvas or Context not found');
      return;
  }

  /**
   * Get page info from document, resize canvas accordingly, and render page.
   */
  function renderPage(num) {
    pageRendering = true;
    
    // Update UI elements
    const pageNumInput = $('page-num-input');
    if (pageNumInput) pageNumInput.value = num;
    
    const currDisplay = $('current-page-display');
    if (currDisplay) currDisplay.textContent = num;

    const progress = $('read-progress');
    if (progress && pdfDoc) {
        const percent = (num / pdfDoc.numPages) * 100;
        progress.style.width = percent + '%';
    }

    // Using promise to fetch the page
    pdfDoc.getPage(num).then(function (page) {
      const viewport = page.getViewport({ scale: scale });
      canvas.height = viewport.height;
      canvas.width = viewport.width;

      // Render PDF page into canvas context
      const renderContext = {
        canvasContext: ctx,
        viewport: viewport,
      };
      const renderTask = page.render(renderContext);

      // Wait for rendering to finish
      renderTask.promise.then(function () {
        pageRendering = false;
        if (pageNumPending !== null) {
          // New page rendering is pending
          renderPage(pageNumPending);
          pageNumPending = null;
        }

        // Save progress to API
        saveProgress(num);
      }).catch(function (error) {
        console.error('Render task error:', error);
        pageRendering = false;
      });
    }).catch(function(error) {
        console.error('getPage error:', error);
        pageRendering = false;
    });

    // Update active thumbnail
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    thumbnails.forEach((thumb, index) => {
      if (index + 1 === num) {
        thumb.classList.add('active');
        thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      } else {
        thumb.classList.remove('active');
      }
    });
  }

  function saveProgress(num) {
    const slug = config.bookSlug;
    const csrf = config.csrfToken;
    const baseUrl = config.baseUrl || '';

    if (slug && csrf) {
      const apiPath = baseUrl + '/api/reader/' + encodeURIComponent(slug) + '/progress';
      fetch(apiPath, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ csrf_token: csrf, page: String(num) })
      }).catch(() => {});
    }
  }

  /**
   * If another page rendering in progress, waits until the rendering is
   * finised. Otherwise, executes rendering immediately.
   */
  function queueRenderPage(num) {
    if (pageRendering) {
      pageNumPending = num;
    } else {
      renderPage(num);
    }
  }

  /**
   * Displays previous page.
   */
  function onPrevPage() {
    if (pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
  }

  /**
   * Displays next page.
   */
  function onNextPage() {
    if (pageNum >= pdfDoc.numPages) return;
    pageNum++;
    queueRenderPage(pageNum);
  }

  /**
   * Zoom functions
   */
  function onZoomIn() {
    scale += 0.2;
    updateZoom();
  }

  function onZoomOut() {
    if (scale <= 0.4) return;
    scale -= 0.2;
    updateZoom();
  }

  function updateZoom() {
    const zoomLevel = $('zoom-level');
    if (zoomLevel) zoomLevel.textContent = Math.round(scale * 100) + '%';
    queueRenderPage(pageNum);
  }

  function onFitWidth() {
    const container = $('canvas-container');
    if (!container) return;
    
    pdfDoc.getPage(pageNum).then(function(page) {
        const viewport = page.getViewport({ scale: 1.0 });
        const containerWidth = container.clientWidth - 64; // Padding
        scale = containerWidth / viewport.width;
        updateZoom();
    });
  }

  /**
   * Thumbnails Sidebar
   */
  function generateThumbnails() {
    const list = $('thumbnails-list');
    if (!list) return;
    list.innerHTML = '';

    // Create an intersection observer for lazy loading thumbnails
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const item = entry.target;
                const pageNum = parseInt(item.dataset.page);
                const canvas = item.querySelector('.thumbnail-canvas');
                if (!item.dataset.loaded) {
                    item.dataset.loaded = 'true';
                    renderThumbnail(pageNum, canvas);
                }
            }
        });
    }, {
        root: $('thumbnails-sidebar'),
        rootMargin: '100px' // Load slightly before it comes into view
    });

    for (let i = 1; i <= pdfDoc.numPages; i++) {
      const item = document.createElement('div');
      item.className = 'thumbnail-item group cursor-pointer space-y-2';
      item.dataset.page = i;
      item.innerHTML = `
        <div class="aspect-[2/3] bg-gray-800 rounded-lg overflow-hidden border-2 border-transparent transition-all group-hover:border-gray-600 relative shadow-lg">
            <div class="absolute inset-0 flex items-center justify-center text-gray-700 font-black text-2xl">${i}</div>
            <canvas class="thumbnail-canvas w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity"></canvas>
        </div>
        <p class="text-[10px] text-center font-bold text-gray-500 uppercase tracking-widest group-hover:text-rajasthan-500 transition-colors">Page ${i}</p>
      `;
      
      item.onclick = () => {
        pageNum = i;
        queueRenderPage(pageNum);
      };
      
      list.appendChild(item);
      observer.observe(item);
    }
  }

  function renderThumbnail(num, thumbCanvas) {
    pdfDoc.getPage(num).then(function(page) {
      const viewport = page.getViewport({ scale: 0.2 });
      thumbCanvas.height = viewport.height;
      thumbCanvas.width = viewport.width;
      const renderContext = {
        canvasContext: thumbCanvas.getContext('2d'),
        viewport: viewport
      };
      page.render(renderContext);
    }).catch(err => console.warn('Thumbnail render error:', err));
  }

  /**
   * Bookmark logic
   */
  function toggleBookmarkModal(show) {
    const modal = $('bookmark-modal');
    if (!modal) return;
    
    if (show) {
      const pageSpan = $('bookmark-page');
      if (pageSpan) pageSpan.textContent = pageNum;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    } else {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  }

  /**
   * Asynchronously downloads PDF.
   */
  pdfjsLib.getDocument(url).promise.then(function (pdfDoc_) {
    pdfDoc = pdfDoc_;
    
    // Update Page Count UI
    if ($('page-count')) $('page-count').textContent = pdfDoc.numPages;
    if ($('total-pages-display')) $('total-pages-display').textContent = pdfDoc.numPages;
    
    // Generate Thumbnails
    generateThumbnails();

    // Hide Loader
    const loader = $('reader-loader');
    if (loader) loader.style.display = 'none';
    
    const status = $('status');
    if (status) status.innerHTML = '<span class="w-2 h-2 rounded-full bg-green-500"></span> READY';

    // Initial render
    renderPage(pageNum);
    
    // Trigger loaded event
    window.dispatchEvent(new Event('pdf-loaded'));

  }, function (reason) {
    console.error(reason);
    
    // Hide Loader
    const loader = $('reader-loader');
    if (loader) loader.style.display = 'none';

    // Show error in canvas container
    const container = $('canvas-container');
    if (container) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'flex flex-col items-center justify-center h-full text-center space-y-4';
        errorDiv.innerHTML = `
            <div class="w-16 h-16 bg-red-900/30 text-red-500 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <h3 class="text-white font-bold text-lg">Failed to load PDF</h3>
            <p class="text-gray-400 text-sm max-w-md">${reason.message}</p>
        `;
        container.appendChild(errorDiv);
    }

    const status = $('status');
    if (status) status.innerHTML = `<span class="w-2 h-2 rounded-full bg-red-500"></span> Error: ${reason.message}`;
  });

  // Event Listeners
  if ($('prev')) $('prev').addEventListener('click', onPrevPage);
  if ($('next')) $('next').addEventListener('click', onNextPage);
  if ($('zoom-in')) $('zoom-in').addEventListener('click', onZoomIn);
  if ($('zoom-out')) $('zoom-out').addEventListener('click', onZoomOut);
  if ($('fit-width')) $('fit-width').addEventListener('click', onFitWidth);
  
  if ($('toggle-thumbnails')) {
    $('toggle-thumbnails').addEventListener('click', function() {
        const sidebar = $('thumbnails-sidebar');
        if (sidebar) sidebar.classList.toggle('hidden');
    });
  }

  if ($('fullscreen')) {
    $('fullscreen').addEventListener('click', function() {
        const container = $('reader-container');
        if (!document.fullscreenElement) {
            container.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message}`);
            });
        } else {
            document.exitFullscreen();
        }
    });
  }

  if ($('page-num-input')) {
    $('page-num-input').addEventListener('change', function() {
        const val = parseInt(this.value);
        if (val > 0 && val <= pdfDoc.numPages) {
            pageNum = val;
            queueRenderPage(pageNum);
        } else {
            this.value = pageNum;
        }
    });
  }

  // Bookmark actions
  if ($('bookmark')) $('bookmark').addEventListener('click', () => toggleBookmarkModal(true));
  if ($('bookmark-cancel')) $('bookmark-cancel').addEventListener('click', () => toggleBookmarkModal(false));
  if ($('bookmark-close')) $('bookmark-close').addEventListener('click', () => toggleBookmarkModal(false));
  if ($('modal-overlay')) $('modal-overlay').addEventListener('click', () => toggleBookmarkModal(false));

  if ($('bookmark-save')) {
    $('bookmark-save').addEventListener('click', function() {
        const slug = config.bookSlug;
        const csrf = config.csrfToken;
        const note = $('bookmark-note')?.value || '';
        const baseUrl = config.baseUrl || '';

        if (!slug || !csrf) return;
        
        const apiPath = baseUrl + '/api/reader/' + encodeURIComponent(slug) + '/bookmark';
        fetch(apiPath, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ 
              csrf_token: csrf, 
              page: String(pageNum),
              note: note 
          })
        }).then(response => {
            if (response.ok) {
                toggleBookmarkModal(false);
                const btn = $('bookmark');
                if (btn) {
                    const original = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> Bookmarked';
                    btn.classList.replace('bg-rajasthan-600', 'bg-green-600');
                    setTimeout(() => {
                        btn.innerHTML = original;
                        btn.classList.replace('bg-green-600', 'bg-rajasthan-600');
                    }, 2000);
                }
            }
        }).catch(err => console.error(err));
    });
  }

  // Keyboard navigation
  window.addEventListener('keydown', function(e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
    
    if (e.key === 'ArrowRight' || e.key === 'PageDown') {
        onNextPage();
    } else if (e.key === 'ArrowLeft' || e.key === 'PageUp') {
        onPrevPage();
    }
  });

})();
