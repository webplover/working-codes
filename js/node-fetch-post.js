import fetch from "node-fetch";

(async function () {
  let body = {
    title: "Post Title",
    content: "Post Content",
    status: "draft",
    acf: {
      field_name: "set-this-value",
    },
  };

  let credential =
    "Basic " + Buffer.from(`${username}:${password}`).toString("base64");

  await fetch("https://example.com/posts/123", {
    method: "post",
    body: JSON.stringify(body),
    headers: {
      Authorization: credential,
      "Content-Type": "application/json",
    },
  });
})();
