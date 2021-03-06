<?php
// parameters
$hubVerifyToken = "electrohammer"; // Verify token
$accessToken = "EAAFmkd01IIUBAH4ZB3PMQHKR63zDwHGFGZCyJGvr6c8NWXl3T2JFV5hzSZC12rf4WfqPVooIKQYklwn4pT81QkSo4jvC67dWTmfhvjklLEJVZCDr0m4JZBktK0sfnZAY5G24KlGRuZBNa3PJCIa8d0L9KGoNs4QfD2XDy68ZAFZBDHQZDZD"; // Page token
if (file_exists(__DIR__.'/config.php')) {
    $config = include __DIR__.'/config.php';
    $verify_token = $config['verify_token'];
    $token = $config['token'];
}

require_once(dirname(__FILE__) . '/vendor/autoload.php');
use pimax\FbBotApp;
use pimax\Messages\Message;
use pimax\Messages\ImageMessage;
use pimax\UserProfile;
use pimax\Messages\MessageButton;
use pimax\Messages\StructuredMessage;
use pimax\Messages\MessageElement;
use pimax\Messages\MessageReceiptElement;
use pimax\Messages\Address;
use pimax\Messages\Summary;
use pimax\Messages\Adjustment;

if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
  echo $_REQUEST['hub_challenge'];
  exit;
}
// handle bot's anwser
$bot = new FbBotApp($token);


if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token)
{
     // Webhook setup request
    echo $_REQUEST['hub_challenge'];
} else {

     $data = json_decode(file_get_contents("php://input"), true);
     if (!empty($data['entry'][0]['messaging']))
     {
            foreach ($data['entry'][0]['messaging'] as $message)
            {

if (!empty($message['delivery'])) {
    continue;
}

$command = "";
// Receive message from user
if (!empty($message['message'])) {
    $command = $message['message']['text'];
    // OR receive button click
} else if (!empty($message['postback'])) {
    $command = $message['postback']['payload'];
}

// Handle the command
if($command!="")
{
switch ($command) {

    // When bot receive "text"
    case 'text':
        $bot->send(new Message($message['sender']['id'], 'This is a simple text message.'));
        break;

    // When bot receive "button"
    case 'button':
      $bot->send(new StructuredMessage($message['sender']['id'],
          StructuredMessage::TYPE_BUTTON,
          [
              'text' => 'Choose category',
              'buttons' => [
                  new MessageButton(MessageButton::TYPE_POSTBACK, getReady()),
                  new MessageButton(MessageButton::TYPE_POSTBACK, 'Second button'),
                  new MessageButton(MessageButton::TYPE_POSTBACK, 'Third button')
              ]
          ]
      ));
    break;

    // When bot receive "generic"
    case 'generic':

        $bot->send(new StructuredMessage($message['sender']['id'],
            StructuredMessage::TYPE_GENERIC,
            [
                'elements' => [
                    new MessageElement("First item", "Item description", "", [
                        new MessageButton(MessageButton::TYPE_POSTBACK, 'First button'),
                        new MessageButton(MessageButton::TYPE_WEB, 'Web link', 'http://facebook.com')
                    ]),

                    new MessageElement("Second item", "Item description", "", [
                        new MessageButton(MessageButton::TYPE_POSTBACK, 'First button'),
                        new MessageButton(MessageButton::TYPE_POSTBACK, 'Second button')
                    ]),

                    new MessageElement("Third item", "Item description", "", [
                        new MessageButton(MessageButton::TYPE_POSTBACK, 'First button'),
                        new MessageButton(MessageButton::TYPE_POSTBACK, 'Second button')
                    ])
                ]
            ]
        ));

    break;

    // When bot receive "receipt"
    case 'receipt':

        $bot->send(new StructuredMessage($message['sender']['id'],
            StructuredMessage::TYPE_RECEIPT,
            [
                'recipient_name' => 'Fox Brown',
                'order_number' => rand(10000, 99999),
                'currency' => 'USD',
                'payment_method' => 'VISA',
                'order_url' => 'http://facebook.com',
                'timestamp' => time(),
                'elements' => [
                    new MessageReceiptElement("First item", "Item description", "", 1, 300, "USD"),
                    new MessageReceiptElement("Second item", "Item description", "", 2, 200, "USD"),
                    new MessageReceiptElement("Third item", "Item description", "", 3, 1800, "USD"),
                ],
                'address' => new Address([
                    'country' => 'US',
                    'state' => 'CA',
                    'postal_code' => 94025,
                    'city' => 'Menlo Park',
                    'street_1' => '1 Hacker Way',
                    'street_2' => ''
                ]),
                'summary' => new Summary([
                    'subtotal' => 2300,
                    'shipping_cost' => 150,
                    'total_tax' => 50,
                    'total_cost' => 2500,
                ]),
                'adjustments' => [
                    new Adjustment([
                        'name' => 'New Customer Discount',
                        'amount' => 20
                    ]),

                    new Adjustment([
                        'name' => '$10 Off Coupon',
                        'amount' => 10
                    ])
                ]
            ]
        ));

    break;

    // Other message received
    default:
        $bot->send(new Message($message['sender']['id'], 'Sorry. I don’t understand you.'));
}
            }
   }
}
}
