<?php
/*----------------------------------------------------------------
 | Software: [WillPHP framework]
 | Site: 113344.com
 |----------------------------------------------------------------
 | Author: 无念 <24203741@qq.com>
 | WeChat: www113344
 | Copyright (c) 2020-2023, 113344.com. All Rights Reserved.
 |---------------------------------------------------------------*/
header('Content-type: text/html; charset=utf-8');
date_default_timezone_set('PRC');
define('ROOT_PATH', strtr(realpath(__DIR__ . '/../'), '\\', '/'));
$lockFile = ROOT_PATH . '/install/install.lock';
if (file_exists($lockFile)) {
    exit('已经安装过了，重新安装请先删除install/install.lock');
}
$dataFile = ROOT_PATH . '/install/data.sql';
if (!file_exists($dataFile)) {
    exit('数据文件install/data.sql不存在');
}
function get_install_sql(string $file, string $dbPrefix): array
{
    $sql = file_get_contents($file);
    if ($dbPrefix != 'wp_') {
        $sql = str_replace('wp_', $dbPrefix, $sql);
    }
    return array_filter(explode(';', $sql));
}

function is_write(string $res): bool
{
    if (is_file($res)) {
        return is_writable($res) || chmod($res, 0777);
    }
    if (is_dir($res) && $fp = fopen("$res/test.txt", 'w')) {
        fclose($fp);
        unlink("$res/test.txt");
        return true;
    }
    return false;
}

function get_dsn(array $config): string
{
    $dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'];
    if (isset($config['db_port'])) {
        $dsn .= ';port=' . $config['db_port'];
    }
    if (isset($config['db_charset'])) {
        $dsn .= ';charset=' . $config['db_charset'];
    }
    return $dsn;
}
$err = '';
$write_res = ['/install', '/runtime', '/config/database.php', '/app/index/config/site.php', '/public/uploads'];
$write_ok = array_map(fn($v) => is_write(ROOT_PATH . $v), $write_res);
$result = array_combine($write_res, $write_ok);
$is_ok = !in_array(false, $write_ok);
$db_config_file = ROOT_PATH . '/config/database.php';
$db_config = require $db_config_file;
if (isset($_POST['install'])) {
    if (!$is_ok) {
        echo "<script>alert('无法安装!请确保目录和文件可写。');</script>";
    } else {
        unset($_POST['install']);
        $db_config = array_replace_recursive($db_config, ['default' => $_POST]);
        $db = $db_config['default'];
        $dsn = get_dsn($db);
        try {
            $pdo = new PDO($dsn, $db['db_user'], $db['db_pwd'], $db['pdo_params']);
            file_put_contents($db_config_file, "<?php\nreturn " . var_export($db_config, true) . ';');
            $pdo->exec("SET sql_mode = ''");
            $data = get_install_sql($dataFile, $db['db_prefix']);
            foreach ($data as $sql) {
                $pdo->exec($sql);
            }
            file_put_contents($lockFile, time());
            echo "<script>alert('安装成功!默认管理员帐号：admin 密码：123456');document.location.href='index.php';</script>";
        } catch (Exception $e) {
            $err = '错误：' . $e->getMessage();
        }
    }
}
?>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>一鱼留言板安装程序</title>
    <link href="./static/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <style>
        .wrapper {
            border: #CCC 1px solid;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px 30px;
            background-color: #FFF;
            border-radius: 5px;
        }
    </style>
</head>
<body style="background-color:#F5F5F5">
<div class="wrapper">
    <h3><a href="install.php">一鱼留言板安装程序</a></h3>
    <hr/>
    <?php foreach ($result as $k => $v): ?>
        <?php if ($v): ?>
            <p style="color:green;"><?php echo $k; ?> 可写，可进行安装。</p>
        <?php else: ?>
            <p style="color:red;"><?php echo $k; ?> 不可写，无法进行安装。</p>
        <?php endif ?>
    <?php endforeach ?>
    <hr/>
    <?php if (!empty($err)):?>
        <p style="color:red;"><?php echo $err; ?></p>
        <hr/>
    <?php endif;?>
    <form action="install.php" method="post">
        <div class="form-group">
            <label for="db_host">数据库服务器：</label>
            <input type="text" class="form-control" id="db_host" name="db_host" value="localhost"/>
        </div>
        <div class="form-group">
            <label for="db_user">用户名：</label>
            <input type="text" class="form-control" id="db_user" name="db_user" value="root"/>
        </div>
        <div class="form-group">
            <label for="db_pwd">密码：</label>
            <input type="text" class="form-control" id="db_pwd" name="db_pwd" value=""/>
        </div>
        <div class="form-group">
            <label for="db_name">数据库名(<span style="color:red">需先建立数据库</span>)：</label>
            <input type="text" class="form-control" id="db_name" name="db_name" value="willbook01db"/>
        </div>
        <div class="form-group">
            <label for="db_prefix">数据库表前缀：</label>
            <input type="text" class="form-control" id="db_prefix" name="db_prefix" value="wp_"/>
        </div>
        <div class="form-group">
            <label for="db_charset">数据库编码：</label>
            <input type="text" class="form-control" id="db_charset" name="db_charset" value="utf8"/>
        </div>
        <hr/>
        <input type="submit" value="确认安装" class="btn btn-primary btn-block" name="install"/>
    </form>
</div>
</body>
</html>