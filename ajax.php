<?php
define('__ROOT__', getcwd());

require_once (__ROOT__.'/classes/file-upload.class.php');

//file upload
var_dump($_POST);
if($_POST['image']) {
    new fileUpload( $_POST );
}
