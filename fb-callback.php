<?php

require_once __DIR__ . '/Facebook/autoload.php';
require_once 'config.php';
require_once 'DBConect.php';
session_start();
$fb = new Facebook\Facebook([
    'app_id' => '1299306096781980', // Replace {app-id} with your app id
    'app_secret' => '480995be3368b1cf8972eac9f96c12a4',
    'default_graph_version' => 'v2.2',
        ]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
} else {
    // Logged in
    echo '<h3>Access Token</h3>';
    try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $fb->get('/me?fields=id,name,email,gender,birthday', $accessToken);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    echo '<h3>Long-lived</h3>';
    //var_dump($accessToken->getValue());
    $user = $response->getGraphUser();

    $fName = $user['name'];
    $fID = $user['id'];
    $fEmail = $user['email'];
    $fGender = $user['gender'];
    echo 'Name: ' . $fName . '<br>';
    echo 'id: ' . $fID . '<br>';
    echo 'email: ' . $fEmail . '<br>';
    echo 'gender: ' . $fGender . '<br>';

    
    $sql = "INSERT INTO Customer (fID, fName, fMail,fGender)
    VALUES ('" . $fID . "','" . $fName . "','" . $fEmail . "','" . $fGender . "')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    echo 'test';
    $conn->close();
}
/*



  var_dump($accessToken->getValue());

  // The OAuth 2.0 client handler helps us manage access tokens
  $oAuth2Client = $fb->getOAuth2Client();

  // Get the access token metadata from /debug_token
  $tokenMetadata = $oAuth2Client->debugToken($accessToken);
  echo '<h3>Metadata</h3>';
  var_dump($tokenMetadata);

  // Validation (these will throw FacebookSDKException's when they fail)
  $tokenMetadata->validateAppId('1299306096781980'); // Replace {app-id} with your app id
  // If you know the user ID this access token belongs to, you can validate it here
  //$tokenMetadata->validateUserId('123');
  $tokenMetadata->validateExpiration();
 */

if (!$accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one


    /* try {
      $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
      } catch (Facebook\Exceptions\FacebookSDKException $e) {
      echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
      exit;
      } */
}

//$_SESSION['fb_access_token'] = (string) $accessToken;
// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
?>
