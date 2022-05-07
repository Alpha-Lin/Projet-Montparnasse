function switchStep() {
    const formUploadProduct = document.getElementById("blocPrincipal");

    changeDisplay(formUploadProduct.firstElementChild);
    changeDisplay(formUploadProduct.firstElementChild.nextElementSibling);

    document.getElementById('previousStep').hidden = !document.getElementById('previousStep').hidden
    document.getElementById('nextStep').hidden = !document.getElementById('nextStep').hidden
    document.getElementById('submitProduct').hidden = !document.getElementById('submitProduct').hidden
    document.getElementById('fakeBlock').hidden = !document.getElementById('fakeBlock').hidden
}

function showPicture(input) {
    var image =  document.getElementById('firstImg');
    if (input.files && input.files[0]) {
        var reader = new FileReader()

        reader.onload = function (e) {
            input.previousElementSibling.src = e.target.result
        }

       if(image.offsetWidth > "300")
       {
            image.style.width = "300px";
            image.style.objectFit = "cover";
       }

       if(image.offsetHeight > "100")
       {
            image.style.height = "300px";
            image.style.objectFit = "cover";
       }
        

        reader.readAsDataURL(input.files[0]);

        input.nextElementSibling.hidden = false;
    }
}

function resetPicture(imgReset) {
    imgReset.parentElement.firstElementChild.src = 'svg/defaultPicture.svg' // Reset l'image affichée
    imgReset.previousElementSibling.value = '' // Reset l'input
    imgReset.hidden = true // Cache le bouton de reset
}

function resetPictureManagement(imgReset, id) {
    imgReset.nextElementSibling.value = id

    resetPicture(imgReset)
}

function managePicture(input, id) {
    input.nextElementSibling.nextElementSibling.value = id;

    showPicture(input);
}
