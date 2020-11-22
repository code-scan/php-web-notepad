<?php
function Download($path){
    $file_name=substr(strrchr($path, "/"), 1);
    $file_name=str_replace('-','.',$file_name);
    Header("Content-type: application/octet-stream"); 
    Header("Accept-Ranges: bytes"); 
    Header("Accept-Length: ".filesize($path)); 
    Header("Content-Disposition: attachment; filename=" . $file_name); 
    readfile($path);
    die();
}

$base_url = getenv('MWN_BASE_URL') ?: 'https://js.al';
$save_path = getenv('MWN_SAVE_PATH') ?: '_tmp_js_al_files';


header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
$filename=(isset($_GET['note']) and preg_match('/^[a-zA-Z0-9_-]+$/', $_GET['note']))?$_GET['note']:substr(str_shuffle('1234567890'), -5);

$path = $save_path . '/' . $filename;

if (isset($_POST['text'])) {
    file_put_contents($path, $_POST['text']);
    if (!strlen($_POST['text'])) {
        unlink($path);
    }
    die;
}

if(isset($_FILES['data']) and !empty($_FILES['data'])){
  $ext=substr(strrchr($_FILES['data']['name'], "."), 1);
  $filename.="-".$ext;
  $path.="-".$ext;
  echo 'FileName: '.$_FILES['data']['name']."\n";
  move_uploaded_file($_FILES['data']['tmp_name'],$path);
  echo 'Link: '.$base_url.'/'.$filename;
  die();

}

if($filename!=@$_GET['note']){
  header("Location: $base_url/" . $filename);
  die;
}
if(!isset($_FILES['data']['tmp_name']) and stripos($filename,'-')!==false){
    Download($path);
}

if (isset($_GET['raw']) || strlen($_SERVER['HTTP_USER_AGENT'])<50) {
    if (is_file($path)) {
        header('Content-type: text/plain');
        print file_get_contents($path);
    } else {
        header('HTTP/1.0 404 Not Found');
    }
    die;
}

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="generator" content="Minimalist Web Notepad (https://github.com/pereorga/minimalist-web-notepad)">
    <title><?php print $_GET['note']; ?></title>
    <link rel="shortcut icon" href="<?php print $base_url; ?>/favicon.ico">
    <link rel="stylesheet" href="<?php print $base_url; ?>/static/styles.css">
    <style>
        .file input {
            position: absolute;
            right: 0;
            top: 0;
            opacity: 0;/*关键点*/
            filter: alpha(opacity=0);/*兼容ie*/
            font-size: 100px;/* 增大不同浏览器的可点击区域 */
            cursor: pointer;
            height: 30px;
        }
        a{
            text-decoration:none;
        }
    </style>
</head>
<body>
    <div class="container">
       
        <!-- <input name="data" id="data" type="file"  > <button onclick="upload()">UP</button> -->
        <a href="javascript:;" class="file">
            <embed style="height: 25px;" src="/static/up.svg" type="image/svg+xml" />
            <input type="file" name="file" id="file">
        </a>&nbsp;&nbsp;
        <span name="status" id="status"></span>
        <textarea id="content"><?php
            if (is_file($path)) {
                print htmlspecialchars(file_get_contents($path), ENT_QUOTES, 'UTF-8');
            }
        ?></textarea>
    </div>
    <pre id="printable"></pre>
    <script src="<?php print $base_url; ?>/static/script.js"></script>
    <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
    <script src="<?php print $base_url; ?>/static/upload.js"></script>
</body>
</html>
