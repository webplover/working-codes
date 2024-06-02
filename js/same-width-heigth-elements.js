function setMaxDimensions(selector) {
  const elements = [...document.querySelectorAll(selector)];

  const maxWidth = Math.max(...elements.map((el) => el.offsetWidth));
  const maxHeight = Math.max(...elements.map((el) => el.offsetHeight));

  elements.forEach((el) => {
    el.style.width = `${maxWidth}px`;
    el.style.height = `${maxHeight}px`;
  });
}

// Call the function for different selectors
setMaxDimensions(".my-box");
