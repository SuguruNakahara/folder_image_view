<?php
$php_file = 'index.php';

function getFileList($dir) {
    $files = scandir($dir);
    $files = array_filter($files, function ($file) {
        return !in_array($file, array('.', '..', $php_file));
    });

    $list = array();
    foreach ($files as $file) {
        $fullpath = rtrim($dir, '/') . '/' . $file;
        if (is_file($fullpath)) {
            $list[] = $fullpath;
        }
        if (is_dir($fullpath)) {
            $list = array_merge($list, getFileList($fullpath));
        }
    }

    return $list;
}

$current_dir = dirname(__FILE__);
$list = getFileList($current_dir);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8"/>
</head>
<body>
    <?php
        $count = 0;
        foreach ($list as $item) {
            $path = str_replace(__DIR__, '', $item);
            echo "<p>".$path."</p>";
            echo "<img src='".$path."' width='210' height='210' />";
        }
    ?>
</body>