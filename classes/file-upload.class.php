<?php

class fileUpload {

    public $uploadDirectory = '../images/';
    public $errors = [];
    public $fileExtensions = ['jpeg', 'jpg', 'png', 'gif'];
    public $fileName;
    public $fileSize;
    public $fileTmpName;
    public $fileType;
    public $fileExtension;
    public $data = [];
    public $uploadPath = '';

    public function __construct($postData) {
        echo $this->init($postData);
    }

    private function init ($postData) {
        $this->fileName = $postData['image']['name'];
        $this->fileSize = $postData['image']['size'];
        $this->fileTmpName  = $postData['image']['tmp_name'];
        $this->fileType = $postData['image']['type'];
        $this->fileExtension = strtolower(end(explode('.', $this->fileName)));
        $this->uploadPath = $this->uploadDirectory . basename($this->fileName);

        $this->fileCheck($postData);

    }

    private function fileCheck ($postData) {

        if (empty($postData['name'])) {
            $errors['name'] = 'Name is required.';
        }

        if (empty($postData['email'])) {
            $errors['email'] = 'Email is required.';
        }

        if ( ! in_array($this->fileExtension, $this->fileExtensions)) {
            $this->errors['image'] = "This file extension is not allowed. Please upload a JPEG or PNG file";
        }

        if ($fileSize > 2000000) {
            $this->errors['image'] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        }

        if ( ! empty($errors)) {
            $data['success'] = false;
            $data['errors']  = $errors;
        } else {

            $data = $this->fileUpload;
        }

        echo json_encode($data);
    }

    private function fileUpload () {

        $this->checkDir($this->uploadDirectory);

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

    private function checkDir($directoryName) {
        if( ! is_dir($directoryName)) {
            mkdir($directoryName, 0775);
        }
    }
}