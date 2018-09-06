<?php
/**
 * Eric Draken
 * Date: 2016-09-02
 * Time: 4:44 PM
 * Desc: Callback for responding to Line messages
 *       Send 'whoami' to this endpoint to get a reply with your mid.
 */

// I put constants like 'LINE_CHANNEL_ID' here 
//require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . "/../includes/line-bot-sdk/vendor/autoload.php";

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\GuzzleHTTPClient;

// Set these values
$config = [
    'channelId' => 1605764083,
    'channelSecret' => 496241cfaf7a3ed50afd51e1d37c06f0,
    'channelMid' => LINE_CHANNEL_MID,
];
$sdk = new LINEBot($config, new GuzzleHTTPClient($config));

$postdata = @file_get_contents("php://input");
$messages = $sdk->createReceivesFromJSON($postdata);

// Verify the signature
// REF: http://line.github.io/line-bot-api-doc/en/api/callback/post.html#signature-verification
// REF: http://stackoverflow.com/a/541450
$sigheader = 'X-LINE-ChannelSignature';
$signature = @$_SERVER[ 'HTTP_'.strtoupper(str_replace('-','_',$sigheader)) ];
if($signature && $sdk->validateSignature($postdata, $signature)) {
    // Next, extract the messages
    if(is_array($messages)) {
        foreach ($messages as $message) {
            if ($message instanceof LINEBot\Receive\Message\Text) {
                $text = $message->getText();
                if (strtolower(trim($text)) === "whoami") {
                    $fromMid = $message->getFromMid();
                    $user = $sdk->getUserProfile($fromMid);
                    $displayName = $user['contacts'][0]['displayName'];

                    $reply = "You are $displayName, and your mid is:\n\n$fromMid";

                    // Send the mid back to the sender and check if the message was delivered
                    $result = $sdk->sendText([$fromMid], $reply);
                    if(!$result instanceof LINE\LINEBot\Response\SucceededResponse) {
                        error_log('LINE error: ' . json_encode($result));
                    }
                } else {
                    // Process normally, or do nothing
                }
            } else {
                // Process other types of LINE messages like image, video, sticker, etc.
            }
        }
    } // Else, error
} else {
    error_log('LINE signatures didn\'t match: ' . $signature);
}
1
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
52
53
54
55
56
57
58
59
60
61
<?php
/**
 * Eric Draken
 * Date: 2016-09-02
 * Time: 4:44 PM
 * Desc: Callback for responding to Line messages
 *       Send 'whoami' to this endpoint to get a reply with your mid.
 */
 
// I put constants like 'LINE_CHANNEL_ID' here 
//require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . "/../includes/line-bot-sdk/vendor/autoload.php";
 
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\GuzzleHTTPClient;
 
// Set these values
$config = [
    'channelId' => 1605764083,
    'channelSecret' => 496241cfaf7a3ed50afd51e1d37c06f0,
    'channelMid' => LINE_CHANNEL_MID,
];
$sdk = new LINEBot($config, new GuzzleHTTPClient($config));
 
$postdata = @file_get_contents("php://input");
$messages = $sdk->createReceivesFromJSON($postdata);
 
// Verify the signature
// REF: http://line.github.io/line-bot-api-doc/en/api/callback/post.html#signature-verification
// REF: http://stackoverflow.com/a/541450
$sigheader = 'X-LINE-ChannelSignature';
$signature = @$_SERVER[ 'HTTP_'.strtoupper(str_replace('-','_',$sigheader)) ];
if($signature && $sdk->validateSignature($postdata, $signature)) {
    // Next, extract the messages
    if(is_array($messages)) {
        foreach ($messages as $message) {
            if ($message instanceof LINEBot\Receive\Message\Text) {
                $text = $message->getText();
                if (strtolower(trim($text)) === "whoami") {
                    $fromMid = $message->getFromMid();
                    $user = $sdk->getUserProfile($fromMid);
                    $displayName = $user['contacts'][0]['displayName'];
 
                    $reply = "You are $displayName, and your mid is:\n\n$fromMid";
 
                    // Send the mid back to the sender and check if the message was delivered
                    $result = $sdk->sendText([$fromMid], $reply);
                    if(!$result instanceof LINE\LINEBot\Response\SucceededResponse) {
                        error_log('LINE error: ' . json_encode($result));
                    }
                } else {
                    // Process normally, or do nothing
                }
            } else {
                // Process other types of LINE messages like image, video, sticker, etc.
            }
        }
    } // Else, error
} else {
    error_log('LINE signatures didn\'t match: ' . $signature);
}
