var bouton = document.getElementById("button");
var arrow = document.getElementById("arrow");
var content = bouton.getAttribute("valeur");
bouton.addEventListener("click", function open() {
    if(content == "closed")
    {
        document.getElementById("wrapper").style.transitionDuration = "1s";
        document.getElementById("wrapper").style.height = "240px";
        button.style.transitionDuration = "1.24s";
        button.style.top = "200px"
        arrow.style.transform = "rotate(225deg)";
        content = "opened";
    }
    else
    {
        document.getElementById("wrapper").style.height = "0px";
        button.style.transitionDuration = "1s";
        button.style.top = "0px";
        arrow.style.transform = "rotate(45deg)";
        content = "closed";
    }
});
