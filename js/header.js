var btn = document.getElementById("dropdown");
var value = btn.getAttribute("valeur");
var panier = document.getElementById("panier");
var user = document.getElementById("utilisateur");
var iconeB = document.getElementById("iconeB");
var iconeU = document.getElementById("iconeU");
var basketClass = "fa fa-shopping-basket";
var userClass = "fa fa-user";

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



panier.addEventListener("mouseover", function(){
    iconeB.classList.remove.apply(iconeB.classList, basketClass.split(" "));
    iconeB.innerHTML = "Panier";
    panier.addEventListener("mouseout", function(){
        iconeB.innerHTML = "";
        iconeB.classList.add.apply(iconeB.classList, basketClass.split(" "));
    });
});

user.addEventListener("mouseover", function(){
    iconeU.classList.remove.apply(iconeU.classList, userClass.split(" "));
    iconeU.innerHTML = "infoUser";
    user.addEventListener("mouseout", function(){
        iconeU.innerHTML = "";
        iconeU.classList.add.apply(iconeU.classList, userClass.split(" "));
    });
});