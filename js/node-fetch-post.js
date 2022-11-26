import fetch from "node-fetch";

(async function () {
  await fetch("https://onlydev.ml/onlineacademy/wp-json/wp/v2/posts/36114", {
    method: "post",
    body: JSON.stringify({ status: "draft" }),
    headers: {
      Authorization:
        "Basic " + Buffer.from(`${username}:${password}`).toString("base64"),
      "Content-Type": "application/json",
    },
  });
})();
