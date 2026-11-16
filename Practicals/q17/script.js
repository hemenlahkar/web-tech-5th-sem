const btn = document.getElementsByTagName("button")[0];
const div = document.getElementById("paraContainer");

const paragraphs = [
  "JavaScript is a versatile language used for web development.",
  "HTML provides the structure of a webpage, while CSS styles it.",
  "The DOM allows JavaScript to interact with HTML elements.",
  "APIs enable communication between different software systems.",
  "Debugging is an essential skill for any programmer.",
];

btn.addEventListener("click", function (e) {
  const para = document.createElement("p");
  para.innerText = paragraphs[Math.floor(Math.random() * paragraphs.length)];
  div.appendChild(para);
});
