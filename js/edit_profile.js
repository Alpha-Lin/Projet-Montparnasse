function edit_profile(){
    const inputs = document.getElementsByClassName("edit_input")
    
    for (let i = 0; i < inputs.length; i++){
        inputs[i].hidden = !inputs[i].hidden
    }
}

function add_address(img) {
    img.src = changeDisplay(document.getElementById('addAddressForm')) ? 'svg/remove-button.svg' : 'svg/add-button.svg'
}

function edit_address(imgNode) {
    imgNode.parentNode.parentNode.children[1].hidden = !imgNode.parentNode.parentNode.children[1].hidden
    changeDisplay(imgNode.parentNode.parentNode.children[2])
}

function changeDisplay(element){
    if (element.style.display == 'none') {
        element.style.display = 'grid'
        return true
    }

    element.style.display = 'none'
    return false
}
