<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/config.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BotService {


    static function sendMessage($jsonData) {
//API Url
        $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=' . $GLOBALS['access_token'];
//Initiate cURL.
        $ch = curl_init($url);
//Encode the array into JSON.
        $jsonDataEncoded = json_encode($jsonData);
//Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//Execute the request
        $result = curl_exec($ch);
    }

   static function getJsonData($isConversation, $sender, $data) {
        $jsonData = "";
        if ($isConversation) {
            //process conversation
            $jsonData = array(
                "recipient" => array(
                    "id" => $sender
                ),
                "message" => array(
                    "text" => $data
                )
            );
        } else {
            //process attachment
            $jsonData = array(
                'recipient' =>
                array(
                    'id' => $sender
                ),
                'message' =>
                array(
                    'attachment' => $data
                ),
            );
        }
        return $jsonData;
    }

}
