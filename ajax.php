<?php
define('__ROOT__', getcwd());

require_once (__ROOT__.'/classes/file-upload.class.php');


//file upload
if(isset($_GET['upload'])) {
    //print_r($_POST);
    //print_r($_FILES);
    new fileUpload($_POST, $_FILES);
}
