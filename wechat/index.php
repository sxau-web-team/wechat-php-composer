<?php

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
    $fromUser = $user->get($message->FromUserName);
    return "{$fromUser->nickname} 您好！欢迎关注!";
});

$server->serve()->send();