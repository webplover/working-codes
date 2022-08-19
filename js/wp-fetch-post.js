(async () => {
  await fetch(`https://example.com/wp-json/wp/v2/posts/${id}`, {
    method: "POST",
    body: JSON.stringify({ status: "draft" }),
    headers: {
      Authorization: "Basic " + btoa("username:password"),
      "Content-Type":
        "application/json; charset=UTF-8; application/x-www-form-urlencoded",
    },
  });
})();
