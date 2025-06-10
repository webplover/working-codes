// Click "Retry" button every 2 minutes
setInterval(function() {
  var retryButton = jQuery("button").filter(function() {
    return jQuery(this).text().trim() === "Retry";
  }).first();

  if (retryButton.length) {
    retryButton.click();
  }
}, 120000); // 2 minutes = 120000 ms

// Click button.el-button--small with child .el-icon-refresh every 30 minutes
setInterval(function() {
  var refreshButton = jQuery("button.el-button--small").filter(function() {
    return jQuery(this).find(".el-icon-refresh").length > 0;
  }).first();

  if (refreshButton.length) {
    refreshButton.click();
  }
}, 1800000); // 30 minutes = 1800000 ms
