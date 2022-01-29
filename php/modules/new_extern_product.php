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

function add_extern_product_in_db($plateforme, $id, $marche = null)
{
    $req = $bdd->prepare('INSERT INTO produit_externe(plateforme, id, marche, prix) VALUE (?, ?, ?, ?)');
    $req->execute(array($plateforme,
                        $id,
                        $marche,
                        get_price($plateforme, $id, $marche))
    );
}

function extract_infos_product($url)
{
    $url_host = parse_url($url, PHP_URL_HOST); // Obtention plateforme
    switch($url_host)
    {
        case (preg_match('/www.amazon.(?:com|fr|ca|co\.uk|com|in)$/', $url_host) ? true : false):
            $dp = strpos($url, '/dp/'); // Obtention ID

            if($dp === false | strlen($url) < 35) // Taille minimum
                return false;

            $dp = substr($url, $dp + 4, 10);

            // Obtention marché
            $marche = explode('.', $url_host);
            
            switch(end($marche))
            {
                case 'fr':
                    add_extern_product_in_db('AMAZON', $dp, 'FR');
                    break;
                case 'com':
                    add_extern_product_in_db('AMAZON', $dp, 'US');
                    break;
                case 'uk':
                    add_extern_product_in_db('AMAZON', $dp, 'UK');
                    break;
                case 'in':
                    add_extern_product_in_db('AMAZON', $dp, 'IN');
                    break;
                case 'ca':
                    add_extern_product_in_db('AMAZON', $dp, 'CA');
                    break;
                default:
                    return false;
            }

            return true;
        case (preg_match('/www.ebay.(?:com|fr|ca|co\.uk|com|de|es|ch|it|nl|ie|pl)$/', $url_host) ? true : false):
            $itm = strpos($url, '/itm/'); // Obtention ID

            if($itm === false | strlen($url) < 36) // Taille minimum
                return false;

            $itm = substr($url, $itm + 5, 12);

            if(ctype_digit($itm) === false) // Vérification que l'id est composé uniquement de chiffres
                return false;

            add_extern_product_in_db('EBAY', $itm);
            return true;
        case "www.leboncoin.fr":
            $id = strpos($url, '.h'); // Obtention ID

            if($id === false)
                return false;

            $id = substr($url, $id - 10, 10);

            if(ctype_digit($id) === false) // Vérification que l'id est composé uniquement de chiffres
                return false;

            add_extern_product_in_db('LEBONCOIN', $id);
            return true;
        case "www.cdiscount.com":
            if(strlen($url) < 107) // Taille minimum
                return false;

            add_extern_product_in_db('CDISCOUNT', $url);
            return true;
        case (preg_match('/\.aliexpress\.com$/', $url_host) ? true : false):
            $item = strpos($url, '/item/'); // Obtention ID

            if($item === false || strlen($url) < 47) // Taille minimum
                return false;

            add_extern_product_in_db('ALIEXPRESS', substr($url, $item + 6, 16));
            return true;
        case "www.materiel.net":
            $produit = strpos($url, 'u'); // Obtention ID

            if($produit === false || strlen($url) < 51) // Taille minimum
                return false;

            add_extern_product_in_db('MATERIEL.NET', substr($url, $produit + 4, 12));
            return true;
        case "www.ldlc.com":
            $fiche = strpos($url, 'P'); // Obtention ID

            if($fiche === false | strlen($url) < 42) // Taille minimum
                return false;

            add_extern_product_in_db('LDLC', substr($url, $fiche, 10));
            return true;
        case "www.topachat.com":
            if(strlen($url) < 60) // Taille minimum
                return false;

            add_extern_product_in_db('TOPACHAT', substr($url, 47, strlen($url) - 47));
            return true;
        default:
            return false;
    }
}
