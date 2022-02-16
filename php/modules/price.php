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

function curl_sample($url, $api_host)
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
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: " . $api_host,
            "x-rapidapi-key: " . json_decode(file_get_contents('api_tokens.json'), true)['RAPIDAPI_KEY']
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if($err)
        return ERROR_URL;
    else
    {
        return json_decode($response, true);
    }
}

define('PRICE_404', '-3');
define('CDISCOUNT_TOKEN', '-5');

function get_price($plateforme, $id, $marche = 'N/A')
{
    switch($plateforme)
    {
        case "AMAZON": // class : apexPriceToPay
            $res = curl_sample("https://amazon-product-price-data.p.rapidapi.com/product?asins=" . $id . "&locale=" . $marche, "amazon-product-price-data.p.rapidapi.com");

            if($res === ERROR_URL)
                return ERROR_URL;

            return $res[0]['current_price'];
        case "EBAY":
            return PRICE_404;
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
            $res = curl_sample("https://aliexpress19.p.rapidapi.com/products/" . $id . "?countryCode=FR", "aliexpress19.p.rapidapi.com");

            if($res === ERROR_URL || !array_key_exists('skuList', $res))
            {
                $res = curl_sample("https://ali-express1.p.rapidapi.com/product/" . $id . "?language=fr", "ali-express1.p.rapidapi.com");

                if($res === ERROR_URL || !array_key_exists('priceModule', $res))
                {
                    $res = curl_sample("https://aliexpress-unofficial.p.rapidapi.com/product/" . $id . "?country=FR&currency=EUR&locale=FR_FR", "aliexpress-unofficial.p.rapidapi.com");

                    if($res === ERROR_URL || !array_key_exists('prices', $res))
                        return ERROR_URL;

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

    $req = $bdd->prepare('SELECT plateforme, produit_id, marche FROM produit_externe WHERE id = ?');
    $req->execute(array($id));

    $produit_externe = $req->fetch(PDO::FETCH_ASSOC);

    $prix = get_price($produit_externe['plateforme'], $produit_externe['produit_id'], $produit_externe['marche']);

    if($prix < 0)
        return;

    $req = $bdd->prepare('UPDATE produit_externe SET prix = ?, last_refresh = NOW() WHERE id = ?'); // On met à jour le prix et la datetime de refresh
    $req->execute(array($prix,
                        $id));
}

//echo get_price('ALIEXPRESS', 1005002745180207);
