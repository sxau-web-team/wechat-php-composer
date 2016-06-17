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
];

$app = new Application($options);

$server = $app->server;
$user = $app->user;

$server->setMessageHandler(function($message) use ($user) {
    switch ($message->MsgType) {
        case 'event':
            switch ($message->Event) {
                case 'subscribe':
                    $fromUser = $user->get($message->FromUserName);
                    return "您好！欢迎关注!";
                    break;
                case 'CLICK':
                    switch ($message->EventKey) {
                        case 'V1001_GOOD':
                            $response = $app->oauth->scopes(['snsapi_userinfo'])->redirect();
                            $response->send(); exit();
                            break;
                        case 'V1001_TODAY_MUSIC':
                            $fromUser = $user->get($message->FromUserName);
                            $content = $message->Content;
                            return "{$fromUser->nickname} 您好！欢迎关注!" .$content.$message;
                            # code...
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
            break;
               //文本信息处理
        case 'text':
            $fromUser = $user->get($message->FromUserName);
            $content = $message->Content;
            return "{$fromUser->nickname} {$fromUser->openid}您好！欢迎关注!" .$content;
            break;
        case 'image':
            $mediaId  = $message->MediaId;
            return new Image(['media_id' => $mediaId]);
            break;
        case 'voice':
            $fromUser = $user->get($message->FromUserName);
            return "{$fromUser->nickname} {$fromUser->openid}您好！欢迎关注!";
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
            $fromUser = $user->get($message->FromUserName);
            return "未知错误。";
            break;
    }
});

// $buttons = [
//     [
//         "type" => "click",
//         "name" => "今日歌曲",
//         "key"  => "V1001_TODAY_MUSIC"
//     ],
//     [
//         "name"       => "菜单",
//         "sub_button" => [
//             [
//                 "type" => "view",
//                 "name" => "搜索",
//                 "url"  => "http://www.soso.com/"
//             ],
//             [
//                 "type" => "view",
//                 "name" => "视频",
//                 "url"  => "http://v.qq.com/"
//             ],
//             [
//                 "type" => "click",
//                 "name" => "赞一下我们",
//                 "key" => "V1001_GOOD"
//             ],
//         ],
//     ],
// ];
// $menu = $app->menu;
// $menu->add($buttons);

$server->serve()->send();