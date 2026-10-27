// Service Worker for RajasthanReads
const CACHE_NAME = 'rajasthanreads-v1';
const urlsToCache = [
    '/rajasthan_reads/',
    '/rajasthan_reads/public/assets/js/pdfjs-viewer.js',
    '/rajasthan_reads/public/assets/js/pdf.min.js',
    '/rajasthan_reads/public/assets/js/pdf.worker.min.js'
];

// Install event - cache assets
self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request)
            .then(function(response) {
                // Return cached version or fetch from network
                if (response) {
                    return response;
                }
                return fetch(event.request);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.map(function(cacheName) {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
