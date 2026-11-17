const btn = document.getElementById("genBtn");
const para = document.getElementById("para");

btn.addEventListener("click", function(_) {
    para.innerText = Math.round(Math.random() * 9) + 1;
});