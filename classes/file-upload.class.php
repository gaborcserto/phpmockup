<?php
// Prevent direct access to this file
if ( ! defined('ABSPATH' ) ) {
	header( 'HTTP/1.1 403 Forbidden' );
	die( 'Please do not load this file directly. Thank you.' );
}

class fileUpload {

    public $uploadDirectory = './images/';
    public $errors = [];
    public $fileExtension;
    public $fileExtensions = ['jpeg', 'jpg', 'png', 'gif'];
    public $fileData;
    public $fileName = '';
    public $fileSize = '';
    public $fileTmpName = '';
    public $fileType = '';
    public $data = [];
    public $uploadPath = '';

    public function __construct($postData, $postImage) {
        echo $this->init($postData, $postImage);
    }


    private function init($postData, $postImage) {

        $this->fileName = $postImage['image']['name'];
        $this->fileSize = $postImage['image']['size'];
        $this->fileTmpName  = $postImage['image']['tmp_name'];
        $this->fileType = $postImage['image']['type'];
        
        $tmp = explode('.',  $this->fileName);
        $this->fileExtension = strtolower(end($tmp));

        $this->uploadPath = $this->uploadDirectory . basename($this->fileName);

        return $this->formCheck($postData);

    }

    private function formCheck ($postData) {

        if (empty($postData['title'])) {
            $errors['title'] = 'Title is required.';
        }

        if (empty($postData['description'])) {
            $errors['description'] = 'Description is required.';
        }

        if ( ! in_array($this->fileExtension, $this->fileExtensions)) {
            $this->errors['image'] = "This file extension is not allowed. Please upload a JPEG or PNG file";
        }

        if ($this->fileSize > 2000000) {
            $this->errors['image'] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        }

        if ( ! empty($errors)) {
            $data['success'] = false;
            $data['errors']  = $errors;
        } else {

            $data = $this->fileUploader();
        }

        return json_encode($data);
    }

    private function fileUploader() {

        $this->dirCheck($this->uploadDirectory);

        $fileUpload = move_uploaded_file($this->fileTmpName, $this->uploadPath);

        if ($fileUpload) {
            $data['success'] = true;
            $data['message'] = "The file " . basename($this->fileName) . " has been uploaded";
        } else {
            $data['success'] = false;
            $data['errors']['form']  = "An error occurred somewhere. Try again or contact the admin";
        }

        return $data;
    }

    private function dirCheck($directoryName) {
        if( ! is_dir($directoryName)) {
            mkdir($directoryName, 0775);
        }
    }
}