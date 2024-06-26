<?php
/*------------------------------------------------------------------
 | Software: APHP - A PHP TOP Framework
 | Site: https://aphp.top
 |------------------------------------------------------------------
 | CopyRight(C)2020-2024 大松栩<24203741@qq.com>,All Rights Reserved.
 |-----------------------------------------------------------------*/

use aphp\core\App;

define('APHP_TOP', strtr(realpath(__DIR__ . '/../'), '\\', '/'));
if(!file_exists(APHP_TOP.'/install/install.lock')){
    header('Location:install.php');
    exit();
}
require APHP_TOP . '/aphp/bootstrap.php';
App::init()->boot();