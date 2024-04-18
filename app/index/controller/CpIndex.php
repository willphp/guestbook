<?php
declare(strict_types=1);
namespace app\index\controller;
use aphp\core\Jump;
class CpIndex
{
    use Jump;
    protected string $middleware = 'rbac'; //权限验证
    public function index()
    {
        $sys = [
            '系统版本' => '一鱼留言本 v3.2',
            '当前域名' => $_SERVER['SERVER_NAME'],
            'IP地址' => gethostbyname($_SERVER['SERVER_NAME']),
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP版本' => 'PHP ' . PHP_VERSION,
            'PHP运行方式' => php_sapi_name(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '服务器时间' => date("Y年n月j日 H:i:s"),
        ];
        return view()->with('sys', $sys);
    }
}