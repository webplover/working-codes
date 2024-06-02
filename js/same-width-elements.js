document.addEventListener("DOMContentLoaded", () => {
  function setMaxWidth(selector) {
    const elements = [...document.querySelectorAll(selector)];
    const maxWidth = Math.max(...elements.map((el) => el.offsetWidth));
    elements.forEach((el) => (el.style.width = `${maxWidth}px`));
  }

  // Call the function for different selectors
  setMaxWidth(".my-box");
});
