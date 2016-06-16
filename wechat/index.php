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
                default:
                    break;
            }
            break;
               //文本信息处理
        case 'text':
            $fromUser = $user->get($message->FromUserName);
            $content = $message->Content;
            if ($content=="模版消息" || $content == 'template') {
                $userId = 'OPENID';
                $templateId = 'lOFwKZr1gloR0caommzl3yRnXia0NLiCWWajU1AzPOU';
                $url = 'http://www.baidu.com';
                $color = '#FF0000';
                $data = array(
                         "username"  => "范兆洁！",
                         "name"   => "杜蕾斯",
                         "price"  => "39.8元",
                         "remark" => "欢迎再次购买！",
                        );
                $messageId = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
            }
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