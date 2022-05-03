
const buttonsWrapper = document.querySelector(".btnMap");
const slides = document.querySelector(".cadre");

buttonsWrapper.addEventListener("click", e => {
  if (e.target.nodeName === "BUTTON") {
    Array.from(buttonsWrapper.children).forEach(item =>
      item.classList.remove("active")
    );
    if (e.target.classList.contains("first")) {
      slides.style.transform = "translateX(0px)";
      e.target.classList.add("active");
    } else if (e.target.classList.contains("second")) {
      if(window.innerWidth > 600)
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
