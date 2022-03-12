const observer = new MutationObserver((mutationsList) => {
  for (let mutation of mutationsList) {
    if (mutation.type === "childList") {
      // Do Something...
      console.log(866);
    }
  }
});

observer.observe(document.querySelector("#gform_wrapper_1"), {
  childList: true,
  // subtree: true
});
