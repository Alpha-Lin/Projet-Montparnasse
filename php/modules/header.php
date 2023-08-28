<?php
require 'html/header.html';

if(isset($_SESSION['stonks-me-session'])){
  $req = $bdd->prepare("SELECT stepLock FROM stonks_me_sessions WHERE id = ?"); // Récupère l'étape bloquée
  $req->execute(array($_SESSION['stonks-me-session']));

  $stepLocked = $req->fetch()[0];

  echo '
        <div class="dropdown" id="dropdown" valeur="closed">
            <button class="dropbtn">
                <i class="fa fa-search fa-lg"></i>
            </button>
            <div class="dropdown-content" id="dropdown-content">
                <form>
                    <input placeholder="Rechercher..." type="search" id="search" name="search" required>
                </form>
            </div>
        </div>
        <a href="?i=aide" style="float: right"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
        <script src="../js/header.js"></script>';
}

if(isset($_SESSION['id'])){
    $req = $bdd->prepare('SELECT COUNT(id) FROM products WHERE sellerID = ?');
    $req->execute(array($_SESSION['id']));

    $isVendor = $req->fetch(PDO::FETCH_COLUMN) > 0;

    echo '<a href="?i=Compte" class="compte" id="utilisateur"><i class="fa fa-user" id="iconeU" aria-hidden="true"></i></a>
          <div id="compteDropDown">
            <a href="?i=Compte">Mon profil</a>' .
            ($isVendor ? '<a href="?i=sales">Mes ventes</a>' : '') .
            ($_SESSION['isAdmin'] && $stepLocked > 6 ? '<a href="?i=interfaceAdmin"><i class="fa fa-terminal" aria-hidden="true"></i></a>' : '').
            '<a href="logout.php">Déconnexion</a>
          </div>
          <a href="?i=panier" id="panier" style="float: right"><i class="fa fa-shopping-basket" id="iconeB" aria-hidden="true"></i></a>' .
          ($stepLocked > 5 ? '<a href="?i=upload_announcement" style="float: right"><i class="fa fa-plus" aria-hidden="true"></i></a>' : '') .
          ($_SESSION['isAdmin'] && $stepLocked > 6 ? '<a href="?i=fin" style="float: right"><i class="fa fa-flag" aria-hidden="true"></i></a>': '');
}
?>

</nav> 

<?php
    if(isset($_GET['search']) && !empty($_GET['search']))
        require 'html/slide_down_menu.html';
?>

<main>
