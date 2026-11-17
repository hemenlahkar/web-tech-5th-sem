const form = document.getElementById("myForm");

form.addEventListener("submit", handleSubmit);

function handleSubmit(e) {
    e.preventDefault();
    const data = new FormData(form);
    let confirmationMsg = "<div>";
    for (const [name, value] of data) {
        confirmationMsg += `<div>${name.charAt(0).toUpperCase() + name.slice(1)} : ${value}</div>`;
    }
    confirmationMsg += "</div><br/><div>Form Submitted Successfully</div>";
    document.getElementsByTagName('main')[0].innerHTML = confirmationMsg;
    setTimeout(() => {
        window.location.reload();
    }, 5000);
}