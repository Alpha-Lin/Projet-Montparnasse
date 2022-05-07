var creationCompte = document.getElementById('createAccount');
var gestionCompte = document.getElementById('account');
var recherche = document.getElementById('searchHelp');
var vendeur = document.getElementById('vendor');
var panier = document.getElementById('shoppingCart');
var achat = document.getElementById('buyProduct');
var avis = document.getElementById('rate');
var annonce = document.getElementById('uploadAnnouncement');

var creationCompteVal = creationCompte.getAttribute('valeur');
var gestionCompteVal = gestionCompte.getAttribute('valeur');
var rechercheVal = recherche.getAttribute('valeur');
var vendeurVal = vendeur.getAttribute('valeur');
var panierVal = panier.getAttribute('valeur');
var achatVal = achat.getAttribute('valeur');
var avisVal = avis.getAttribute('valeur');
var annonceVal = annonce.getAttribute('valeur');


creationCompte.addEventListener("click", function(){
    if(creationCompteVal == "closed")
    {
        creationCompte.style.transitionDuration = "1s";
        creationCompte.style.height = "10em";
        creationCompteVal = "opened";
    }
    else
    {
        creationCompte.style.transitionDuration = "1s";
        creationCompte.style.height = "2.5em";
        creationCompteVal = "closed";
    }
});

gestionCompte.addEventListener("click", function(){
    if(gestionCompteVal == "closed")
    {
        gestionCompte.style.transitionDuration = "1s";
        gestionCompte.style.height = "38em";
        gestionCompteVal = "opened";
    }
    else
    {
        gestionCompte.style.transitionDuration = "1s";
        gestionCompte.style.height = "2.5em";
        gestionCompteVal = "closed";
    }
});

recherche.addEventListener("click", function(){
    if(rechercheVal == "closed")
    {
        recherche.style.transitionDuration = "1s";
        recherche.style.height = "10em";
        rechercheVal = "opened";
    }
    else
    {
        recherche.style.transitionDuration = "1s";
        recherche.style.height = "2.5em";
        rechercheVal = "closed";
    }
});

vendeur.addEventListener("click", function(){
    if(vendeurVal == "closed")
    {
        vendeur.style.transitionDuration = "1s";
        vendeur.style.height = "10em";
        vendeurVal = "opened";
    }
    else
    {
        vendeur.style.transitionDuration = "1s";
        vendeur.style.height = "2.5em";
        vendeurVal = "closed";
    }
});

panier.addEventListener("click", function(){
    if(panierVal == "closed")
    {
        panier.style.transitionDuration = "1s";
        panier.style.height = "10em";
        panierVal = "opened";
    }
    else
    {
        panier.style.transitionDuration = "1s";
        panier.style.height = "2.5em";
        panierVal = "closed";
    }
});

achat.addEventListener("click", function(){
    if(achatVal == "closed")
    {
        achat.style.transitionDuration = "1s";
        achat.style.height = "12em";
        achatVal = "opened";
    }
    else
    {
        achat.style.transitionDuration = "1s";
        achat.style.height = "2.5em";
        achatVal = "closed";
    }
});

avis.addEventListener("click", function(){
    if(avisVal == "closed")
    {
        avis.style.transitionDuration = "1s";
        avis.style.height = "15em";
        avisVal = "opened";
    }
    else
    {
        avis.style.transitionDuration = "1s";
        avis.style.height = "2.5em";
        avisVal = "closed";
    }
});

annonce.addEventListener("click", function(){
    if(annonceVal == "closed")
    {
        annonce.style.transitionDuration = "1s";
        annonce.style.height = "20em";
        annonceVal = "opened";
    }
    else
    {
        annonce.style.transitionDuration = "1s";
        annonce.style.height = "2.5em";
        annonceVal = "closed";
    }
});