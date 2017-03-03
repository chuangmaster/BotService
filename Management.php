<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/botservice/product/ProductService.php';

        $message = "false";


        $attachment = array(
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
                                "title" => "加入購物車",
                                "payload" => "BUY_IT_WATCH",
                            )
                        )
                    )
                )
            )
        );
        $rows = getProduct("SP170228001");
        echo $rows['ptName'];
        ?>
    </body>
</html>
