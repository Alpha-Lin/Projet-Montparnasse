function switchStep() {
    const formUploadProduct = document.getElementById("blocPrincipal");

    changeDisplay(formUploadProduct.firstElementChild)
    changeDisplay(formUploadProduct.firstElementChild.nextElementSibling)

    document.getElementById('previousStep').hidden = !document.getElementById('previousStep').hidden
    document.getElementById('nextStep').hidden = !document.getElementById('nextStep').hidden
    document.getElementById('submitProduct').hidden = !document.getElementById('submitProduct').hidden
    document.getElementById('fakeBlock').hidden = !document.getElementById('fakeBlock').hidden
}

function showPicture(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader()

        reader.onload = function (e) {
            input.previousElementSibling.src = e.target.result
        }

        reader.readAsDataURL(input.files[0])

        input.nextElementSibling.hidden = false
    }
}

function resetPicture(imgReset) {
    imgReset.parentElement.firstElementChild.src = 'svg/defaultPicture.svg' // Reset l'image affichée
    imgReset.previousElementSibling.value = '' // Reset l'input
    imgReset.hidden = true // Cache le bouton de reset
}
