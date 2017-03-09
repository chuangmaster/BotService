<?php

// 
/**
 * Webhook for Facebook Messenger Bot
 * User: Master
 * Date: 10/26/16
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/Bot/BotService.php';

//Recevice 
if (!empty($_REQUEST["hub_mode"]) && $_REQUEST["hub_mode"] == "subscribe" && $_REQUEST["hub_verify_token"] === $GLOBALS["verify_token"]) {
    //process webhook identified
    $challenge = $_REQUEST["hub_challenge"];
    echo $challenge;
} else {
    //process other event
    $data = json_decode(file_get_contents('php://input'), true);
    //file_put_contents("fb.txt", file_get_contents('php://input'), FILE_APPEND);
    if (!empty($data["entry"][0]["messaging"])) {
        foreach ($data["entry"][0]["messaging"] as $message) {
            //skipping delivery messages
            if (!empty($message["delivery"])) {
                continue;
            }

            $command = "";
            $sender = $message["sender"]["id"];
            $orderPtno = "";
            //set command
            error_log(json_decode($message));
            if (!empty($message["message"])) {
                //set command to send text
                $command = "text";
            } else if (!empty($message["postback"])) {
                //set command to send postback 
                $command = $message["postback"]["payload"];
                //process order commad 
                if ("BUY_IT" === substr($command, 0, 6)) {
                    $orderPtno = substr($command, 8);
                    $command = "BUY_IT";
                }
                BotService::sendMessage(BotService::getMessageData(TRUE, $sender, $orderPtno));
            }

            switch ($command) {
                case "Get Started":
                    break;
                case "text":
                    //檢查#
                    $isSPNo = strpos($message["message"]["text"], "#");
                    if ($isSPNo === 0 && $isSPNo !== "") {
                        $product = ProductService::getProduct(substr($message["message"]["text"], 1));
                        //file_put_contents("debug2.txt", $product, FILE_APPEND);
                        if (empty($product)) {
                            $message_to_reply = "找不到您輸入商品編號(" . $message["message"]["text"] . ")，重新輸入#商品編號，例如#SP20170304001";
                            BotService::sendMessage(BotService::getMessageData(TRUE, $sender, $message_to_reply));
                        } else {
                            $message_to_reply = "這是你要購買的商品:";
                            BotService::sendMessage(BotService::getMessageData(TRUE, $sender, $message_to_reply));
                            $botProductGenericElement = BotService::getBotProductGenericElement($product, FALSE, null);
                            $message_to_reply = BotService::getBotGenericTemplate(array($botProductGenericElement));
                            BotService::sendMessage(BotService::getMessageData(FALSE, $sender, $message_to_reply));
                        }
                    } else {
                        $message_to_reply = "!找不到您輸入的商品，請確認商品是否存在，或重新輸入#商品編號，例如#SP20170304001";
                        BotService::sendMessage(BotService::getMessageData(TRUE, $sender, $message_to_reply));
                    }
                    break;
                case 'BOT_INTRODUCTION':
                    $message_to_reply = "哈囉！我是麥斯特的機器人賈維斯，協助他在不在位置上直接回答您問題！你可以從聊天的輸入框旁的三插入點獲得更多功能！";
                    BotService::sendMessage(BotService::getMessageData(TRUE, $sender, $message_to_reply));
                    break;
                case 'BOT_SHOPPING':
                    $message_to_reply = "請問你要購買的商品編號是?(請輸入#商品編號，例如#SP20170304001)";
                    BotService::sendMessage(BotService::getMessageData(TRUE, $sender, $message_to_reply));
                    break;
                case 'BOT_SHOP':
                    $message_to_reply = array(
                        'type' => 'template',
                        'payload' =>
                        array(
                            'template_type' => 'generic',
                            'elements' =>
                            array(
                                array(
                                    "title" => "DanielWellington Classic Black Durham",
                                    "item_url" => "https://www.danielwellington.com/tw",
                                    "image_url" => "http://robot.iammaster.tw/assets/image/DW.png",
                                    "subtitle" => "Classic Black Durham有著漂亮的黑色錶盤、淡棕色美國真皮錶帶，絕對是真正收藏家的首選。經過植物油處理，Durham錶帶有著獨特的變色效果，能形成非常個性的精美光澤。",
                                    "buttons" =>
                                    array(
                                        array(
                                            "type" => "postback",
                                            "title" => "購買",
                                            "payload" => "BUY_IT_WATCH",
                                        ),
                                        array(
                                            "type" => "web_url",
                                            "url" => "https://www.danielwellington.com/tw",
                                            "title" => "拜訪官方網站"
                                        )
                                    )
                                ),
                                array(
                                    "title" => "MacBook",
                                    "item_url" => "http://www.apple.com/tw/shop/buy-mac/macbook",
                                    "image_url" => "http://robot.iammaster.tw/assets/image/mac.PNG",
                                    "subtitle" => "我們相信，要有絕佳的筆記型電腦體驗，舒適的全尺寸鍵盤不可或缺。但要將這樣的鍵盤放進優雅纖薄的 MacBook，就必須重新思考鍵盤的設計與結構。我們重新設計每個按鍵與下方結構，不只讓整個鍵盤更薄，打起字來也更舒適、精準、靈敏，帶來優異的使用感受。",
                                    "buttons" =>
                                    array(
                                        array(
                                            "type" => "postback",
                                            "title" => "購買",
                                            "payload" => "BUY_IT_MAC",
                                        )
                                    )
                                ),
                            )
                        )
                    );
                    BotService::sendMessage(BotService::getMessageData(FALSE, $sender, $message_to_reply));
                    break;
                case "BUY_IT":
                    BotService::sendMessage(BotService::getMessageData(TRUE, $sender, "商品編號{$orderPtno}已被加入購物車"));
                    
                    //to do add carts
                    break;
                default :
                    $message_to_reply = "!!!!!!!";
                    BotService::sendMessage(BotService::getMessageData(TRUE, $sender, $message_to_reply));
                    break;
                //end of switch
            }

            //end of foreach
        }
    }
}
?>
