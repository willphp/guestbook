<?php
declare(strict_types=1);
namespace app\admin\controller;
class Index
{
    protected string $middleware = 'auth';
    public function index()
	{
        $info = [
            '服务器域名/IP' => $_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            '剩余空间' => size_format((int)disk_free_space(".")),
            'PHP运行方式' => php_sapi_name(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time').'秒',
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s",time()+8*3600)
        ];
        return view()->with('info', $info);
	}
}