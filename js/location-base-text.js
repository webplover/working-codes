jQuery(async function ($) {
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
        $(".your-selector"),
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
