## 一鱼留言本v3

>一鱼留言本是一个用aphp框架开发的简单留言本

### 演示地址

[http://guestbook.aphp.top](http://guestbook.aphp.top)

### 系统特色

- 多主题
- 自适应
- 安全过滤html

### 技术构架

- PHP框架：APHP v5.0.3 (php7.4.3~8.3.x)
- 前后台UI：Bootstrap + Jquery
- 弹窗组件：layer mobile
- 富文本编辑器：HandyEditor

### 前台功能

- 游客发布留言，点赞留言，查看留言会员信息。
- 会员注册，登录，修改资料，修改密码。发布留言，删除留言。
- 管理员回复，置顶，删除留言。

### 后台功能

- 留言管理：查看，回复，启用，停用，删除留言。
- 单页管理：设置留言公告，关于页面(HTML内容)，图片上传等。
- 用户管理：启用，停用，添加，删除，修改用户。
- 网站配置：添加，修改，删除配置，生成配置文件。
- 数据管理：清空数据，清除缓存。

### URL重写

Nginx规则：

```
location / {
	if (!-e $request_filename) {
		rewrite  ^(.*)$  /index.php/$1  last;
	}
}
```

Apache规则：

```
<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php? [L,E=PATH_INFO:$1]
</IfModule>
```

### 技术支持

QQ群1：325825297  QQ群2：16008861

官网：[aphp.top](https://www.aphp.top) 作者：大松栩(24203741@qq.com) 