<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/Product/ProductService.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BotService {

    /**
     * 
     * @param type $jsonData
     */
    public static function sendMessage($jsonData) {
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
        return $result;
    }

    public static function getMessageData($isConversation, $sender, $data) {
        $jsonData = "";
        //process conversation
        if ($isConversation) {
            $jsonData = array(
                "recipient" => array(
                    "id" => $sender
                ),
                "message" => array(
                    "text" => $data
                )
            );
        }
        //process attachment
        else {
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

    /**
     * Get one product generic element
     * @param array $product product array 
     * @param Boolean $isItemUrl is the payload has a item url
     * @param String $otherButton button's json string
     * @return array ProductPayload
     */
    public static function getBotProductGenericElement($product, $isItemUrl, $otherButton) {
        if (!isset($otherButton)) {
            //todo
        }
        if ($isItemUrl) {
            $element = array(
                "title" => $product['ptName'],
                "item_url" => "https://www.danielwellington.com/tw",
                "image_url" => 'https://secretwarehousetw.com/resource/img/'.$product['ptImgUrl'],
                "subtitle" => $product['ptDesc'],
                "buttons" =>
                array(
                    array(
                        "type" => "postback",
                        "title" => "加入購物車",
                        "payload" => "BUY_IT_#" . $product['ptNo'],
                    )
                )
            );
        } else {
            $element = array(
                "title" => $product['ptName'],
                "image_url" => 'https://secretwarehousetw.com/resource/img/'.$product['ptImgUrl'],
                "subtitle" => $product['ptDesc'],
                "buttons" =>
                array(
                    array(
                        "type" => "postback",
                        "title" => "加入購物車",
                        "payload" => "BUY_IT_#" . $product['ptNo'],
                    )
                )
            );
        }
        return $element;
    }

    /**
     * Get one bot generic template
     * @param array $elements elements
     * @return array template
     */
    public static function getBotGenericTemplate($elements) {
        $template = array(
            'type' => 'template',
            'payload' =>
            array(
                'template_type' => 'generic',
                'elements' => $elements
            )
        );
        return $template;
    }

}
