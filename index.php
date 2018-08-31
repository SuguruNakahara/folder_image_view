<?php
function getFileList($dir) {
    $files = scandir($dir);
    $files = array_filter($files, function ($file) {
        return !in_array($file, array('.', '..', 'index.php'));
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
<style type="text/css">
.rotate { transform: rotate(0deg) }
.rotate_right { transform: rotate(90deg) }
.rotate_left { transform: rotate(270deg) }
</style>
</head>
<body>
<?php
$count = 0;

foreach ($list as $item) {
    $path = str_replace(__DIR__, '.', $item);
    $orientation = exif_read_data($path)["Orientation"];
    if ($orientation == 6) {
        $img_class = "rotate_right";
    } else if ($orientation == 8) {
        $img_class = "rotate_left";
    } else {
        $img_class = "rotate";
    }
    echo "<img class='".$img_class."' src='".$path."' width='210' height='210' />";

    // Group with 3
    $count++;
    if ($count % 3 == 0) {
        echo "<span style='margin-right:10px'></span>"; 
    }
}
?>
</body>