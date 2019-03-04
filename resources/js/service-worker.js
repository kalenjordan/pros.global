// empty on purpose
// let cacheName = 'pros.global-v0.1';
// let filesToCache = [
//     '/js/bootstrap.js',
//     '/',
// ];
//
// self.addEventListener('install', function(e) {
//     console.log('[ServiceWorker] Install ' + cacheName);
//     e.waitUntil(
//         caches.open(cacheName).then(function(cache) {
//             console.log('[ServiceWorker] Caching app shell');
//             return cache.addAll(filesToCache);
//         })
//     );
// });
//
// self.addEventListener('fetch', function(e) {
//     // console.log('[ServiceWorker] Fetch', e.request.url);
//     if (e.request.cache === 'only-if-cached' && e.request.mode !== 'same-origin') {
//         return;
//     }
//     e.respondWith(
//         caches.match(e.request).then(function(response) {
//             if (response) {
//                 // console.log('[ServiceWorker] Returning from cache');
//                 return response;
//             } else {
//                 return fetch(e.request);
//             }
//         })
//     );
// });
