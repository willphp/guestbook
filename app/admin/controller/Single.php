<?php
declare(strict_types=1);
namespace app\admin\controller;

class Single extends Base
{
    protected string $middleware = 'auth';
    protected string $table = 'single';
    protected string $order = 'status DESC,id DESC';
}