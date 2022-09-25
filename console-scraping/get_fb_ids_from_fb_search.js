var links = [];

// Get group links
document
  .querySelectorAll(
    ".qi72231t.nu7423ey.n3hqoq4p.r86q59rh.b3qcqh3k.fq87ekyn.bdao358l.fsf7x5fv.rse6dlih.s5oniofx.m8h3af8h.l7ghb35v.kjdc1dyq.kmwttqpk.srn514ro.oxkhqvkx.rl78xhln.nch0832m.cr00lzj9.rn8ck1ys.s3jn8y49.icdlwmnq.cxfqmxzd.pbevjfx6.innypi6y"
  )
  .forEach((item) => {
    links.push(item.getAttribute("href"));
  });

// Get group ids from links
var group_ids = links.map((link) => link.match(/(?<=groups\/).*(?=\/)/)[0]);

// Get none number ids
var non_number_ids = group_ids.filter((id) => isNaN(id));
