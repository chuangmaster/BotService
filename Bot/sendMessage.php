<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/config.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




/**
 * to send message to facebook woohook
 * @param String $jsonData json string
 */
function sendMessage($jsonData) {
    //API Url
    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . $GLOBALS['access_token'] ;

//Initiate cURL.
    $ch = curl_init($url);
    //Encode the array into JSON.
    $jsonDataEncoded = json_encode($jsonData);
// $jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//Execute the request
    $result = curl_exec($ch);
}
