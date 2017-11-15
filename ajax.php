<?php
define('__ROOT__', getcwd());

require_once (__ROOT__.'/classes/file-upload.class.php');

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    die('Sorry Request must be Ajax POST'); //exit script
}

//file upload
if(isset($_GET['upload'])) {
    //print_r($_POST);
    //print_r($_FILES);
    new fileUpload($_POST, $_FILES);
}
