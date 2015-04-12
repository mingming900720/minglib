<?php
/**
 * Created by PhpStorm.
 * User: zzugm
 * Date: 2015/4/11
 * Time: 16:55
 * 接受一个文件夹，
 * 判断其中的文件是否相同
 * 相同的话删除第二个相同的文件
 *
 */
header("Content-Type: text/html; charset=UTF-8");
$dir = "/AppServ/www/checkimages/backgrounds/";

checkFile($dir);

function checkFile($dir) {
    //创建一个数组用于保存已经检查过的文件
    $t = array();
    //检查如果$dir是一个文件夹则打开这个文件夹
    if (is_dir($dir) && $dh = opendir($dir)) {
        //从打开的文件夹中读取文件直到读取失败或者读取完所有文件
        while (($file = readdir($dh)) !== false) {
            //当读取到的文件不在$t中的时候才进行判断
            if (!in_array($file, $t) ) {
                //将此时判断的文件存入数组
                $t[$file] = $file;
                //排除.和..两个文件名，并且再次打开文件夹
                if ($file != '.' && $file != '..' && $dh2 = opendir($dir)) {
                    while (($file2 = readdir($dh2)) !== false) {
                        if (!in_array($file2, $t) ) {
                            //比较两个文件的MD5值，如果一样的 话就删除第二个文件
                            if (checkMd5($dir . $file, $dir . $file2)) {
                                $t[$file2] = $file2;
                                if(delete($dir.$file2)){
                                    echo '删除'.$file2.'成功<br>';
                                }else{
                                    echo '删除'.$file2.'不成功：未知错误<br>';
                                }
                            }else{
                                echo $file.'和'.$file2.'不相同<br>';
                            }
                        }
                    }
                    closedir($dh2);
                }
            }
        }
        closedir($dh);
    }
}

//比较两个文件的MD5值，一样的话返回true否则返回false
function checkMd5 ($filename, $filename2){
    if(md5_file($filename) == md5_file($filename2)) {
        return true;
    }else{
        return false;
    }
}
//删除文件，如果删除成功就返回true否则返回false
function delete ($filename){
    if(unlink($filename)) {
        return true;
    }else{
        return false;
    }

}
