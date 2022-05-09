<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function reload_cdiscount_token($url)
{
    $page_token = null;

    exec('wget ' . $url . ' -U "' . generateRandomString() .'" -qO-', $page_token);

    $page_token = implode($page_token);

    $token_pos = strpos($page_token, 'JmNaL4p84LHEfek6eFD6L');

    if($token_pos !== false)
    {
        $token = substr($page_token, $token_pos, 107); // Token
        file_put_contents('tokens/token_cdiscount.txt', $token);
    }
}

define('ERROR_URL', '-4');

function curl_rapidapi($url, $api_host)
{
    return curl_sample($url, [
            "x-rapidapi-host: " . $api_host,
            "x-rapidapi-key: " . json_decode(file_get_contents('api_tokens.json'), true)['RAPIDAPI_KEY']
    ]);
}

function curl_sample($url, $header = [])
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $header,
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if($err)
        return ERROR_URL;

    if(empty($header))
        return $response;

    return json_decode($response, true);
}

define('PLATEFORM_NOT_FOUND', '-1');
define('PRICE_404', '-3');
define('CDISCOUNT_TOKEN', '-5');
define('EBAY_KEYWORD', '-6');
define('ARTICLE_REMOVED', '-7');

function get_price($plateforme, $id, $marche = 'N/A')
{
    switch($plateforme)
    {
        case "AMAZON": // class : apexPriceToPay
            $res = curl_rapidapi("https://amazon-product-price-data.p.rapidapi.com/product?asins=" . $id . "&locale=" . $marche, "amazon-product-price-data.p.rapidapi.com");

            if($res === ERROR_URL)
                return ERROR_URL;

            return $res[0]['current_price'];
        case "EBAY":
            $page_price = curl_sample($id);

            if($page_price === ERROR_URL)
                return ERROR_URL;

            $pos_price = strpos($page_price, 'prc');

            if($pos_price === false)
                return EBAY_KEYWORD;

            if($page_price[$pos_price + 7] === '_') // Si le produit est en mode enchère
                $pos_price += 44;
            else
                $pos_price += 65;

            $end_pos_price = strpos($page_price, '"', $pos_price);

            return substr($page_price, $pos_price, $end_pos_price - $pos_price);
        case "LEBONCOIN": // class : Roh2X _3gP8T _25LNb _35DXM ou json avec clé "price"
            return PRICE_404;
        case "CDISCOUNT":
            /* Infos utiles :
            - La connexion nécessite un token qui est chargé lors de la première requête
            - Il y a protection sur les user-agents, il faut donc les régénérer régulièrement
            - Réutiliser le même token sur plusieurs requête permet de contourner une partie de la protection

            Des optimisations sont peut-être possibles mais le processus actuel fonctionne
            */
            $token = file_get_contents('tokens/token_cdiscount.txt');

            $page_price = null;
            exec('wget ' . $id . '?challenge=' . $token . ' -U "' . generateRandomString() .'" -qO-', $page_price); // Page produit

            $page_price = implode($page_price);

            $token_pos = strpos($page_price, 'JmNaL4p84LHEfek6eFD6L');

            if($token_pos !== false)
            {
                $token = substr($page_price, $token_pos, 107); // Token
                file_put_contents('tokens/token_cdiscount.txt', $token);
                exec('wget ' . $id . '?challenge=' . $token . ' -U "' . generateRandomString() . '" -qO-', $page_price); // Page produit
                $page_price = implode($page_price);
            }

            // Après crack - get price

            $position_prix_debut = strpos($page_price, 'ce" co');
            if($position_prix_debut !== false)
            {
                $position_prix_debut += 13;
                        $position_prix_fin = 1;

                while ($page_price[$position_prix_debut + $position_prix_fin] != '"')
                    $position_prix_fin++;

                return substr($page_price, $position_prix_debut, $position_prix_fin);
            }else {
                reload_cdiscount_token($id);
                return CDISCOUNT_TOKEN;
            }
        case 'ALIEXPRESS':
            $res = curl_rapidapi("https://aliexpress19.p.rapidapi.com/products/" . $id . "?countryCode=FR", "aliexpress19.p.rapidapi.com");

            if($res === ERROR_URL || !array_key_exists('skuList', $res))
            {
                if(isset($res['details']) && substr($res['details'], 0, 17) == "Not found product")
                    return ARTICLE_REMOVED;

                $res = curl_rapidapi("https://ali-express1.p.rapidapi.com/product/" . $id . "?language=fr", "ali-express1.p.rapidapi.com");

                if(is_null($res))
                        return ARTICLE_REMOVED;

                if($res === ERROR_URL || !array_key_exists('priceModule', $res))
                {
                    $res = curl_rapidapi("https://aliexpress-unofficial.p.rapidapi.com/product/" . $id . "?country=FR&currency=EUR&locale=FR_FR", "aliexpress-unofficial.p.rapidapi.com");

                    if($res === ERROR_URL || !array_key_exists('prices', $res)){
                        if($res['error'] == "Item not found")
                            return ARTICLE_REMOVED;

                        return ERROR_URL;
                    }

                    return $res['prices']['min']['value']; // Dollar
                }

                return substr($res['priceModule']['formatedActivityPrice'], 4, strlen($res['priceModule']['formatedPrice']) - 3); // Dollar
            }

            return $res['skuList'][0]['activityPrice'];
        default:
            return PLATEFORM_NOT_FOUND;
    }
}

