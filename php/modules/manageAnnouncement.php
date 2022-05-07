<script src="https://www.hCaptcha.com/1/api.js" async defer></script>
<script src="js/edit_links_price.js"></script>
<script src="js/uploadProduct.js"></script>
<script src="js/changeDisplay.js"></script>

<link rel="stylesheet" href="/css/uploadProduct.css">
<link rel="stylesheet" href="/css/saleManagement.css">

<h1>Gérer son annonce</h1>

<form method="post" id="blocPrincipal" enctype="multipart/form-data">
    <div id="step1">
        <input type="text" placeholder="Titre..." name="nom_produit" value="<?=htmlspecialchars($sale['name'])?>" required>

        <div class="blocImgs">
            <?php
                $req = $bdd->prepare('SELECT id, fileName FROM pictures WHERE productID = ?');
                $req->execute(array($_GET['saleID']));

                $pictures = $req->fetchAll(PDO::FETCH_ASSOC);
                // TODO : essayer de faire un required pour le premier
                echo '<div>
                        <img src="images/products/' . $pictures[0]['fileName'] . '" alt="Image du produit" id="firstImg" onclick="this.nextElementSibling.click()">
                        <input type="file" name="pictures[]" hidden onchange="managePicture(this,' . $pictures[0]['id'] . ')" accept="image/*">
                        <img src="svg/remove-button.svg" alt="Image du produit" width="20" onclick="resetPictureManagement(this,' . $pictures[0]['id'] . ')">
                        <input type="hidden" name="idOldPictures[]">
                      </div>';

                for($i = 1; $i < 4; $i++){
                    if(isset($pictures[$i]))
                        echo '<div>
                                <img src="images/products/' . $pictures[$i]['fileName'] . '" alt="Image du produit" width="80" onclick="this.nextElementSibling.click()">
                                <input type="file" name="pictures[]" hidden onchange="managePicture(this,' . $pictures[$i]['id'] . ')" accept="image/*">
                                <img src="svg/remove-button.svg" alt="Image du produit" width="20" onclick="resetPictureManagement(this,' . $pictures[$i]['id'] . ')">
                                <input type="hidden" name="idOldPictures[]">
                              </div>';
                    else
                        echo '<div>
                                <img src="svg/defaultPicture.svg" alt="Image du produit" width="80" onclick="this.nextElementSibling.click()">
                                <input type="file" name="pictures[]" hidden onchange="managePicture(this)" accept="image/*">
                                <img src="svg/remove-button.svg" alt="Image du produit" width="20" onclick="resetPicture(this)" hidden>
                                <input type="hidden" name="idOldPictures[]">
                              </div>';
                }
            ?>
        </div>

        <div class="blocInfosProduit">
            <label class="etat">
                <p>État du produit :</p>
                <select name="etat_produit" required>
                    <?php
                        foreach (array('Neuf', 'Comme neuf', 'Très bon état', 'Bon état', 'État correct', 'Mauvais état', 'Hors service', 'Autre') as $etat)
                            echo "<option value=\"" . $etat . "\"" . ($etat == $sale['conditionP'] ? " selected=\"selected\">" : ">") . $etat . "</option>";
                    ?>
                </select>
            </label>

            <label class="categorie">
                <p>Catégorie du produit :</p>
                <select name="categorie_produit" required>
                    <?php
                        foreach (array('Promo', 'Musique', 'Multimédia', 'Santé', 'Électroménager', 'Décoration', 'Bricolage', 'Animaux', 'Autre') as $category)
                            echo "<option value=\"" . $category . "\"" . ($category == $sale['category'] ? " selected=\"selected\">" : ">") . $category . "</option>";
                    ?>
                </select>
            </label>

            <label class="descriptionObj">
                <p>Description :</p>
                <textarea name="description_produit" placeholder="Petite description" maxlength="300" required><?=htmlspecialchars($sale['description'])?></textarea>
            </label>
        </div>
    </div>

    <div id="step2" style="display: none;">
        <div id="externalProducts">
            <h2>Comparer votre offre avec d'autres sites</h2>
            <?php

            $req = $bdd->prepare('SELECT externalProductID FROM externalAssociations WHERE productID = ?');
            $req->execute(array($_GET['saleID']));

            $externalProducts = $req->fetchAll(PDO::FETCH_COLUMN);
            $numberOfExternalProducts = count($externalProducts);

            $getExternalURL = $bdd->prepare('SELECT platform, productID FROM externalProducts WHERE id = ?');
            

            for ($i = 0; $i < $numberOfExternalProducts; $i++) {
                $getExternalURL->execute(array($externalProducts[$i]));
                $infoExternalProduct = $getExternalURL->fetch(PDO::FETCH_ASSOC);

                echo '<div class="externalURL">
                        <p><span class="urlField">Plateforme :</span> ' . $infoExternalProduct['platform'] . '<br><span class="urlField">ID :</span> ' . $infoExternalProduct['productID'] . '</p>
                        <img src="svg/remove-button.svg" width="20" onclick="remove_url_ById(this.parentElement,' . $externalProducts[$i] . ')">
                      </div>';
            }

            if($numberOfExternalProducts < 5)
                echo '<img src="svg/add-button.svg" width="20" onclick="add_url(this)" id="add_url_image">';
            else
                echo '<img src="svg/add-button.svg" width="20" onclick="add_url(this)" id="add_url_image" hidden>';

            echo '<script>nb_url_input = ' . $numberOfExternalProducts . '</script>';
            ?>
        </div>

        <div class="marketPosition">
            <p>Position prix :</p>
            <label>
                Prix plus bas du marché
                <input type="range" name="marketPosition" min="1" max="100" value="<?=$sale['marketPosition']?>" required="" oninput="num.value = this.value">
                Prix plus haute du marché
            </label>
            <p><output id="num"><?=$sale['marketPosition']?></output>%</p>
        </div>

    </div>

    <hr>
    
    <div class="steps">
        <button type="button" id="previousStep" hidden onclick="switchStep()">Étape précédente</button>
        <div id="fakeBlock"></div>
    
        <div class="h-captcha" data-sitekey="e6b60063-290d-4c3b-b52d-5c4a3aba9a7e"></div>
    
        <button type="button" id="nextStep" onclick="switchStep()">Étape suivante</button>
        <input type="submit" id="submitProduct" value="Confirmer" hidden>
    </div>
</form>
