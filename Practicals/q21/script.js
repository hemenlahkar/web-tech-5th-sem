const ul = document.getElementsByTagName("ul")[0];

ul.addEventListener("click", function (e) {
  const li = e.target.closest("li");
  if (!li) return;
  li.style.backgroundColor = "yellow";
});
