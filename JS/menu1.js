const sidebar = document.querySelector(".sidebar");
const expandBtn = document.querySelector(".expand-btn");

sidebar.addEventListener("click", () => {
  document.body.classList.remove("collapsed");
});

expandBtn.addEventListener("click", (e) => {
  e.stopPropagation();
  document.body.classList.toggle("collapsed");
});

document.addEventListener("click", (e) => {
  if (!sidebar.contains(e.target) && !expandBtn.contains(e.target)) {
    document.body.classList.add("collapsed");
  }
});

const current = window.location.href;

const allLinks = document.querySelectorAll(".sidebar-links a");

allLinks.forEach((elem) => {
  elem.addEventListener("click", function () {
    const hrefLinkClick = elem.href;

    allLinks.forEach((link) => {
      if (link.href == hrefLinkClick) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });
  });
});

const mainItems = document.querySelectorAll(
  '.main-item'
);

mainItems.forEach((mainItem) => {
  mainItem.addEventListener('click', () => {
    mainItem.classList.toggle(
      'main-item--open'
    );
  })
});