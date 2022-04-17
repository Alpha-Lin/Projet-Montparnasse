
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
      if(window.innerWidth < 600)
      {
        slides.style.transform = "translateX(-30.2em)";
      }
      else
      {
        slides.style.transform = "translateX(-33.7em)";
      }
      e.target.classList.add("active");
    } else if (e.target.classList.contains('third')){
      if(window.innerWidth < 600)
      {
        slides.style.transform = 'translatex(-60.5em)';
      }
      else
      {
        slides.style.transform = 'translatex(-67.5em)';
      }
      e.target.classList.add('active');
    }
  }
});
