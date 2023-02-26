<?php

# https://github.com/AliceSync/File-Directory-Comparator

$_can_dir_1 = 'dir one';
$_can_dir_2 = 'dir two';

function can_dir($_dir)
{
    $_arr = scandir($_dir);
    $_ret = [];
    unset($_arr[0], $_arr[1]);
    foreach ($_arr as $_sub_object) {
        if (is_dir($_dir  . DIRECTORY_SEPARATOR . $_sub_object)) {
            $_ret   = array_merge($_ret, can_dir($_dir  . DIRECTORY_SEPARATOR . $_sub_object));
        } elseif (is_file($_dir  . DIRECTORY_SEPARATOR . $_sub_object)) {
            $_ret[] = $_dir  . DIRECTORY_SEPARATOR . $_sub_object;
        }
    }
    return $_ret;
}
$_files_1 = can_dir($_can_dir_1);
$_files_2 = can_dir($_can_dir_2);

$_files_1 = str_replace($_can_dir_1, '', $_files_1); // array_flip
$_files_2 = str_replace($_can_dir_2, '', $_files_2); // array_flip

$_files_1_display = array_diff($_files_1, $_files_2); # 第一个文件夹有 第二个没有的
$_files_2_display = array_diff($_files_2, $_files_1); # 第二个文件夹有 第一个没有的

echo '丢失文件:' . PHP_EOL;

foreach ($_files_1_display as $key => $value) {
    unset($_files_1[$key]);
    echo '{' . PHP_EOL . '    [' . $_can_dir_1 . $value . ']' . PHP_EOL . '    [' . $_can_dir_2 . $value . ' (文件不存在)]' . PHP_EOL . '}' . PHP_EOL;
}

foreach ($_files_2_display as $key => $value) {
    unset($_files_2[$key]);
    echo '{ ' . PHP_EOL . '    [' . $_can_dir_1 . $value . ' (文件不存在)]' . PHP_EOL . '    [' . $_can_dir_2 . $value . ']' . PHP_EOL . '}' . PHP_EOL;
}

echo '差异文件:' . PHP_EOL;

foreach ($_files_1 as $value) {
    if (md5_file($_can_dir_1 . $value) != md5_file($_can_dir_2 . $value)) {
        echo '{' . PHP_EOL . '    [' . $_can_dir_1 . $value . ']' . PHP_EOL . '    [' . $_can_dir_2 . $value . ']' . PHP_EOL . '}' . PHP_EOL;
    }
}

echo 'by zslm.org' . PHP_EOL;
