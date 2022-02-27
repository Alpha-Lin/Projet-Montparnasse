<?php
// TODO : corriger la recherche
$req = $bdd->prepare('SELECT id, nom, description_produit, vendeur_id, etat FROM produit WHERE nom LIKE ? ORDER BY premium LIMIT 10');
$req->execute(array('%' . $_GET['search'] . '%'));

$produits_research = $req->fetchAll(PDO::FETCH_ASSOC);

if(!empty($produits_research))
{
    require 'php/modules/price.php';

    $req_pseudo_vendeur = $bdd->prepare('SELECT pseudo FROM utilisateur WHERE id = ?');
    $req_produits_externes = $bdd->prepare('SELECT produit_externe_id FROM association_externe WHERE produit_id = ?');
    $req_last_refresh_produit_externe = $bdd->prepare('SELECT last_refresh FROM produit_externe WHERE id = ?');
    $req_prix_externe = $bdd->prepare('SELECT prix FROM produit_externe WHERE id = ?');
    $req_update_final_price = $bdd->prepare('UPDATE produit SET dernier_prix = ? WHERE id = ?');
    
    $temps = time();

    echo '<div class="scrollable">
            <div class="alignProduit">';

    foreach ($produits_research as $produit) { // Chaque produit
        $prix = 0;
        $nb_produits_externes = 0;

        $req_produits_externes->execute(array($produit['id']));

        foreach ($req_produits_externes->fetchAll(PDO::FETCH_ASSOC) as $produit_externe) { // Chaque produit externe du produit
            $req_last_refresh_produit_externe->execute(array($produit_externe['produit_externe_id']));

            if($temps - strtotime($req_last_refresh_produit_externe->fetch(PDO::FETCH_COLUMN)) > 604800) // Si ça fait plus de 1 semaine
                update_price($produit_externe['produit_externe_id']); // Mise à jour du prix du produit externe

            $req_prix_externe->execute(array($produit_externe['produit_externe_id']));

            $prix += $req_prix_externe->fetch(PDO::FETCH_COLUMN);
            $nb_produits_externes++;
        }

        $prix = round($prix / $nb_produits_externes, 2);

        $req_update_final_price->execute(array($prix,
                                               $produit['id'])); // Mise à jour du dernier prix

        $req_pseudo_vendeur->execute(array($produit['vendeur_id']));

        echo '
            <div class="produit">
                <div class="imgObj">
                    <p>IMAGE</p>
                </div>
                <div class="titreObj">
                    <a href="#"><h2>' . htmlspecialchars($produit['nom']) . '</h2></a>
                </div>
                <div class="prixObj">
                    <p>Prix : ' . $prix . '</p>
                </div>
                <div class="etatObj">
                    <p>état : ' . $produit['etat'] . '</p>
                </div>
                <div class="vendeurObj">
                    <p>Vendeur : ' . htmlspecialchars($req_pseudo_vendeur->fetch(PDO::FETCH_COLUMN)) . '</p>
                </div>
                <div class="descObj">
                    <p>Description : ' . htmlspecialchars($produit['description_produit']) . '</p>
                </div>
            </div>'; // TODO : envoyer le dernier prix pour montrer l'évolution
    }

    echo '</div></div>';
}else
    echo '<p>Aucun résultat trouvé.</p>';

?>
