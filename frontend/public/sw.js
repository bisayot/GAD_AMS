const CACHE_NAME = 'gad-ams-static-v2'; // Incremented version
console.log('[SW] Initializing Version:', CACHE_NAME);

const APP_SHELL = [
  './',
  'index.php',
  'site.webmanifest',
  'favicon.ico',
  'images/bsulogo.webp',
  'images/gad_logo_enhanced.png',
  'images/logo.png',
];
const BUILD_ASSETS = [];

const isCacheableResponse = (response) =>
  response && (response.ok || response.type === 'opaque');

const isStaticRequest = (request) => {
  if (request.method !== 'GET') return false;
  if (request.url.startsWith(self.location.origin)) {
    return ['script', 'style', 'image', 'font', 'manifest'].includes(request.destination);
  }

  return ['style', 'font'].includes(request.destination);
};

self.addEventListener('install', (event) => {
  const urlsToCache = [...APP_SHELL, ...BUILD_ASSETS];
  event.waitUntil(
    caches.open(CACHE_NAME).then(async (cache) => {
      // Use a more resilient approach: cache items one by one
      // so that one failure doesn't break the whole PWA installation.
      for (const url of urlsToCache) {
        try {
          await cache.add(url);
        } catch (error) {
          console.warn(`[SW] Failed to cache: ${url}. Error:`, error);
        }
      }
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key !== CACHE_NAME)
          .map((key) => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  const { request } = event;

  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then((response) => {
          if (response.ok) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
          }
          return response;
        })
        .catch(async () => {
          const cache = await caches.open(CACHE_NAME);
          return (
            (await cache.match(request)) ||
            (await cache.match('/')) ||
            (await cache.match('/index.php'))
          );
        })
    );
    return;
  }

  if (!isStaticRequest(request)) return;

  event.respondWith(
    caches.match(request).then((cached) => {
      if (cached) return cached;

      return fetch(request).then((response) => {
        if (isCacheableResponse(response)) {
          const copy = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
        }
        return response;
      });
    })
  );
});
