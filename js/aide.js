function dropDownHelp(field) {
    if (field.style.maxHeight == '2.5em'){
        field.style.maxHeight = '38em'
        field.style.transition = 'max-height 1s ease-in'
    }
    else {
        field.style.maxHeight = '2.5em'
        field.style.transition = 'max-height 1s ease-out'
    }
}
