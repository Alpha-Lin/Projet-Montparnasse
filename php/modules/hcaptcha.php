<?php

function hcaptcha($captcha_response)
{
    $data = array(
        'secret' => json_decode(file_get_contents('api_tokens.json'), true)['HCAPTCHA_KEY'],
        'response' => $captcha_response
    );
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);

    return json_decode($response)->success;
}

?>
