function handleSubmit(e) {
    e.preventDefault();

    const name = document.getElementsByName('name')[0].value;
    const email = document.getElementsByName('email')[0].value;

    const ul = document.createElement('ul');
    ul.innerHTML = `<li style="list-style-type: circle">Submitted:</li><li>${name}</li>\n<li>${email}</li>`;
    document.getElementsByTagName('main')[0].appendChild(ul);
}