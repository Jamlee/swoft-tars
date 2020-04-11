<?php
$args = $_SERVER['argv'];
$swoft_bin = dirname(__FILE__).'/bin/swoft ';
if ($args[2] == 'start') {
    $arg_cmd = 'http:start -d';
} else if ($args[2] == 'stop') {
    $arg_cmd = 'http:stop';
} else if ($args[2] == 'restart') {
    $arg_cmd = 'http:restart';
} else {
    $arg_cmd = $args[2];
}
$cmd = "/usr/bin/php " . $swoft_bin . $arg_cmd;

// 启动
system($cmd, $output);