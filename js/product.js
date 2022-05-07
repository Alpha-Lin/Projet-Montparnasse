const mainPicture = document.getElementById('mainPicture')

function switchToMainPicture(img) {
    const oldSrc = mainPicture.src
    mainPicture.src = img.src
    img.src = oldSrc
}
