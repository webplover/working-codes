// Our example url is http://domain.com/?foo=bar

/**
 * Find in query string
 */

if (location.search.indexOf("foo=bar") > -1) {
  //  Do Something...
}

/**
 * Get specific query sting value
 */

const params = new URLSearchParams(window.location.search);

// Check if 'foo' query string is available in url
if (params.has("foo")) {
  // print 'foo' value from url
  params.get("foo"); // Result will 'bar'
}
