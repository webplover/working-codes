/*
 * This script dynamically manipulates text based on the user's IP location.
 * 
 * Functions:
 * - wpr_erase_text_and_type: Erases existing text and types new text with a blinking cursor effect.
 * - wpr_type_text: Types text with a blinking cursor effect.
 * 
 * Usage:
 * - Automatically fetches the user's public IP using ipify.
 * - Sends the IP to a backend service at "https://webplover.com/apps/ipinfo/".
 * - Receives the user's ip info from the backend response.
 * - Dynamically updates text on the page with the user's country.
 * 
 * Important:
 * - Before using this code, make sure your domain is allowed in "webplover.com/apps/ipinfo".
 */


function wpr_erase_text_and_type(element, newText, cursorColor = "#000") {
  const cursor = `<span style="color:${cursorColor}">|</span>`;

  const text = element.text();
  let i = 0;

  const eraseInterval = setInterval(function () {
    if (i <= text.length) {
      element.html(text.substring(0, text.length - i) + cursor);
      i++;
    } else {
      clearInterval(eraseInterval);
      wpr_type_text(element, newText, cursorColor);
    }
  }, 30);
}

function wpr_type_text(element, newText, cursorColor = "#000") {
  const cursor = `<span style="color:${cursorColor}">|</span>`;

  let i = 0;

  const addInterval = setInterval(function () {
    if (i <= newText.length) {
      element.html(newText.substring(0, i) + cursor);
      i++;
    } else {
      clearInterval(addInterval);
      element.html(newText);
    }
  }, 50);
}

jQuery(async function ($) {
  // Fetch the user's public IP address using ipify
  const ipResponse = await fetch("https://api.ipify.org?format=json");
  const ipData = await ipResponse.json();

  // Send the user's IP to the backend
  const backendResponse = await fetch("https://webplover.com/apps/ipinfo", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      ip: ipData.ip,
    }),
  });

  const data = await backendResponse.json();

  const country = data.country;

  if (country) {
    /**
     * add dynamic country
     */
    wpr_type_text($(".selector"), `in ${country}`);
    wpr_type_text($(".selector"), `in ${country}`);

    /**
     * erase text and type new text with dynamic country
     */

    wpr_erase_text_and_type(
      $(".selector"),
      `For ${country} Students`,
      "#d79848"
    );
  }
});
