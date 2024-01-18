(function (window) {

  /**
   * Listen for postMessage events from the iframe.
   */
  window.addEventListener('message', function (event) {
    // Do we trust the sender of this message?
    if (event.origin !== window.location.origin) {
      return;
    }
    if (event.data === '' || event.data.length === 0) {
      return;
    }
    // Check that it is the message from frontend editing.
    if (typeof event.data.command === 'undefined' || event.data.command !== 'closeSidePanel') {
      return;
    }
    if (event.data.selector) {
      const selector = event.data.selector.replace('data-frontend-editing', 'data-fe-update-content')
      const element = document.querySelector(selector);
      if (element) {
        element.click();
      }
      else {
        window.location.reload();
      }
    }
  }, false);

})(window);
