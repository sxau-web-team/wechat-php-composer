<?php
include '/app/vendor/autoload.php';
use EasyWeChat\Foundation\Application;

$options = [
    'debug'     => true,
    'app_id'    => 'wx6a32d9856d2a1e6d',
    'secret'    => 'ce550bba2d0fa5215be9aa11cc17da81',
    'token'     => 'w3chat@4321',
    'log' => [
        'level' => 'debug',
        'file'  => '/tmp/easywechat.log',
    ],
    // ...
];

$app = new Application($options);

$server = $app->server;
$user = $app->user;

$server->setMessageHandler(function($message) use ($user) {
    switch ($message->MsgType) {
        case 'event':
            switch ($message->Event) {
                case 'subscribe':
                    // code...
                    break;

                default:
                    // code...
                    break;
            }
            break;
               //文本信息处理
        case 'text':
            $fromUser = $user->get($message->FromUserName);
            return "{$fromUser->nickname} {$fromUser->openid}您好！欢迎关注!";
            break;
        case 'image':
            $mediaId  = $message->MediaId;
            return new Image(['media_id' => $mediaId]);
            break;
        case 'voice':
            $mediaId  = $message->MediaId;
            return new Voice(['media_id' => $mediaId]);
            break;
        case 'video':
            $mediaId  = $message->MediaId;
            return new Video(['media_id' => $mediaId]);
            break;
        case 'location':
            return new Text(['content' => $message->Label]);
            break;
        case 'link':
            return new Text(['content' => $message->Description]);
            break;
        default:
            break;
    }
});

$server->serve()->send();