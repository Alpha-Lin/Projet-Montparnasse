
const buttonsWrapper = document.querySelector(".btnMap"); //div des boutons de sélection du slideshow/carousel
const slides = document.querySelector(".cadre"); // cadre invisible qui entoure les articles dans le slideshow

buttonsWrapper.addEventListener("click", e => { // si un bouton est cliqué...
  if (e.target.nodeName === "BUTTON") {  // et si la balise est "button"...
    Array.from(buttonsWrapper.children).forEach(item => 
      item.classList.remove("active") // enlever  la classe "active" 
    );
    if (e.target.classList.contains("first")) {
      slides.style.transform = "translateX(0px)";  
      e.target.classList.add("active"); // ajouter la classe "active" au bouton ciblé
    } else if (e.target.classList.contains("second")) {
      if(window.innerWidth > 600) // test pour savoir si l'appareille utilisé est un ordinateur ou un téléphone
      {
        slides.style.transform = "translateX(-28.2em)";
      }
      else
      {
        slides.style.transform = "translateX(-28em)";
      }
      e.target.classList.add("active");
    } else if (e.target.classList.contains('third')){
      if(window.innerWidth > 600)
      {
        slides.style.transform = 'translatex(-56.5em)';
      }
      else
      {
        slides.style.transform = 'translatex(-56em)';
      }
      e.target.classList.add('active');
    }
  }
});
