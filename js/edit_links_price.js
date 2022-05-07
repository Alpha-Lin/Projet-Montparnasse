let nb_url_input = 1

function add_url(url_input)
{
    const new_url = document.createElement("div")
    new_url.className = "url_prix"
    new_url.innerHTML = '<input type="url" name="url_prix[]" onchange="correct_url(this)" class="externalProductsLinks" required><img src="svg/remove-button.svg" width="20" onclick="remove_url(this.parentElement)">'

    url_input.parentElement.appendChild(new_url)

    nb_url_input++

    if (nb_url_input === 5)
        url_input.hidden = true
}

function remove_url(parent)
{
    parent.remove()
    
    if (nb_url_input === 5)
        document.getElementById('add_url_image').hidden = false

    nb_url_input--
}

function correct_url(url_input)
{
    const pos_end_url = url_input.value.indexOf("?")

    if(url_input.value.indexOf("?") !== -1)
        url_input.value = url_input.value.substring(0, pos_end_url)
}

function remove_url_ById(parent, id)
{
    const inputRemoveId = document.createElement("input")
    inputRemoveId.type = "hidden"
    inputRemoveId.name = "idExternalProducts[]"
    inputRemoveId.value = id

    document.getElementById('externalProducts').appendChild(inputRemoveId)

    remove_url(parent)
}
