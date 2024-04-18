<?php
return [
	'index' => 'index/index',
    'index_(:num)' => 'index/index/p/${1}',
    'about' => 'index/single/sign/about',
    'donation' => 'index/about',
    'user' => 'user/index',
    'user_(:num)' => 'user/info/id/${1}',
    'msg_(:num)' => 'guestbook/view/id/${1}',
    'login' => 'login/login',
    'register' => 'login/register',
];