<?php
$hubVerifyToken = 'electrohammer';
$accessToken="EAAZAr6tYkUCcBAEOXoVuOgpI2zWVFETf9SZBTI0pLNjQnCd7tSsxiBm7E59AN7I15Y0kh3DAlH6oO3pGOwZAF7vfGjMZAd5wxp0rfpqc4UuBfrQFMxLtQDvCKxOrYfGsAYwE2H3zTStZClWcMfwT3hfOCUfOuyoPOnRKiFZC8URgZDZD";
// check token at setupw
if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}
// handle bot's anwser
$input = json_decode(file_get_contents('php://input'), true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageText = $input['entry'][0]['messaging'][0]['message']['text'];

if($messageText == "hi") {
    $answer = "Hello";
}
else if($messageText!="")
{
    //$bot->send(new Message($message['sender']['id'], 'This is a simple text message.'));
    $answer = "I don't understand. Ask me 'hi'";
}
$response = [
    'recipient' => [ 'id' => $senderId ],
    'message' => [ 'text' => $answer ]
];
$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);
//based on http://stackoverflow.com/questions/36803518
?>
