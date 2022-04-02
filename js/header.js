var btn = document.getElementById("dropdown");
var value = btn.getAttribute("valeur");


btn.addEventListener("click", function open(){
    
    var content = document.getElementById("dropdown-content");
    
    content.addEventListener("click", function(event){
        event.stopPropagation();
    });

    if(value == "closed")
    {
        content.style.visibility = "visible";
        content.style.transitionDuration = "0.7s";
        content.style.opacity = "1";
        content.style.zIndex = "1";
        value = "opened";
    }
    else
    {   
        content.style.visibility = "hidden";
        content.style.transitionDuration = "0.7s";
        content.style.opacity = "0";
        content.style.zIndex = "0";
        value = "closed";
    }
});