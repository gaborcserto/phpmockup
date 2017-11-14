<?php

class fileUpload {

    public $uploadDirectory = '../images/';
    public $errors = [];
    public $fileExtensions = ['jpeg', 'jpg', 'png', 'gif'];
    public $fileData;
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


    private function init($postData) {

        print_r($postData);


        $this->fileData = $this->fileCheck($postData['image']);

        $this->fileName = $fileData['name'];
        $this->fileSize = $fileData['size'];
        $this->fileTmpName  = $fileData['tmp_name'];
        $this->fileType = $this->$fileData['type'];
        $this->fileExtension = strtolower(end(explode('.', $this->fileName)));
        $this->uploadPath = $this->uploadDirectory . basename($this->fileName);

        $this->formCheck($postData);

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

        if ($fileSize > 2000000) {
            $this->errors['image'] = "This file is more than 2MB. Sorry, it has to be less than or equal to 2MB";
        }

        if ( ! empty($errors)) {
            $data['success'] = false;
            $data['errors']  = $errors;
        } else {

            $data = $this->fileUpload;
        }

        return json_encode($data);
    }

    private function fileUpload () {

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

    private function fileCheck($image) {
        if(is_file($image)) {
            $fileData['size'] = filesize($image);
            $fileData['name'] = $image;

            return $fileData;
        }
    }
}