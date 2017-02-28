<?php
require_once __DIR__ . '/Facebook/autoload.php';
session_start();
$fb = new Facebook\Facebook([
    'app_id' => '1299306096781980', // Replace {app-id} with your app id
    'app_secret' => '480995be3368b1cf8972eac9f96c12a4',
    'default_graph_version' => 'v2.2',
        ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://www.secretwarehousetw.com/botservice/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">登入秘密倉庫小精靈服務！</a>';
?>
