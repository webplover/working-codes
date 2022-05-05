/**
 * Add jQuery to any website from console
 */

let jqry = document.createElement("script");
jqry.src = "https://code.jquery.com/jquery-3.6.0.min.js";
document.getElementsByTagName("head")[0].appendChild(jqry);
jQuery.noConflict();
