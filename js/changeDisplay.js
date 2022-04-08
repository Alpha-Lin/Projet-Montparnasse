function changeDisplay(element){
    if (element.style.display == 'none') {
        element.style.display = 'grid'
        return true
    }

    element.style.display = 'none'
    return false
}
