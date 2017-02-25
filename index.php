<?php
/**
 * Webhook for Facebook Messenger Bot
 * User: Master
 * Date: 10/26/16
  */
 require_once 'config.php';
 
$hub_verify_token = null;

if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}


if ($hub_verify_token === $verify_token) {
    echo $challenge;
}
$input = json_decode(file_get_contents('php://input'), true);
// file_put_contents ("fb.txt",file_get_contents('php://input'),FILE_APPEND);
//$pageid= $input['entry'][0]['id'];

$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$payload = null;
$isText = True;
$message_to_reply="";
if($message != ""){
	/**
	*	handle postback
	*/
	$message_to_reply = $message;
}else{
	/**
	*	handle postback
	*/
	$payload = $input['entry'][0]['messaging'][0]['postback']['payload'];
	switch ($payload) {
	case 'Get Started':
		$attachment = 	array (
	      'type' => 'template',
	      'payload' => 
		    array (
		        'template_type' => 'generic',
		        'elements' => 
		        array (
		          array (
		            "title" => "嗨！我是麥斯特的機器人賈維斯",
					"item_url" => "http://www.iammaster.tw",
					"image_url" => "http://robot.iammaster.tw/getstart.jpg",
					"subtitle" => "不曉得我能否為您效勞的嗎？",
		            "buttons" => 
			            array (
			            	array (
			                "type" => "postback",
							"title" => "來段自我介紹吧",
							"payload" => "BOT_INTRODUCTION",
			              ),
			              array (
			                "type" => "web_url",
							"url" => "http://www.iammaster.tw",
							"title" => "拜訪麥斯特網站"
			              ),
			              array (
			                "type" => "postback",
			                "title" => "聊天吧",
			                "payload" => "CHATTING",
			              )
			            )
		          	)
		        )
		    )
	    );	
	 	$isText = false;
	break;
	case 'BOT_INTRODUCTION':
		$message_to_reply = "哈囉！我是麥斯特的機器人賈維斯，協助他在不在位置上直接回答您問題！你可以從聊天的輸入框旁的三插入點獲得更多功能！";
	break;
	case 'BOT_SHOP':
		$attachment = 	array (
	      'type' => 'template',
	      'payload' => 
		    array (
		        'template_type' => 'generic',
		        'elements' => 
		        array (
		          array (
		            "title" => "DanielWellington Classic Black Durham",
					"item_url" => "https://www.danielwellington.com/tw",
					"image_url" => "http://robot.iammaster.tw/assets/image/DW.png",
					"subtitle" => "Classic Black Durham有著漂亮的黑色錶盤、淡棕色美國真皮錶帶，絕對是真正收藏家的首選。經過植物油處理，Durham錶帶有著獨特的變色效果，能形成非常個性的精美光澤。",
		            "buttons" => 
			            array (
			            	array (
			                "type" => "postback",
							"title" => "購買",
							"payload" => "BUY_IT_WATCH",
			              ),
			              array (
			                "type" => "web_url",
							"url" => "https://www.danielwellington.com/tw",
							"title" => "拜訪官方網站"
			              )
			            )
		          	),
				array (
		            "title" => "MacBook",
					"item_url" => "http://www.apple.com/tw/shop/buy-mac/macbook",
					"image_url" => "http://robot.iammaster.tw/assets/image/mac.PNG",
					"subtitle" => "我們相信，要有絕佳的筆記型電腦體驗，舒適的全尺寸鍵盤不可或缺。但要將這樣的鍵盤放進優雅纖薄的 MacBook，就必須重新思考鍵盤的設計與結構。我們重新設計每個按鍵與下方結構，不只讓整個鍵盤更薄，打起字來也更舒適、精準、靈敏，帶來優異的使用感受。",
		            "buttons" => 
			            array (
			            	array (
			                "type" => "postback",
							"title" => "購買",
							"payload" => "BUY_IT_MAC",
			              )
			            )
		          	),
		        )
		    )
	    );	
		$isText = false;
	break;
	case 'CHATTING':
		$message_to_reply = $payload;
	break;
	}
}

//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;


//Initiate cURL.
$ch = curl_init($url);

//The JSON data.

if($isText){
	$jsonData = array(
	"recipient" => array(
		"id" => $sender
		),
	"message" => array(
		"text" =>$message_to_reply
		)
	);
}else{
	$jsonData = array (
	  'recipient' => 
	  array (
	    'id' => $sender
	  ),
	  'message' => 
	  array (
	    'attachment' => $attachment
	    
	  ),
	);

}

 

//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);
// $jsonDataEncoded = $jsonData;

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    $result = curl_exec($ch);
}else{
    $result = curl_exec($ch);
    
}


?>