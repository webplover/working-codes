var tlds_array = [];

document.querySelectorAll("tbody tr").forEach((el) => {
  const domain = el.querySelector("td:first-child").innerText.trim();
  const tld = domain.split(".").pop().trim();
  tlds_array.push(tld)
});

console.log(tlds_array.join(', '))
