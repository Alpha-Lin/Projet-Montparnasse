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
            "x-rapidapi-key: bac31d043bmsh64187a8ec6bade0p15149cjsndfa282c1eeb7"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if($err)
        return false;
    else
    {
        return json_decode($response, true);
    }
}

function get_price($plateforme, $id, $marche = 'N/A')
{
    switch($plateforme)
    {
        case "AMAZON": // class : apexPriceToPay
            $res = curl_sample("https://amazon-product-price-data.p.rapidapi.com/product?asins=" . $id . "&locale=" . $marche, "amazon-product-price-data.p.rapidapi.com");

            if($res === false)
                return false;

            return $res[0]['current_price'];
        case "EBAY":
            return false;
        case "LEBONCOIN": // class : Roh2X _3gP8T _25LNb _35DXM ou json avec clé "price"
            return false;
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
                return false;
            }
        case 'ALIEXPRESS':
            $res = curl_sample("https://aliexpress19.p.rapidapi.com/products/" . $id . "?countryCode=FR", "aliexpress19.p.rapidapi.com");

            if($res === false | !array_key_exists('skuList', $res))
            {
                $res = curl_sample("https://ali-express1.p.rapidapi.com/product/" . $id . "?language=fr", "ali-express1.p.rapidapi.com");

                if($res === false | !array_key_exists('priceModule', $res))
                {
                    $res = curl_sample("https://aliexpress-unofficial.p.rapidapi.com/product/" . $id . "?country=FR&currency=EUR&locale=FR_FR", "aliexpress-unofficial.p.rapidapi.com");

                    if($res === false | !array_key_exists('prices', $res))
                        return false;

                    return $res['prices']['min']['value']; // Dollar
                }

                return substr($res['priceModule']['formatedActivityPrice'], 4, strlen($res['priceModule']['formatedPrice']) - 3); // Dollar
            }

            return $res['skuList'][0]['activityPrice'];
        default:
            return false;
    }
}

function update_price($plateforme, $id, $marche = null)
{
    $req = $bdd->prepare('UPDATE produit_externe SET prix = ? WHERE plateforme = ? AND id = ? AND marche = ?');
    $req->execute(array($plateforme,
                        $id,
                        $marche,
                        get_price($plateforme, $id, $marche)));
}

//echo get_price('ALIEXPRESS', 1005002745180207);
