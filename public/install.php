<?php
header('Content-type: text/html; charset=utf-8');
if (!ini_get('display_errors')) {
    ini_set('display_errors', 'On');
}
error_reporting(E_ALL);
date_default_timezone_set('PRC');
define('APHP_TOP', strtr(realpath(__DIR__ . '/../'), '\\', '/'));
$lock_file = APHP_TOP . '/install/install.lock';
if (file_exists($lock_file)) {
    exit('已经安装过了，重新安装请先删除install/install.lock');
}
$sql_file = APHP_TOP . '/install/data.sql';
if (!file_exists($sql_file)) {
    exit('数据文件install/data.sql不存在');
}
//检测目录是否可写
function is_write(string $dir): bool
{
    if (is_file($dir)) {
        return is_writable($dir) || chmod($dir, 0777);
    }
    if (is_dir($dir) && $fp = fopen("$dir/aphp.txt", 'w')) {
        fclose($fp);
        unlink("$dir/aphp.txt");
        return true;
    }
    return false;
}
//清除html
function clear_html(string $string): string
{
    $string = strip_tags($string);
    $string = preg_replace(['/\t/', '/\r\n/', '/\r/', '/\n/'], '', $string);
    return trim($string);
}
//处理sql文件
function parse_sql_file(string $file, string $table_prefix): array
{
    $sql = file_get_contents($file);
    if ($table_prefix != 'ap_') {
        $sql = str_replace('ap_', $table_prefix, $sql);
    }
    return array_filter(explode(';', $sql));
}
//待检测目录
$write_dir = ['/install', '/runtime', '/config/database.php', '/app/index/config/site.php', '/public/uploads'];
//检测结果
$write_result = array_map(fn($v) => is_write(APHP_TOP . $v), $write_dir);
//是否检测通过
$is_ok = !in_array(false, $write_result);
//检测数据
$dir_test = array_combine($write_dir, $write_result);
//错误信息
$error = '';
$db_file = APHP_TOP . '/config/database.php';
$db_config = require $db_file;
if (isset($_POST['install'])) {
    if (!$is_ok) {
        echo "<script>alert('无法安装!请确保目录和文件可写。');</script>";
    } else {
        unset($_POST['install']);
        $post = array_map(fn($v) => clear_html($v), $_POST);
        $db_config = array_replace_recursive($db_config, ['default' => $post]);
        $db = $db_config['default'];
        $dsn = "{$db['db_driver']}:host={$db['db_host']};port={$db['db_port']};dbname={$db['db_name']};charset={$db['db_charset']}";
        try {
            $pdo = new PDO($dsn, $db['db_user'], $db['db_pass'], $db['pdo_params']);
            file_put_contents($db_file, "<?php\nreturn " . var_export($db_config, true) . ';');
            $pdo->exec("SET sql_mode = ''");
            $data = parse_sql_file($sql_file, $db['table_prefix']);
            foreach ($data as $sql) {
                $pdo->exec($sql);
            }
            file_put_contents($lock_file, time());
            echo "<script>alert('安装成功!默认管理员帐号：admin 密码：1');document.location.href='index.php';</script>";
        } catch (Exception $e) {
            $error = '错误：' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>一鱼留言本安装程序</title>
    <link href="./static/js/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <style>
        body {
            background-color: #F5F5F5;
            padding: 5px;
            line-height: 18px;
        }
        .container {
            border: #CCC 1px solid;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px 30px;
            background-color: #FFF;
        }
    </style>
</head>
<body>
<div class="container">
    <h3><a href="install.php">一鱼留言本安装</a></h3>
    <hr />
    <?php foreach ($dir_test as $k => $v): ?>
        <?php if ($v): ?>
            <p class="text-success"><?php echo $k; ?> 可写，可进行安装。</p>
        <?php else: ?>
            <p class="text-danger"><?php echo $k; ?> 不可写，无法进行安装。</p>
        <?php endif ?>
    <?php endforeach ?>
    <hr />
    <?php if (!empty($error)):?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif;?>
    <form action="install.php" method="post">
        <div class="form-group">
            <label for="db_host">数据库服务器：</label>
            <input type="text" class="form-control" id="db_host" name="db_host" value="localhost"/>
        </div>
        <div class="form-group">
            <label for="db_user">数据库用户：</label>
            <input type="text" class="form-control" id="db_user" name="db_user" value="root"/>
        </div>
        <div class="form-group">
            <label for="db_pass">数据库密码：</label>
            <input type="text" class="form-control" id="db_pass" name="db_pass" value=""/>
        </div>
        <div class="form-group">
            <label for="db_name">数据库名(<span class="text-danger">需先建立数据库</span>)：</label>
            <input type="text" class="form-control" id="db_name" name="db_name" value="guestbook01db"/>
        </div>
        <div class="form-group">
            <label for="table_prefix">数据库表前缀：</label>
            <input type="text" class="form-control" id="table_prefix" name="table_prefix" value="ap_"/>
        </div>
        <div class="form-group">
            <label for="db_charset">数据库编码：</label>
            <input type="text" class="form-control" id="db_charset" name="db_charset" value="utf8mb4"/>
        </div>
        <hr/>
        <input type="submit" value="确认安装" class="btn btn-primary btn-block" name="install"/>
    </form>
</div>
</body>
</html>