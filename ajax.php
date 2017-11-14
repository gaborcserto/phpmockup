<?php
define('__ROOT__', getcwd());

require_once (__ROOT__.'/classes/file-upload.class.php');

//file upload
if($_POST) {
    new fileUpload($_POST);
}
