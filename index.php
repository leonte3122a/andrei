<?php
$hubVerifyToken = 'electrohammer';
$accessToken="EAAIKJGUvJKwBAPF6uFuETicAXyXifiB7GZBhoMZCep5hz40mBmEnoZBZCE7HUlXAdt0p7ZA8RZA8pVnx4pE0fc1jrWZBqUNrwbCI57ChIDPoIElgZBze9ZBQosxxYiFn2aeEQz5z6Nbrc9NxhZCOUcHRfJKwQu6N3TeIMn88qO9onuFwZDZD";
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
