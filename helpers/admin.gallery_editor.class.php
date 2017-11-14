<?php
class gallery_editor extends db{
    
    private $validator, $zip;
    private $allowed_extensions = array (
	  	'gif',
	  	'png',
	  	'jpg',
	  	'jpeg'
    );
    
	public function __construct(){
		$this->connect();
		$this->validator = new validation;
        $this->zip = new ZipArchive;
	}
	
	public function task_manager(){
        if(isset($_POST['leiras_update_x'])) $this->leirasUpdate();
        if(isset($_POST['send_zip'])){
            $this->zip_process();
        }
		elseif(isset($_POST['send_pic'])){
            $this->save_now();
        }
		elseif(isset($_POST['pic_up_x']))$this->pic_up($_POST['sorrend'],$_POST['modid']);
		elseif(isset($_POST['pic_down_x']))$this->pic_down($_POST['sorrend'],$_POST['modid']);
		elseif(isset($_GET['del']))$this->del($_GET['del']);
		return $this->edit_now($_GET['mid']);
	}
    
    private function leirasUpdate() {
        //data
        $id     =$this->validator->check_input($_POST['modid'],1);
        $leiras =$this->validator->check_input($_POST['leiras'],1);
        
        $this->q("UPDATE gallery SET leiras='".$leiras."' WHERE id='".$id."'");
    }
    
	public function pic_up($sorrend,$id){
        //data
        if(isset($_GET['mid'])){
            $katid     =$this->validator->check_input($_GET['mid'],1);
        }
        
		if($elozo =$this->onerow("SELECT * FROM gallery WHERE sorrend<'".$sorrend."' AND katid='".$katid."' ORDER BY sorrend DESC LIMIT 1"))
		{
			$this->q("UPDATE gallery SET sorrend='".$elozo['sorrend']."' WHERE id='".$id."'");
			$this->q("UPDATE gallery SET sorrend='".$sorrend."' WHERE id='".$elozo['id']."'");
		}
	}
	
	public function pic_down($sorrend,$id){
        //data
        if(isset($_GET['mid'])){
            $katid     =$this->validator->check_input($_GET['mid'],1);
        }
		if($kov =$this->onerow("SELECT * FROM gallery WHERE sorrend>'".$sorrend."' AND katid='".$katid."' ORDER BY sorrend ASC LIMIT 1")){
			$this->q("UPDATE gallery SET sorrend='".$kov['sorrend']."' WHERE id='".$id."'");
			$this->q("UPDATE gallery SET sorrend='".$sorrend."' WHERE id='".$kov['id']."'");
		}
	}
	
