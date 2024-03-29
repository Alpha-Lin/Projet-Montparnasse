var volet = document.getElementById("wrapper");
var bouton = document.getElementById("button");
var arrow = document.getElementById("arrow");
var content = bouton.getAttribute("valeur");

window.addEventListener("resize", function(){
    if(content == "opened")
    {
        if(window.innerWidth > 600)
        {
            volet.style.height = "220px";
            button.style.top = "222px"
        }
        else
        {
            volet.style.height = "510px";
            button.style.top = "510px"
        }
    }
});


bouton.addEventListener("click", function open() {
    if(content == "closed")
    {
        volet.style.transitionDuration = "1s";
        button.style.transitionDuration = "1s";
        if(window.innerWidth > 600)
        {
            volet.style.height = "220px";
            button.style.top = "222px"
        }
        else
        {
            volet.style.height = "510px";
            button.style.top = "510px";
        }
        arrow.style.transform = "rotate(225deg)";
        content = "opened";
    }
    else
    {
        volet.style.transitionDuration = "1s";
        volet.style.height = "0px";
        button.style.transitionDuration = "1s";
        button.style.top = "0px";
        arrow.style.transform = "rotate(45deg)";
        
        content = "closed";
    }
});
