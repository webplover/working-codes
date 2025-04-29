jQuery(async function ($) {
  const response = await $.getJSON("https://webplover.com/ipinfo/");
  if (response.geoplugin_status === 200) {
    const {
      geoplugin_region,
      geoplugin_city,
      geoplugin_locationAccuracyRadius,
    } = response;
    const region = geoplugin_region;
    const city = geoplugin_city;
    const radius =
      geoplugin_locationAccuracyRadius && geoplugin_locationAccuracyRadius > 0
        ? geoplugin_locationAccuracyRadius
        : null; // If invalid, don't include radius

    if (region && city) {
      let city_region = region === city ? city : `${city}, ${region}`;

      /**
       *
       *
       * Start - Your Code
       *
       *
       */

      await wpr_type_text(
        $(".your-selector"),
        ` - in ${city_region}`,
        "#00FFFF"
      );

      /**
       *
       *
       * End - Your Code
       *
       *
       */
    }
  }
});
