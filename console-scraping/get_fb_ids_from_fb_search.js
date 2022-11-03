var links = [];

// Get group links
document
  .querySelectorAll(
    'div[aria-label="Search results"] a[href*="/groups/"][tabindex="0"]'
  )
  .forEach((item) => {
    links.push(item.getAttribute("href"));
  });

// Get group ids from links
var all_group_ids = links.map((link) => link.match(/(?<=groups\/).*(?=\/)/)[0]);

// Get number ids
var number_ids = all_group_ids.filter((id) => !isNaN(id));

// Get none number ids
var non_number_ids = all_group_ids.filter((id) => isNaN(id));
