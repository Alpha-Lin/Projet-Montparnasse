<?php
/* Exemples : */
// Cdiscount :
//$url_test = "https://www.cdiscount.com/high-tech/casques-baladeur-hifi/casque-hi-res-sans-fil-bluetooth-a-reduction-de-br/f-1065420-son4548736132535.html"; // Doit avoir un protocole au début
//$url_test = "https://www.cdiscount.com/telephonie/accessoires-portable-gsm/samsung-galaxy-buds-noir/f-14420110101-sam8806090196461.html";
//$url_test = "https://www.cdiscount.com/telephonie/telephone-mobile/samsung-galaxy-s21-fe-128go-graphite/f-1440426-samgalaxys21feg.html";
//$url_test = "https://www.cdiscount.com/electromenager/refrigerateur-congelateur/hisense-fcd315ace-refrigerateur-congelateur-bas/f-110030901-his3838782525179.html";
// Leboncoin :
//$url_test = "https://www.leboncoin.fr/telephonie/2106212825.htm";
// Amazon :
//$url_test = "https://www.amazon.fr/Sony-WF-1000XM4-Ecouteurs-Bluetooth-R%C3%A9duction/dp/B095DNPH4R/";
//$url_test = "https://www.amazon.fr/Jabra-75t-%C3%89couteurs-Bluetooth-Wireless/dp/B083741F79/ref=psdc_14054961_t3_B095DNPH4R";
//$url_test = "https://www.amazon.com/BENGOO-G9000-Controller-Cancelling-Headphones/dp/B01H6GUCCQ/";
//$url_test = "https://www.amazon.in/Fresh-Potato-1kg-Pack/dp/B07HN2PC1F";
//$url_test = "https://www.amazon.co.uk/2021-Apple-iPad-10-2-inch-Wi-Fi/dp/B09G968MFZ/";
//$url_test = "https://www.amazon.ca/dp/B079NKQ2H7/";

require 'price.php';

define('BAD_MARKET', '-2');
define('PLATEFORM_NOT_FOUND', '-1');

function add_extern_product_in_db($produit_id, $plateforme, $id, $marche = null)
{
    $prix = get_price($plateforme, $id, $marche);

    if($prix < 0)
        return $prix;

    global $bdd;

    $req = $bdd->prepare('INSERT INTO produit_externe(plateforme, produit_id, marche, prix) VALUE (?, ?, ?, ?)'); // Ajoute le produit externe dans la bdd
    $req->execute(array($plateforme,
                        $id,
                        $marche,
                        $prix)
    );

    $req = $bdd->prepare('INSERT INTO association_externe(produit_id, produit_externe_id) VALUE (?, ?)'); // Associe le nouveau produit externe avec le produit principal
    $req->execute(array($produit_id,
                        $bdd->lastInsertId())
    );

    return $prix;
}

function extract_infos_product($url, $produit_id)
{
    $url_host = parse_url($url, PHP_URL_HOST); // Obtention plateforme
    switch($url_host)
    {
        case (preg_match('/www.amazon.(?:com|fr|ca|co\.uk|com|in)$/', $url_host) ? true : false):
            $dp = strpos($url, '/dp/'); // Obtention ID

            if($dp === false || strlen($url) < 35) // Taille minimum
                return ERROR_URL;

            $dp = substr($url, $dp + 4, 10);

            // Obtention marché
            $marche = explode('.', $url_host);
            
            switch(end($marche))
            {
                case 'fr':
                    return add_extern_product_in_db($produit_id, 'AMAZON', $dp, 'FR');
                case 'com':
                    return add_extern_product_in_db($produit_id, 'AMAZON', $dp, 'US');
                case 'uk':
                    return add_extern_product_in_db($produit_id, 'AMAZON', $dp, 'UK');
                case 'in':
                    return add_extern_product_in_db($produit_id, 'AMAZON', $dp, 'IN');
                case 'ca':
                    return add_extern_product_in_db($produit_id, 'AMAZON', $dp, 'CA');
                default:
                    return BAD_MARKET;
            }
        case (preg_match('/www.ebay.(?:com|fr|ca|co\.uk|com|de|es|ch|it|nl|ie|pl)$/', $url_host) ? true : false):
            $itm = strpos($url, '/itm/'); // Obtention ID

            if($itm === false || strlen($url) < 36) // Taille minimum
                return ERROR_URL;

            $itm = substr($url, $itm + 5, 12);

            if(ctype_digit($itm) === false) // Vérification que l'id est composé uniquement de chiffres
                return ERROR_URL;

            return add_extern_product_in_db($produit_id, 'EBAY', $itm);
        case "www.leboncoin.fr":
            $id = strpos($url, '.h'); // Obtention ID

            if($id === false)
                return ERROR_URL;

            $id = substr($url, $id - 10, 10);

            if(ctype_digit($id) === false) // Vérification que l'id est composé uniquement de chiffres
                return ERROR_URL;

            return add_extern_product_in_db($produit_id, 'LEBONCOIN', $id);
        case "www.cdiscount.com":
            if(strlen($url) < 107) // Taille minimum
                return ERROR_URL;

            $end_url = strpos($url, '?');

            if($end_url !== false)
                $url = substr($url, 0, $end_url);

            return add_extern_product_in_db($produit_id, 'CDISCOUNT', $url);
        case (preg_match('/\.aliexpress\.com$/', $url_host) ? true : false):
            $item = strpos($url, '/item/'); // Obtention ID

            if($item === false || strlen($url) < 47) // Taille minimum
                return ERROR_URL;

            $end_item = strpos($url, '.h');

            return add_extern_product_in_db($produit_id, 'ALIEXPRESS', substr($url, $item + 6, $end_item - $item - 6));
        case "www.materiel.net":
            $produit = strpos($url, 'u'); // Obtention ID

            if($produit === false || strlen($url) < 51) // Taille minimum
                return ERROR_URL;

            return add_extern_product_in_db($produit_id, 'MATERIEL.NET', substr($url, $produit + 4, 12));
        case "www.ldlc.com":
            $fiche = strpos($url, 'P'); // Obtention ID

            if($fiche === false || strlen($url) < 42) // Taille minimum
                return ERROR_URL;

            return add_extern_product_in_db($produit_id, 'LDLC', substr($url, $fiche, 10));
        case "www.topachat.com":
            if(strlen($url) < 60) // Taille minimum
                return ERROR_URL;

            return add_extern_product_in_db($produit_id, 'TOPACHAT', substr($url, 47, strlen($url) - 47));
        default:
            return PLATEFORM_NOT_FOUND;
    }
}
