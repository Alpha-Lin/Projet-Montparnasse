var btn = document.getElementById('btnCategories');
var fleche = document.getElementById('fleche');
var categories = document.getElementById('sidebar');

if(categories)
{
    ouvrir();
}


window.addEventListener("resize", function(){
    if(categories)
    {
        ouvrir();
    }
});

function ouvrir()
{
    if(window.innerWidth < 600 && btn)
    {
        btn.style.display = "";
        
        var contenu = btn.getAttribute("valeur");
        btn.addEventListener("click", function() {
            if(contenu == "closed")
            {
                categories.style.transitionDuration = "1s";
                categories.style.transform = "translateX(120px)";
                btn.style.transitionDuration = "1s";
                btn.style.transform = "translateX(120px)";
                fleche.style.transform = "rotate(135deg)";
                contenu = "opened";
            }
            else
            {
                categories.style.transform = "translateX(0)";
                btn.style.transform = "translateX(0)";
                fleche.style.transform = "rotate(315deg)";
                contenu = "closed";
            }
        });
    }
    else
    {
        categories.style.transform = "translateX(0)";
        btn.style.transform = "translateX(0)";
        fleche.style.transform = "rotate(315deg)";
        btn.style.display = "none";
           
    }
}   