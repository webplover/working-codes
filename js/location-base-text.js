jQuery(async function ($) {
  function wpr_type_text(element, newText, cursorColor = "#000") {
    return new Promise((resolve) => {
      const cursor = `<span style="color:${cursorColor}">|</span>`;
      let i = 0;

      const addInterval = setInterval(function () {
        if (i <= newText.length) {
          element.html(newText.substring(0, i) + cursor);
          i++;
        } else {
          clearInterval(addInterval);
          element.html(newText);
          resolve(); // Resolve the promise when typing is complete
        }
      }, 50);
    });
  }

  try {
    const response = await $.getJSON("https://ipinfo.io/json");

    // Check if response contains required fields
    if (response && response.city && response.region) {
      const { city, region, country, loc, org, postal, timezone } = response;

      // Combine city + region (avoid duplication if both are same)
      const city_region = city === region ? city : `${city}, ${region}`;

      /**
       *
       * Start - Your Code
       *
       */
      await wpr_type_text(
        $(".hero-first-section .home-hero-heading .wpr-inner-text"),
        ` - in ${city_region}`,
        "#00FFFF"
      );
      /**
       *
       * End - Your Code
       *
       */
    } else {
      console.warn("Location data not found in response:", response);
    }
  } catch (error) {
    console.error("Error fetching IP info:", error);
  }
});
