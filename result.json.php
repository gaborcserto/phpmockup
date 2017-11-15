<?php
require_once ('classes/vendor/MysqliDb.php');

class jsonData {
    private $db;
    private $result;

    public function __construct() {
        $this->db = new MysqliDb ('localhost', 'root', '', 'upload_image');

        echo $this->resultData();
    }

    private function resultData() {
        $gallery = $this->db->get ("gallery");
        $tempArray['data'] = $gallery;
        return json_encode($tempArray);
    }
}

new jsonData();