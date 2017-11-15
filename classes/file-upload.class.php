<?php

require_once ('vendor/MysqliDb.php');

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

    private $db;

    public function __construct($postData, $postImage) {
        echo $this->init($postData, $postImage);
    }


    private function init($postData, $postImage) {

        $this->db = new MysqliDb ('localhost', 'root', '', 'upload_image');

        $this->fileName = $postImage['image']['name'];
        $this->fileSize = $postImage['image']['size'];
        $this->fileTmpName  = $postImage['image']['tmp_name'];
        $this->fileType = $postImage['image']['type'];
        
        $tmp = explode('.',  $this->fileName);
        $this->fileExtension = strtolower(end($tmp));

        $newName = '' . $this->checkId() . '.' . $this->fileExtension . '';
        $this->uploadPath = $this->uploadDirectory . basename($newName);

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

            if($data['success'] == true) {

                $insertData = Array (
                    "title" => $postData['title'],
                    "description" => $postData['description'],
                    "url" => $this->uploadPath
                );

                $this->db->insert ('gallery', $insertData);
            }
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
            $data['errors']['image']  = "An error occurred somewhere. Try again or contact the admin";
        }

        return $data;
    }

    private function dirCheck($directoryName) {
        if( ! is_dir($directoryName)) {
            mkdir($directoryName, 0775);
        }
    }

    private function checkId() {
        $gallery = $this->db->get ("gallery");
        $max = $this->db->count;

        if($max > 0) {
            $maxId = $gallery[$max-1]['id'];
            return $maxId + 1;
        } else {
            return 1;
        }
    }
}