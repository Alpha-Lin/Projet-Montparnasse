function edit_profile(){
    const inputs = document.getElementsByClassName("edit_input")
    
    for (let i = 0; i < inputs.length; i++){
        inputs[i].hidden = !inputs[i].hidden
    }
}