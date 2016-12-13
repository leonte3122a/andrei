<?php
$hubVerifyToken = 'electrohammer';
$accessToken="EAAaB82SFkG4BALyb8XiZAa7ZBOsEIM7ZCsmSN46obpbubowtKRJATLdB1kZCP7zJW76HKbD08zVSjQGd9DXHJbwPW2L8J7ZBNLr0DSQQ17ntQXXB4JYKPFiVwbArZC7jnZCbqKP6Fq8ZB5OxRxbYF14vuQ3JjI12muR1t80012lyUQZDZD";
// check token at setup
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