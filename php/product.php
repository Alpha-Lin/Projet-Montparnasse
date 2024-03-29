<?php
if(isset($_GET['id'])){
    $req = $bdd->prepare('SELECT products.id, name, lastPrice, marketPosition, products.description, releaseDate, conditionP, saleStatus, sellerID, users.id AS vendorID, pseudo, reputation, picture, `rank` FROM products JOIN users ON sellerID = users.id WHERE products.id = ?');
    $req->execute(array($_GET['id']));

    $produit = $req->fetch(PDO::FETCH_ASSOC);

    if(!empty($produit)){
        $req = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');
        $req->execute(array($_GET['id']));

        $pictures = $req->fetchAll(PDO::FETCH_ASSOC);

        require 'php/modules/etoile.php';
        require 'php/modules/price.php';

        echo '<h2 id="articleName">' . $produit['name'] . '</h2>

              <div id="blocArticle">
                <div id="picturesProduct">';
                
            for($i = 1; $i < count($pictures); $i++)
                echo '<img class="minorPictures" src="images/products/' . htmlspecialchars($pictures[$i]['fileName']) . '" alt="Image de ' . htmlspecialchars($produit['name']) . '" onclick="switchToMainPicture(this)">';

            echo     '<img id="mainPicture" src="images/products/' . htmlspecialchars($pictures[0]['fileName']) . '" alt="Image de ' . htmlspecialchars($produit['name']) . '">
                </div>
                <script src="js/product.js"></script>
                <div id="infosProduct">
                    <div id="headerInfos">
                        <div id="pdpStarsRank">
                            <img class="littlePDP" src="' . ($produit['picture'] === NULL ? "svg/avatar.svg" : "data:image;base64," . base64_encode($produit['picture'])) . '" width="64" height="64">' .
                            reputationStars($produit['reputation']) .
                            '<img src="svg/medals/' . strtolower($produit['rank']) . '-medal.svg" class="iconRank" width="64" height="64">
                        </div>
                        <p id="linkVendor">
                            Par <a href="?i=otherUser&id=' . $produit['vendorID']  . '">' . $produit['pseudo'] . '</a><br>
                            le ' . $produit['releaseDate'] .
                       '</p>
                    </div>

                    <div id="descritionProduct">
                        <h3>Description :</h3>
                        <p>' . $produit['description'] . '</p>
                    </div>

                    <div id="marketBlock">' .
                        number_format(reloadExternalPrices($produit, time()), 2, '.', '') . '€';
                        if($produit['saleStatus'] == 1)
                            echo '<p>Article Vendu</p>';
                        else if(!isset($_SESSION['id']) || $produit['sellerID'] != $_SESSION['id'])
                            echo '<button type="button" onclick="location.href=\'?i=panier&add=' . $produit['id'] . '\'">Ajouter au panier</button>';
            echo '</div>
                </div>
              </div>';

        $req = $bdd->prepare('SELECT P.id, name, lastPrice, marketPosition, P.description, releaseDate, saleStatus, sellerID FROM products AS P JOIN users ON sellerID = users.id WHERE name LIKE ? AND id <> ? AND saleStatus = 0 AND (releaseDate <= "2022-05-10 23:18:25" OR idGroupe = ?) LIMIT 3');
        $req->execute(array('%' . $produit['name'] . '%',
                            $produit['id'],
                            $_SESSION['stonks-me-id']));

        $produitsRecommandation = $req->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($produitsRecommandation)){
            echo '<div id="recommandations">
                    <h3>Vous aimerez aussi</h3>';

            require 'php/modules/blocItems.php';

            blocItems($produitsRecommandation);

            echo '</div>';
        }

        echo '<link rel="stylesheet" href="css/article.css">';
    }else
        header('location: ?');
}
else
    header('location: ?');

?>