    public function edit_now(){
		global $Site_Name;
        if(isset($_GET['mid'])){
            $_mid     =$this->validator->check_input($_GET['mid'],1);
        }
        $data=$this->onerow("SELECT * FROM site WHERE id='".$_mid."'");
        
        
        $data=$this->onerow("SELECT * FROM site WHERE id='".$_mid."'");
        $out ='<h1><img src="assets/icon_big/page_copy.png" alt=""/>Galéria</h1>';
        $out .='<div class="btn-group">';
            if($this->onedata("SELECT marker FROM site WHERE id='".$data['pid']."' AND marker=13")){
                $out .='<a class="btn btn-primary" href="?type=pages&amp;mid='.$data['pid'].'&amp;cikk=lista">Vissza a geléria tárba</a>';
            }
            $out .='<a class="btn btn-primary" href="?type=editor&mid='.$_mid.'">Tartalom szerkesztése</a>';
        $out .='</div>';
        $out .='<div id="right_panel_content">';
                $out .='<div id="edit">';
                    $out .='<h2> '.$data['title'].'</h2>';
                    
                    $out .="<form action =\"\" method=\"post\" enctype=\"multipart/form-data\">";
            		$out .='<label>Oldal címe:</label><td><input type="text" name="cim" class="bevitel" value="'.$data['title'].'"/>';
                    $out .='<div class="fileupload fileupload-new" data-provides="fileupload">
  <div class="input-append">
    <div class="uneditable-input span3">
    <i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span>
    </div>
    <span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="uj_kep" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
  </div>
</div>';
                    
            		$out .='<label>Képaláírás:</label><input type="text" name="kepalairas" class="bevitel"/>';
            		$out .='<br /><input type="submit" name="send_pic" class="btn" value="Ment" onclick="showLoading();"/>';
                    $out .='<label>Zip:</label>
                    <div class="fileupload fileupload-new" data-provides="fileupload">
  <div class="input-append">
    <div class="uneditable-input span3">
    <i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span>
    </div>
    <span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="uj_kep" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
  </div>
</div></td></tr>';
                    $out .='<input name="send_zip" type="submit" class="btn" value="Ment" onclick="showLoading();"/>';
            		$out .="</form>";
                    $out .=$this->get_list($_mid);
                $out .='</div>';
                if($this->onedata("SELECT marker FROM site WHERE id='".$data['pid']."' AND marker=13")){
                    $out .='<div class="well">';
                        $out .='<h2>Kezdőkép feltöltése a galériához</h2>';
                        $out .=$this->title_pic($_mid);
                    $out .='</div>';
                }
		$out .='</div>';
        
		return $out;
    }
    
    public function title_pic($id){
        if($_POST['send_title_pic']) {
           return $this->save_title_pic($id);
        }
		$u=$this->onerow("SELECT * FROM site".$_SESSION['a_lang']." WHERE id='".$id."'");
		$out .='<h2> '.$u['title'].' - galéria kezdő kép</h2>';
		$out .="<form action =\"\" method=\"post\" enctype=\"multipart/form-data\">";
		$out .='<div class="gall_table">';
		$out .='<p>Kép: (*.png,*.jpg,*.bmp) <br /> <input type="file" name="uj_kep" class="bevitel"/></p>';
		$out .="<p><input type=\"submit\" name=\"send_title_pic\" class=\"gomb\" value=\"Ment\" onclick=\"showLoading();\"/></p>";
        $out .='</div>';
        if($u['title_img'] && is_file('../data/cikk_img/'.$id.'.jpg')){
            $out .='<div class="gall_table">';
	           $out .='<a class="title_pic" href="../data/cikk_img/'.$u['title_img'].'.jpg" rel="lightbox"><img src="../data/cikk_img/'.$u['title_img'].'_tn.jpg" alt="" border="0"/></a>';
               $out .='<p><a href="?type=editor&amp;mid='.$_GET['mid'].'&amp;cikk=edit&amp;cikk_id='.$id.'&amp;pic=del" onclick="return confirm(\'Biztosan Törli a képet?\');"><img border="0" src="assets/icon/picture_delete.png" alt=""/></a></p>';
            $out .='</div>';
        }
		$out .="</form>";
		return $out;
	}
    
    
    
    public function save_title_pic($cikk_id=NULL,$mid=null){
		global $Gal_lp_width,$Gal_lp_height,$Gal_bp_width,$Gal_bp_height,$Gal_c_width,$Gal_c_height;
        
		if(
            is_uploaded_file($_FILES['uj_kep']['tmp_name']) 
            && $this->get_type($_FILES['uj_kep']['type']) 
            && getimagesize($_FILES['uj_kep']['tmp_name'])
        ){

            if(!is_dir('../data/cikk_img')){
                mkdir('../data/cikk_img', 0775);
            }
            
            $this->q("UPDATE site".$_SESSION['a_lang']." SET title_img='".$cikk_id."' WHERE id='".$cikk_id."'");
			
            $this->resize_image(
                $_FILES['uj_kep']['tmp_name'],
                $cikk_id.".jpg",
                $Gal_bp_width,
                $Gal_bp_height,
                '../data/cikk_img/',
                '',
                "width"
            );
            
            $this->resize_image(
                $_FILES['uj_kep']['tmp_name'],
                $cikk_id."_tn.jpg",
                $Gal_lp_width,
                $Gal_lp_height,
                '../data/cikk_img/',
                '',
                "width"
            );
            if($mid){
                header("Location: ?type=editor&mid=".$mid."&cikk=edit&cikk_id=".$cikk_id);
            }else{
                header("Location: ?type=gallery_editor&mid=".$cikk_id);
            }
		}
	}	
	
