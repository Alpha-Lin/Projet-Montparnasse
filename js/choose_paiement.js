function choose_paiement(element, tag) {
    let selectionElements = document.getElementsByTagName('input');

    for (let i = 0; i < selectionElements.length; i++){
        if (selectionElements[i].name == tag)
            selectionElements[i].parentNode.style.border = '';
    }

    element.parentNode.style.border = 'solid 3px blue'
    element.parentNode.style.borderRadius = '1em'
}
