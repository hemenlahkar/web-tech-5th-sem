const form = document.getElementById("myForm");

form.addEventListener("submit", function (e) {
  e.preventDefault();
  const data = new FormData(form);
  if (data.get("password") != data.get("cpassword")) {
    alert("Passwords don't match!!");
    document.getElementById("pass").value = "";
    document.getElementById("confPass").value = "";
  } else {
    alert("Logged in successfully");
    window.location.reload();
  }
});
