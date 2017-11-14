<?php

class Helper {


    private $allowed_extensions = array (
        'gif',
        'png',
        'jpg',
        'jpeg'
    );

    public function __construct() {
    }
    
    public function get_type($fileType){
		switch($fileType){
            //Jpeg type
			case "image/jpeg";
				return "jpg";
            case "image/pjpeg";
				return "jpg";
            //Png type
			case "image/png"; 
				return "png";
            case "image/x-png"; 
				return "png";
			default: return NULL;
		}
    }
    
    public function _extension($file_name) {
        if(!empty($file_name)){
            
            $parts = explode('.', $file_name);
            $ext = end($parts);
            $filenameSansExt=str_replace('.'.$ext,"",$file_name);
        }
        return array(
            "file_name"=>$filenameSansExt,
            "extension"=>'.'.strtolower($ext),
            "file_extension"=>strtolower($ext)
        );
        
	}    

}