	public function del_title_pic($id){
        $this->q("UPDATE site".$_SESSION['a_lang']." SET title_img='' WHERE id='".$cikk_id."'");
		if(is_file('../data/cikk_img/'.$id.'_tn.jpg')){
            unlink('../data/cikk_img/'.$id.'_tn.jpg');
        }
        if(is_file('../data/cikk_img/'.$id.'.jpg')){
            unlink('../data/cikk_img/'.$id.'.jpg');
        }
	}
    
    public function zip_process(){
		global $Gal_lp_width,$Gal_lp_height,$Gal_bp_width,$Gal_bp_height,$Gal_c_width,$Gal_c_height;
        if(isset($_GET['mid'])){
            $_gallery_id=$this->validator->check_input($_GET['mid'],1);
        }
		if(is_uploaded_file($_FILES['zipfile']['tmp_name'])){
			move_uploaded_file($_FILES['zipfile']['tmp_name'],"temp/temp.zip");
            
            $this->zip->open('temp/temp.zip');
            $this->zip->extractTo('temp');
            unlink("temp/temp.zip");
			$dz = opendir("temp/");
			while ($fn = readdir($dz)) {
				$f="temp/".$fn;
                $file= array(
                    'name'      => $fn,
                    'type'      => mime_content_type($f),
                    'tmp_name'  => "temp/".$fn
                );
				if($this->upfile_check($file)){
                    $this->pic_upload('../gallery/',$_gallery_id,$this->_gallery_dir($_gallery_id),$f);
				}
				if(is_file($f))unlink($f);
			}
        }
		header("Location: ?type=gallery_editor&mid=".$_gallery_id);
		exit();
	}
    
    public function _gallery_dir($id){
        
        return 'gall_'.$id;
    }
    
    public function save_now(){
		global $Gal_lp_width,$Gal_lp_height,$Gal_bp_width,$Gal_bp_height,$Gal_c_width,$Gal_c_height;
       
        if(isset($_GET['mid'])){
            $_gallery_id=$this->validator->check_input($_GET['mid'],1);
        }
        if(isset($_POST['kepalairas'])){
            $_label = $this->validator->check_input($_POST['kepalairas'],1);
        }
		if(isset($_POST['cim'])){
            $_cim = $this->validator->convert_title($_POST['cim'],1);
            $this->q("UPDATE `site` SET `title`='".$_cim."' WHERE `id`='".$_gallery_id."'");
        }
		if(is_uploaded_file($_FILES['uj_kep']['tmp_name'])         
        && $this->upfile_check($_FILES['uj_kep'])){
            
            $this->pic_upload('../gallery/',$_gallery_id,$this->_gallery_dir($_gallery_id),$_FILES['uj_kep']['tmp_name'],$_label);
		}
	}
    
