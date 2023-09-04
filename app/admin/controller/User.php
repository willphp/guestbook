<?php
declare(strict_types=1);
namespace app\admin\controller;

class User extends Base
{
    protected string $middleware = 'auth';
    protected string $table = 'user';
    protected string $order = 'status DESC,id DESC';
}