function update_price($id)
{
    global $bdd;

    $req = $bdd->prepare('SELECT platform, productID, market FROM externalProducts WHERE id = ?');
    $req->execute(array($id));

    $produit_externe = $req->fetch(PDO::FETCH_ASSOC);

    $prix = get_price($produit_externe['platform'], $produit_externe['productID'], $produit_externe['market']);

    if($prix < 0)
        return;

    $req = $bdd->prepare('UPDATE externalProducts SET price = ?, lastRefresh = NOW() WHERE id = ?'); // On met à jour le prix et la datetime de refresh
    $req->execute(array($prix,
                        $id));
}

function calculPrixMarketPosition($prix_array, $tailleArray, $position)
{
    if($tailleArray == 1)
        return $prix_array[0] * $position / 100;

    sort($prix_array);

    $chemin_prix = array();

    for($prixIndex = 0; $prixIndex < $tailleArray - 1; $prixIndex++) // On calcul les distances qui composent le chemin
        $chemin_prix[] = sqrt(1 + pow($prix_array[$prixIndex + 1] - $prix_array[$prixIndex], 2));

    $position_prix_chemin = array_sum($chemin_prix) * $position / 100;

    $balade_prix = 0;

    for($prixIndex = 1; $prixIndex < $tailleArray; $prixIndex++){
        $balade_prix += $chemin_prix[$prixIndex - 1]; // Commence à partir du 2nd indice

        if($position_prix_chemin <= $balade_prix){ // On vient de trouver l'intervalle dans lequel le prix se trouve, on doit calculer la fonction affine de l'intervalle (Yb - Ya) / (Xb - Xa)
            $pente = $prix_array[$prixIndex] - $prix_array[$prixIndex - 1]; // Xb - Xa vaut tjrs 1

            // On corrige le position du prix voulu sur le chemin pour coller au chemin trouvé
            for($i = 0; $i < $prixIndex - 1; $i++)
                $position_prix_chemin -= $chemin_prix[$i];

            $abscisse_x = $position_prix_chemin / $chemin_prix[$prixIndex - 1] + $prixIndex;// Un chemin étant de longeur d'abscisse 1, la valeur sera comprise entre 0 et 1

            return $pente * $abscisse_x + $prix_array[$prixIndex - 1] - $pente * $prixIndex; // ax + b
        }
    }
}

function reloadExternalPrices($produit, $temps){
    if($produit['saleStatus'] == 1)
        return $produit['lastPrice'];

    $prix_array = array();

    global $bdd;

    $req_produits_externes = $bdd->prepare('SELECT externalProductID FROM externalAssociations WHERE productID = ?');
    $req_prix_externe = $bdd->prepare('SELECT price FROM externalProducts WHERE id = ?');
    $req_last_refresh_produit_externe = $bdd->prepare('SELECT lastRefresh FROM externalProducts WHERE id = ?');
    $req_update_final_price = $bdd->prepare('UPDATE products SET lastPrice = ? WHERE id = ?');

    $req_produits_externes->execute(array($produit['id']));

    foreach ($req_produits_externes->fetchAll(PDO::FETCH_ASSOC) as $produit_externe) { // Chaque produit externe du produit
        $req_last_refresh_produit_externe->execute(array($produit_externe['externalProductID']));

        if($temps - strtotime($req_last_refresh_produit_externe->fetch(PDO::FETCH_COLUMN)) > 604800) // Si ça fait plus de 1 semaine
            update_price($produit_externe['externalProductID']); // Mise à jour du prix du produit externe

        $req_prix_externe->execute(array($produit_externe['externalProductID']));

        $prix_array[] = $req_prix_externe->fetch(PDO::FETCH_COLUMN);
    }

    $prix = calculPrixMarketPosition($prix_array, count($prix_array), $produit['marketPosition']);

    $req_update_final_price->execute(array($prix,
                                            $produit['id'])); // Mise à jour du dernier prix

    return $prix;
}

//echo get_price('ALIEXPRESS', 1005002745180207);
