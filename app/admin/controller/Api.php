<?php
declare(strict_types=1);

namespace app\admin\controller;

use willphp\core\Upload;

class Api
{
    use \willphp\core\Jump;

    protected array $middleware = [
        'auth' => ['except' => ['captcha']], //除captcha外
    ];

    public function clear(): void
    {
        cache_flush('[all]');
        $this->success('清除缓存成功', 'index/index');
    }

    //编辑器上传图片 path相对路径，url绝对路径
    public function editor_upload()
    {
        $res = Upload::init('img')->save();
        $link = $res[0]['path'] ?? 'Your image was not uploaded.';
        exit($link);
    }

    //图片base64上传
    public function upload_base64()
    {
        $type = input('post.type', '', 'clear_html');
        $base64 = input('post.base64');
        $res = Upload::init('img')->saveBase64($base64);
        if (!$res['path']) {
            $this->error('上传失败');
        }
        $this->_json(200, '上传成功', ['path' => $res['path']]);
    }
    //图片删除
    public function image_del() {
        $pic = input('post.pic', '', 'clear_html');
        $filename = trim($pic, '.');
        $file = ROOT_PATH.'/public'.$filename;
        if (file_exists($file)) {
            unlink($file);
        }
        $this->success('删除成功');
    }
}