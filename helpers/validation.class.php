<?php
class validation {
    
    
    public function __construct(){
	}
    
    
    /**
     * validation::clear_input()
     * 
     * @param mixed $data
     * @param mixed $db_clear
     * @param mixed $type
     * @return
     */
    /*public function clear_input($data,$db_clear=null,$type=null) {
        //$data = $this->normalize_input($data,$type);        
        return $this->check_input($data,$db_clear);
    }*/

    /**
     * validation::check_input()
     * 
     * @param mixed $data
     * @param mixed $db_clear
     * @return
     */
    public function check_input($data,$db_clear=null){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        if($db_clear){
            $data = mysql_real_escape_string($data);
        }
        return $data;
    }

    /**
     * validation::check_data()
     * 
     * @param mixed $data
     * @return
     */
    public function check_data($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = mysql_real_escape_string($data);
        return $data;
    }
    
    /**
     * validation::convert_title()
     * 
     * @param mixed $data
     * @param mixed $db_clear
     * @return
     */
    public function convert_title($data,$db_clear=null){
        $data = $this->check_input($data,$db_clear);
        return str_replace('&amp;', '&', $data);
    }
    
    /*form validate*************************************************/
    
    /**
     * Validation::check_email()
     * 
     * @param mixed $data
     * @return
     */
    public function check_email($data) {
        if(preg_match('/[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|gov|mil|biz|info|name|aero|biz|info|mobi|jobs|museum|mobil|me)\b/i', $data)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * validation::check_login_name()
     * 
     * @param mixed $data
     * @return
     */
    public function check_login_name($data) {
        if(preg_match('/^[a-zA-Z0-9_.]{1,60}$/', $data)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function check_password($data){
        if(preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(^[a-zA-Z0-9@\$=!:.#%]+$)/',$data)){
            return TRUE;
        } else {
            return FALSE;
        } 
    }
    
    /**
     * Validation::check_phone()
     * 
     * @param mixed $data
     * @return
     */
    public function check_phone($data) {
        if(preg_match('/^([\+][0-9]{1,3}[ \.\-\/])?([\ \(]{1,2}?[0-9]{2,6}[\)])?([0-9 \.\-\/]{3,20})((x|ext|extension)[ ]?[0-9]{1,4})?$/', $data)){
           return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Validation::check_phone2()
     * 
     * @param mixed $data
     * @return
     */
    public function check_phone2($data) {
        if(preg_match('/^[0-9\+\.\-\(\)\/]{1,}[0-9\+\.\-\(\)\/ ]*?$/', $data)){
           return TRUE;
        } else {
            return FALSE;
        }
    }
       
    /**
     * Validation::check_date()
     * 
     * @param mixed $data
     * @return
     */
    public function check_date($data) {
        if(preg_match('/^(19|20)[0-9]{2}[- \/.][ ]?(0[1-9]|1[012])[- \/.][ ]?(0[1-9]|[12][0-9]|3[01])[.]?$/', $data)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Validation::check_name()
     * 
     * @param mixed $data
     * @return
     */
    public function check_name($data) {
        if(preg_match("/^([a-zA-ZáéíóöőúüűÁÉÍÓÖŐÚÜŰ]{1,24}?[- \/.]?[ ]?)*$/", $data)) {
            return TRUE;
        } else {
            return FALSE;
        } 
    }
    
    /**
     * Validation::check_number()
     * 
     * @param mixed $data
     * @return
     */
    public function check_number($data) {
        if(is_numeric($data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Validation::check_string()
     * 
     * @param mixed $data
     * @return
     */
    public function check_string($data) {
        if(is_string($data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * Validation::convert_date()
     * 
     * @param mixed $data
     * @return
     */
    public function convert_date($data){
        $date_year=substr($data,0,4); 
        $date_month=substr($data,5,2); 
        $date_day=substr($data,8,2);
        return $date_year.'-'.$date_month.'-'.$date_day;
    }
    
    public function check_size($data,$min=null,$max=null) {
        if($min){
            if(strlen($data)<$min){
                return FALSE;
            }
        }
        if($max){
            if(strlen($data)>$max){
                return FALSE;
            }
        }
        return TRUE;
    }
    /***************************************************************/
    private $normalizeChars = array( 
            'Á'=>'a', 'À'=>'a', 'Â'=>'a', 'Ã'=>'a', 'Å'=>'a', 'Ä'=>'a', 'Æ'=>'ae', 'Ç'=>'c', 
            'É'=>'e', 'È'=>'e', 'Ê'=>'e', 'Ë'=>'e', 'Í'=>'i', 'Ì'=>'I', 'Î'=>'i', 'Ï'=>'i', 'Ð'=>'eth', 
            'Ñ'=>'n', 'Ó'=>'o', 'Ò'=>'o', 'Ô'=>'o', 'Õ'=>'o', 'Ö'=>'o', 'Ő'=>'o', 'Ø'=>'o', 
            'Ú'=>'U', 'Ù'=>'u', 'Û'=>'U', 'Ü'=>'u', 'Ű'=>'u', 'Ý'=>'y', 
    
            'á'=>'a', 'à'=>'a', 'â'=>'a', 'ã'=>'a', 'å'=>'a', 'ä'=>'a', 'æ'=>'ae', 'ç'=>'c', 
            'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e', 'í'=>'i', 'ì'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'eth', 
            'ñ'=>'n', 'ó'=>'o', 'ò'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ő'=>'o', 'ø'=>'o', 
            'ú'=>'u', 'ù'=>'u', 'û'=>'u', 'ü'=>'u', 'ű'=>'u', 'ý'=>'y', 
            
            'ß'=>'sz', 'þ'=>'thorn', 'ÿ'=>'y' 
    ); 
     
    /**
     * validation::make_sort_title()
     * 
     * @param mixed $name
     * @return
     */
    public function make_sort_title($name) {
        $name   =   strtr($name, $this->normalizeChars);
        $name   =   str_replace('&amp;', '&', $name);
        $name   =   str_replace('&', 'and', $name);
        $name   =   trim(preg_replace('/[^\w\d_ -]/si', '', $name));//remove all illegal chars
        $name   =   str_replace(' ', '_', $name);
        $name   =   str_replace('--', '-', $name);
        
        
        $name   =   strtolower($name);

        return $name;
    }
}

?>