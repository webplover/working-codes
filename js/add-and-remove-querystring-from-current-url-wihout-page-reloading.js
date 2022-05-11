// Add
const url = new URL(window.location);
url.searchParams.set('foo', 'bar');
window.history.pushState({}, '', url);


// Remove all query strings
window.history.pushState({}, "", window.location.origin + window.location.pathname);