    public function pic_upload($root_dir,$gallery_id=null,$dirname=null,$file,$leiras=null,$ws=null){
        global $Gal_lp_width,$Gal_lp_height,$Gal_bp_width,$Gal_bp_height,$Gal_c_width,$Gal_c_height;
        
        $g_dir=$this->search("stitle","site","id='".$gallery_id."'");
        if(!is_dir($root_dir)){
            mkdir($root_dir, 0775);
        }
        if(!is_dir($root_dir.$dirname)){
            mkdir($root_dir.$dirname, 0775);
        }
        if($gallery_id){
            $max=$this->onedata("SELECT max(sorrend) AS max FROM gallery WHERE katid='".$gallery_id."'");
            $this->q("INSERT INTO `gallery` ( `id` , `katid` , `leiras`, `sorrend`, `sorrend_ok`) VALUES (NULL , '".$gallery_id."', '".$leiras."', '".($max+1)."', 'ok')");
        }
		$ujid=$this->last_id();
		if(!empty($ws)){
            $this->resize_image(
                $file,
                $ws.'.jpg',
                $Gal_bp_width,
                $Gal_bp_height,
                $root_dir,
                $dirname
            );
            $this->resize_image(
                $file,
                $ws.'_mini.jpg',
                $Gal_lp_width*1.5,
                $Gal_lp_height*1.5,
                $root_dir,
                $dirname
            );
            $this->resize_image(
                $file,
                $ws.'_tn.jpg',
                $Gal_c_width*1.5,
                $Gal_c_height*1.5,
                $root_dir,
                $dirname
            );
		}else{
            $this->resize_image(
                $file,
                $ujid.'_bp.jpg',
                $Gal_bp_width,
                $Gal_bp_height,
                $root_dir,
                $dirname
            );
            $this->resize_image(
                $file,
                $ujid.'_tn.jpg',
                $Gal_lp_width,
                $Gal_lp_height,
                $root_dir,
                $dirname
            );
        }
        
    }
    
