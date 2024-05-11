["mousedown", "mouseup", "click"].forEach((e) => {
  const event = new MouseEvent(e, {
    bubbles: true,
    cancelable: true,
    view: window,
  });
  document.querySelector(".selector");
});
