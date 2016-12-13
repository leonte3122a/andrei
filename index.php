<?php
$hubVerifyToken = 'electrohammer';
$accessToken="EAAOnMlLekZC0BADgU1QbiZBTn7Ucibbm7UTyV0qLme5enEPq9ciAYp12W9yKTJYMMqQ01MXqzlw7AywfULi0FcP3C94evTFOpMxqQ1GvWqomnTvsKzIKDKjkvySbHYnD2reZCDngXnfgWuA9vCVGqsyZB4HRtoVAYBZBJpborVwZDZD";
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