    function get_type($t){
		switch($t){
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
    
    private function _extension($file_name) {
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
    
	public function upfile_check($file){
        $file_name=$file['name'];
        $file_type=$file['type'];
        $file_data=$this->_extension($file_name);
        foreach($this->allowed_extensions as $valid_type){
            
            if($valid_type == strtolower($file_data['file_extension'])){
                
                if(
                    ($file_type=='image/jpeg') || 
                    ($file_type=='image/png') ||
                    ($file_type=='image/gif') ||
                    ($file_type=='image/pjpeg')
                ){
                    if(getimagesize($file['tmp_name'])) {
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
        
        if($file['name']=='.htaccess' || $f['type']=='application/x-php'){
			$this->alert_message('tiltott fájl feltöltés:',$f);
        }
        return false;        
	}

	public function get_list($katid=1){
		$Gal_p_column_nums=3;
		$osszevont_bal=floor($Gal_p_column_nums/2);
		$osszevont_jobb=ceil($Gal_p_column_nums/2);
        $out='';
        
        if(isset($_GET['mid'])){
            $_gallery_id = $this->validator->check_input($_GET['mid'],1);
        }
		if($data=$this->alldata("SELECT id,sorrend,sorrend_ok,leiras FROM `gallery` WHERE katid='".$_gallery_id."' ORDER BY sorrend ASC")){
			$ossz=count($data);
			$i=1;
			$out .= "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" align=\"center\" width=\"100%\">";
			foreach($data as $k => $v){
                if($v['sorrend']==0 || $v['sorrend_ok']!='ok'){
                        $this->q("UPDATE gallery SET sorrend='".($k+1)."', sorrend_ok='ok' WHERE id='".$v['id']."'");
                }
				if($k%$Gal_p_column_nums==0)$out .= "<tr valign=\"top\">\n";
				$out .="<td align=\"center\">";
			$out .='<form action="" method="post" enctype="multipart/form-data">';
			$out .='<table><tr><td rowspan="2">';
				$out .='<a href="../gallery/'.$this->_gallery_dir($_gallery_id).'/' . $v['id'] . '_bp.jpg" rel="lightbox" title="'.$v['leiras'].'">';
				$out .='<img class="gal_img" src="../gallery/'.$this->_gallery_dir($_gallery_id).'/'. $v['id'] . '_tn.jpg" ></a><br>';
			$out .='</td><td>';
			if($i!=1)	$out .='<input type="image" src="assets/icon/arrow_up.png" name="pic_up" />';
			$out .='</td></tr><tr><td>';
			if($i!=$ossz)$out .='<input type="image" src="assets/icon/arrow_down.png" name="pic_down"/>';
			$out .='</td></tr></table>';
			$out	.='<input type="hidden" name="sorrend" value="'.$v['sorrend'].'" />';
			$out	.='<input type="hidden" name="modid" value="'.$v['id'].'" />';
			$out .='</form>';	
				    $out .="<div id=\"g".$v['id']."\" class=\"gal_leiras\">";
                        $out .='<form action="" method="post" enctype="multipart/form-data">';
                            $out .='<input type="text" value="'.$v['leiras'].'" name="leiras" class="bevitel"/>';
                            $out .='<input type="image"  src="assets/icon/arrow_refresh.png" name="leiras_update" class="link_button" alt="Frissítés" title="Frissítés"/>';
                            $out .='<input type="hidden" name="modid" value="'.$v['id'].'" />';
                        $out .='</form>';
                        $out .="<a class=\"btn btn-danger\"href=\"?type=gallery_editor&amp;del=".$v['id']."&amp;mid=".$_gallery_id."\" onclick=\"return confirm('Biztosan Törli a képet?');\"><i class=\"icon-trash icon-white\" /></i></a>";
					$out.="</div>";
					$out.="</td>";
				if($k%$Gal_p_column_nums==$Gal_p_column_nums-1)$out .= "</tr>\n";
				$i++;
			}
			while($k%$Gal_p_column_nums!=$Gal_p_column_nums-1){
				$out .= "<td>&nbsp;</td>";
				$k++;
			}
			 if($k%$Gal_p_column_nums==$Gal_p_column_nums-1)$out .= "</tr>";
			else $out .= "<tr><td colspan=\"3\"></td></tr>";
			$out .= "</table>";
		}
		return $out;
    }	

	
		
    
	public function del($id){
		$this->q("DELETE FROM gallery WHERE id='".$id."'");
        
        $_gallery_id = $this->validator->check_get('mid',1);
        
		if(is_file('../gallery/'.$this->_gallery_dir($_gallery_id).'/'.$id.'_tn.jpg')) unlink('../gallery/'.$g_dir['stitle'].'/'.$id.'_tn.jpg');
		if(is_file('../gallery/'.$this->_gallery_dir($_gallery_id).'/'.$id.'_c.jpg'))unlink('../gallery/'.$g_dir['stitle'].'/'.$id.'_c.jpg');
        if(is_file('../gallery/'.$this->_gallery_dir($_gallery_id).'/'.$id.'_bp.jpg'))unlink('../gallery/'.$g_dir['stitle'].'/'.$id.'_bp.jpg');
        if(is_file('../gallery/'.$this->_gallery_dir($_gallery_id).'/'.$id.'.jpg'))unlink('../gallery/'.$g_dir['stitle'].'/'.$id.'.jpg');
	}
    public function search($param=NULL,$table=NULL,$where=NULL){
        return $this->onerow("SELECT ".$param." FROM ".$table." WHERE ".$where);
    }
    
    public function resize_image($img,$img_name,$img_width,$img_height,$gallery_dir=null,$img_dir=null,$mod=null){
        $img=new Thumbnail($img);
    		if(!$mod){
                $img->size($img_width,$img_height);
            }
            elseif($mod='width'){
                $img->size_width($img_width);
    		}
            elseif($mod='height'){
                $img->size_height($img_height);
    		}
    		elseif($mod='crop'){
                $img->cropImage($img_width,$img_width);
            }
            if(!empty($watermark)){
                $img->img_watermark=$watermark['pic'];
                $img->img_watermark_Valing=$watermark['v'];
                $img->img_watermark_Haling=$watermark['h'];
            }
            $img->output_format='JPG';
            $img->process();
            $img->save($gallery_dir.$img_dir."/".$img_name);
        unset($img);
    }
	
}
?>