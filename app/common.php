<?php
declare(strict_types=1);

// 导航高亮显示
function nav_active(string $nav, string $class = ' class="active"'): string
{
    return ($nav == get_controller()) ? $class : '';
}

// 获取网站版本
function site_ver(): string
{
    return APP_DEBUG ? date('YmdHis') : site('version', date('Y-m-d'));
}

// 获取头像
function get_avatar(string $url):string
{
    return !empty($url)? $url : __STATIC__.'/img/avatar.png';
}

// 表名验证
function is_table(string $table): bool
{
    $prefix = pdo()->getConfig('table_prefix');
    return (bool)preg_match('/^' . $prefix . '\w{1,12}$/', $table);
}

// ids过滤转换
function ids_filter(string $ids, bool $to_array = false, bool $gt_0 = true)
{
    $ids = array_filter(explode(',', $ids), 'is_numeric');
    $ids = array_unique($ids);
    if ($gt_0) {
        $ids = array_filter($ids, fn(int $n)=>$n>0);
    }
    ksort($ids);
    return $to_array ? $ids : implode(',', $ids);
}

// 格式时间
function get_time_ago(int $time): string
{
    $etime = time() - $time;
    if ($etime < 1) {
        return '刚刚';
    }
    $interval = [31536000 => '年前', 2592000 => '个月前', 604800=>'星期前', 86400=>'天前', 3600=>'小时前', 60=>'分钟前', 1=>'秒前'];
    foreach ($interval as $k => $v) {
        $ok = floor($etime / $k);
        if ($ok != 0) {
            return $ok.$v;
        }
    }
    return '刚刚';
}

// 生成form:select
function build_select(string $name, $options, $selected = ''): string
{
    $options = is_array($options)? $options : explode(',', $options);
    $html = "<select name=\"{$name}\" class=\"form-control\" style=\"width:auto;\">\n";
    foreach ($options as $val => $key) {
        if ($selected == $val) {
            $html .= "\t<option value=\"{$val}\" selected=\"selected\">{$key}</option>\n";
        } else {
            $html .= "\t<option value=\"{$val}\">{$key}</option>\n";
        }
    }
    $html .= "</select>\n";
    return $html;
}

// 生成form:radios
function build_radios(string $name, $options, $selected = ''): string
{
    $options = is_array($options)? $options : explode(',', $options);
    $html = '';
    foreach ($options as $val => $key) {
        if ($selected == $val) {
            $html .= "<label><input type=\"radio\" name=\"{$name}\" value=\"{$val}\" checked=\"checked\" />{$key}</label>\n";
        } else {
            $html .= "<label><input type=\"radio\" name=\"{$name}\" value=\"{$val}\" />{$key}</label>\n";
        }
    }
    return $html;
}

/**
 * curl请求
 */
function get_curl(string $url, array $post = [], array $header = [], bool $get_header = false, string $cookie = '', string $referer = '', string $ua = '', bool $nobody = false, int $timeout = 30): string
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    if (!empty($post)) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $http = ['Accept: */*', 'Accept-Encoding: gzip,deflate,sdch', 'Accept-Language: zh-CN,zh;q=0.8', 'Connection: close'];
    $header = array_merge($http, $header);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    if ($get_header) {
        curl_setopt($ch, CURLOPT_HEADER, true);
    }
    if (!empty($cookie)) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if (!empty($referer)) {
        if ($referer == '1') {
            $referer = 'https://i.qq.com/';
        }
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
    if (!empty($ua)) {
        $ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36';
    }
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    if ($nobody) {
        curl_setopt($ch, CURLOPT_NOBODY, true);
    }
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ret = curl_exec($ch);
    curl_close($ch);
    return $ret ? trim($ret) : '';
}

/**
 * 获取真实IP
 */
function get_server_ip(): string
{
    return get_curl('https://www.bt.cn/Api/getIpAddress') ?: get_curl('http://members.3322.org/dyndns/getip');
}

/**
 * 格式化输出IP
 */
function ip_format(int $ip): string
{
    return preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/i',"$1.$2.*.$4", long2ip($ip));
}

/**
 * 获取主题
 */
function get_theme(int $type = 0)
{
    $themeSet = site('theme_list', 'default=默认');
    $themeSet = explode('|', $themeSet);
    $arr = [];
    foreach ($themeSet as $k => $v) {
        [$key, $val] = parse_prefix_name($v, strval($k), '=');
        $arr[$key] = $val;
    }
    if ($type == 0) {
        return $arr;
    }
    return $arr[__THEME__] ?? '未设';
}