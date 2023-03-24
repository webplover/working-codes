import axios from "axios";

const data = {
  regular_price: "12",
  sale_price: "9.00",
};

(async function () {
  // GET Request
  let response1 = await axios.put(
    "https://example.com/wp-json/wc/v3/products/16/variations/17",
    {
      auth: {
        username: "ck_12345678910",
        password: "cs_12345678910",
      },
    }
  );

  // PUT Request
  let response2 = await axios.put(
    "https://example.com/wp-json/wc/v3/products/16/variations/17",
    data,
    {
      auth: {
        username: "ck_12345678910",
        password: "cs_12345678910",
      },
    }
  );
})();
