function make_same_height_elemetns(el) {
  let setHeight = () => {
    all_values = [];
    document.querySelectorAll(el).forEach((item) => {
      item.style.height = "";
      all_values.push(item.offsetHeight);
    });

    biggest = Math.max(...all_values);

    document.querySelectorAll(el).forEach((item) => {
      item.style.height = biggest + "px";
    });
  };

  window.addEventListener("DOMContentLoaded", setHeight);
  window.addEventListener("resize", setHeight);
}

// Use
make_same_height_elemetns(".wpr-services .kt-blocks-info-box-link-wrap");
