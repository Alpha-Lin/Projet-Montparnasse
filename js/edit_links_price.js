let nb_url_input = 1

function add_url(url_input)
{
    const new_url = document.createElement("div")
    new_url.className = "url_prix"
    new_url.innerHTML = '<input name="url_prix[]" required><img src="svg/remove-button.svg" width="20" onclick="remove_url(this.parentElement)">'

    url_input.parentElement.appendChild(new_url)

    nb_url_input++

    if (nb_url_input === 5)
        url_input.hidden = true
}

function remove_url(parent)
{
    parent.remove()

    if(nb_url_input === 5)
        document.getElementById('add_url_image').hidden = false

    nb_url_input--
}