<?php
/*!
 * FileManager (PHP/JS)
 * version: 2.4.4
 * Requires PHP v5.0 or later
 * Copyright (c) 2018 Tanzer DEMİR
 * Examples and documentation at: https://apps.tanzerdemir.net/#file_manager_v2/
 * https://tanzerdemir.net
*/
namespace FMP;
if(empty(FileManager)) exit();
require_once(__DIR__ .'/func/_mime_content_type.php');
class fmp{
	private $mainFolder = false;
	public $managerFolder = __DIR__;
	private $server;
	private $tempImage = 0;
	private $configXML = false;
	private function realpath($path,$sep=DIRECTORY_SEPARATOR,$type=true,$xxx=false){
		if(!$this->mainFolder && $type){
			$mf = '/'.$this->config('mainFolder').'/';
			$this->server = realpath($_SERVER['DOCUMENT_ROOT'].$mf).$sep;
			$this->mainFolder = str_replace(realpath($_SERVER['DOCUMENT_ROOT']),'',$this->server);
		}
		if($type){
			$mf = str_replace(array('/', '\\', DIRECTORY_SEPARATOR), $sep, $this->mainFolder);
			if(strlen($mf)>2){
				$path = str_replace(array('/', '\\', DIRECTORY_SEPARATOR), $sep, $path);
				$path = str_replace($mf,'',$path);
			}
		}
		$server=$this->server;
		$path = str_replace(array('/', '\\', DIRECTORY_SEPARATOR), $sep, ($type?$server:'').$path);
		$parts = array_filter(explode($sep, $path), 'strlen');
		$url = array();
		foreach ($parts as $part) {
			if ($part=='.' || empty($part)) continue;
			if ($part=='..') array_pop($url);
			else $url[] = $part;
		}
		$x = $type;
		if(strtoupper(substr(PHP_OS,0,3))=='WIN') $type=false;
		if($xxx && strtoupper(substr(PHP_OS,0,3))!='WIN')$type = true;
		$y = '';
		if(substr($server,0,2)==$sep.$sep) $y = $sep.$sep;
		elseif(substr($server,0,1)==$sep) $y = $sep;
		$url = ($type?$y:'').implode($sep, $url);
		if($x and strlen($url.$sep)>=strlen($server) AND substr($url.$sep,0,strlen($server))==$server) return $url; //For inject
		if(!$x) return $url;
		exit($this->error($url,'Op: Path. Path error','NotFound')); 
	}
	private function config($id){
		$xml = $this->managerFolder.'/xml/config/config.xml';
		if(!$this->configXML){
			if(file_exists($xml)){
				$xmlc = simplexml_load_file($xml, "SimpleXMLElement", LIBXML_NOCDATA);
				$this->configXML = json_decode(json_encode($xmlc),true);
			}
		}
		if(is_array($this->configXML)){
			if(!empty($this->configXML[$id])) return $this->configXML[$id];
			else{
				$this->error($xml,'Op: Config Load. config.xml in id "'.$id.'" not found.','xmlIdNotFound');
				return $id;
			}
		}
		else exit($this->error($xml,'Op: Config Load. config.xml not found.','xmlNotFound'));
	}
	private function sizeText($path){
		$size = $this->size($path);
		$units = array('byte','kilobyte','megabyte','gigabyte','terabyte','petabyte');
		for ($i = 0; $size > 1024; $i++) $size /= 1024;
		return substr($size,0,strpos($size,".")+3).' '.$units[$i];
	}
	private function size($path){
		$size = 0;
		if(is_file($path)) $size = filesize($path);
		else foreach (glob(rtrim($path,'/').'/*',GLOB_NOSORT) as $each) $size += is_file($each) ? filesize($each) : $this->size($each);
		return $size;
	}
	private function url($path){
		$path = str_replace(array('\\',DIRECTORY_SEPARATOR),'/',$path);
		$server = substr(str_replace(array('\\',DIRECTORY_SEPARATOR),'/',$this->server),0,-1);
		while(substr($server,-1)=='/') $server = substr($server,0,-1);
		$path = str_replace($server,'',$path);
		$path = $this->realpath($path,'/',false);
		// $path = $this->mainFolder.$path;
		if(substr($path,0,1)!='/') $path = '/'.$path;
		return $path;
	}
	private function error($path,$text,$xml = 'fileNotSupport',$re=false){
		$file = __DIR__ .'/fm_error_log.txt';
		if(file_exists($file)) touch($file);
		$file = fopen($file,'aw');
		fwrite($file,'['.date('r').']'.' - Path: "'.$path.'" - '.$text."\r\n");
		fclose($file);
		if($re) return false;
		return $xml.'<br/><b>"'.$path.'"</b>';
	}
	private function deleteFolder($path){
		if(!file_exists($path)) return false;		
		$dir = opendir($path); 
		while(false !== ($file = readdir($dir))){ 
			if($file != '.' && $file!='..'){ 
				if(is_dir($path.'/'.$file))	$this->deleteFolder("$path/$file");
				else unlink("$path/$file"); 
			}
		}
		closedir($dir);
		return rmdir($path); 	
	}
	private function newName($path,$name){
		if(!file_exists($path)){
			$this->error($path,'Op: New Name. This folder not found!','notFoundFolder');
			return false;
		}
		$name=explode('/',$name);
		$name=$name[count($name)-1];
		if(!file_exists($path.'/'.$name)) return $name;
		$x = 2;
		if(is_dir("$path/$name")) $ext = '';
		else{
			$e = explode('.',$name);
			$ext = '.'.$e[count($e)-1];
			unset($e[count($e)-1]);
			$name = implode('.',$e);
		}
		if(substr($name,-1)==')' && strpos($name,'(')!==false){
			$num = substr($name,strrpos($name,'('),-1);
			if(is_numeric($num)){
				$x=$num+1;
				$name = substr($name,0,0-(strlen($name)-strrpos($name,'(')));
			}
		}
		while(file_exists($path.'/'.$name." ($x)$ext")) $x++;
		return $name." ($x)$ext";
	}
	private function checkMainFolder($path){
		if(!file_exists($path)) return false;
		if(strpos($path.DIRECTORY_SEPARATOR,$this->mainFolder)===false) return false;
		return true;
	}
	public function post($name){
		if(isset($_POST[$name])){
			if(is_array($_POST[$name])){
				return array_map(function($item){
					return htmlspecialchars(trim($item));
				},$_POST[$name]);
			}
			return htmlspecialchars(trim($_POST[$name]));
		}
		return false;
	}
	public function tempImage($path,$type=null,$tmp='/assets/tempimg/'){
		$path = $this->realpath($this->url($path));
		$tmp = DIRECTORY_SEPARATOR .$this->realpath($tmp,DIRECTORY_SEPARATOR,false);
		$f = pathinfo($path);
		$dir = $f['dirname'];
		$file = $f['basename'];
		if($type==null) $type = _mime_content_type($path);
		if($this->tempImage<30 && strstr($dir,$tmp)===false && strstr($type,'image')){
			$tmp = __DIR__ .$tmp.DIRECTORY_SEPARATOR;
			$find = md5($path).'.'.pathinfo($path,PATHINFO_EXTENSION);
			if(!file_exists($tmp.$find)){
				if(is_array($size = @getimagesize($path))){
					$imgWidth = $size[0];
					$imgHeight = $size[1];
					$type = $size['mime'];
					switch($type){
						case 'image/gif' : $tmpCreate = imagecreatefromgif($path); break;
						case 'image/jpeg' : $tmpCreate = imagecreatefromjpeg($path); break;
						case 'image/png' : $tmpCreate = imagecreatefrompng($path); break;
					}     
					if(isset($tmpCreate)){
						if(!file_exists($tmp)) mkdir($tmp);
						$tmpWidth = 45;
						$tmpHeight = 45;
						$tmpH = $tmpV = $imgH = $imgV = 0;
						$imgWrite = imagecreatetruecolor($tmpWidth, $tmpHeight);
						if($type=='image/gif' || $type=='image/png'){
							imagealphablending($imgWrite, false);
							imagesavealpha($imgWrite, true);
							}
						imagecopyresampled ($imgWrite, $tmpCreate, $tmpH, $tmpV, $imgH, $imgV, $tmpWidth, $tmpHeight, $imgWidth, $imgHeight);
						imagefilter($imgWrite, IMG_FILTER_GAUSSIAN_BLUR);
						switch($type){
							case 'image/gif' : if(@imagegif($imgWrite, $tmp.$find))	$file = $find; break;
							case 'image/jpeg' : if(@imagejpeg($imgWrite, $tmp.$find, 75))	$file = $find; break;
							case 'image/png' : if(@imagepng($imgWrite, $tmp.$find, 7))	$file = $find; break;
						}
						$this->tempImage++;
						imagedestroy($imgWrite);
					}
				}
			}
			else $file = $find;
		}
		if($file!=$f['basename']) return $file;
		return 'null';
	}
	public function details($path,$type = false){
		$path = $this->realpath($path);
		if(!file_exists($path)) return $this->error($path,'Op: File Detail. This file not found!','notFoundFile');
		// if(!($stat = @stat($path))) $stat['size'] = $this->sizeText($path);
		if(!$type) $type = (is_file($path)?mime_content_type($path):'directory');
		$file = pathinfo($path);
		return array(
			'name'=>$file['basename'],
			'type'=>$type,
			'is_image'=>(strstr($type,'image')?'1':'0'),
			'location'=>$file['dirname'],
			'location2'=>$this->url($path),
			'size'=>$this->sizeText($path),
			'date'=>date('Y-n-d H:i:s a',filemtime($path)),
			'perm'=>substr(sprintf('%o',fileperms($path)),-4)
		);
	}
	public function create($name,$path,$type,$rp=0){
		if(!$rp)$path = $this->realpath($path);
		exit($path);
		if(!file_exists($path)) return $this->error($path,'Op: Create. This folder not found!','notFoundFolder');
		$name = $this->newName($path,$name);
		if($type=='newFile'){
			if(touch($path.'/'.$name)) return $name;
			else {
				$this->error($path.' -> '.$name,'Op: Create. This file not create!','notCreateFile');
				return false;
			}
		}
		elseif($type=='newFolder'){
			if(mkdir($path.'/'.$name)) return $name;
			else {
				$this->error($path.' -> '.$name,'Op: Create. This folder not create!','notCreateFolder');
				return false;
			}
		}
		$this->error($path,'Op: Create. This type not found!','notFoundCommand');
		return false;
	}
	public function fileSave($file,$cont){
		$file = $this->realpath($file);
		if(file_exists($file)){
			if(is_file($file)){
				if(strlen($cont)){
					$xhr = fopen($file, 'w+');
					if(fwrite($xhr, $cont)) $i=1;
					fclose($xhr);
				}
				else{
					if(unlink($file) and touch($file)) $i=1;
				}
				if(isset($i)) return true;
				$this->error($file,'Op: File Save. This file not save!','notSaveFile');
				return false;
			}
			$this->error($file,'Op: File Save. This folder not support!','notSupportFolder');
			return false;
		}
		$this->error($file,'Op: File Save. This file not found!','notFoundFile');
		return false;
	}
	public function listing($path,$type='all',$exp=true){
		$path = ($exp?$this->realpath($path):$this->realpath($path,DIRECTORY_SEPARATOR,false,true));
		if(file_exists($path)){
			$folder = $files = $file = $image = array();
			$scan = opendir($path);
			while(false !== ($s = readdir($scan))){
				if($s!='.' && $s!='..'){
					if(!is_file($path.DIRECTORY_SEPARATOR .$s)) array_push($folder,$s);
					else array_push($files,$s);
				}
			}
			sort($folder);
			$url = $this->url($path);
			$re = array('url'=>$url.(substr($url,-1)!='/'?'/':''),'path'=>$path,'uurl'=>$url);
			switch($type){
				case 'all' :
					require_once "func/_mime_content_type.php";
					foreach($files as $f){
						$type = _mime_content_type($path.DIRECTORY_SEPARATOR .$f);
						if(substr($type,0,5)=='image'){
							if(@in_array(@exif_imagetype($path.DIRECTORY_SEPARATOR .$f),array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF)))	array_push($image,array($f,$type,$this->tempImage($path.DIRECTORY_SEPARATOR .$f,$type)));
							else array_push($file,array($f,$type));
						}
						else array_push($file,array($f,$type));
					}
					sort($file);
					sort($image);
					$re['folders'] = $folder;
					$re['images'] = $image;
					$re['files'] = $file;
				break;
				case 'folder' :
					$re['folders'] = $folder;
				break;
				case 'image' :
					$re['images'] = $image;
				break;
				case 'file' :
					$re['files'] = $file;
				break;
			}
			return $re;
		}
		return $this->error($path,'Op: Directory List. Directory not found!','notFoundFolder');
	}
	public function folderList($dir=''){
		$dir = str_replace($this->realpath(''),'',$dir);
		$dir = $this->realpath($dir);
		if(strlen($dir)>0 && substr($dir,-1)!=DIRECTORY_SEPARATOR) $dir .= DIRECTORY_SEPARATOR;
		if(is_dir($dir)){
			$u = '';
			$files = scandir($dir);
			$ff = array();
			asort($files);
			foreach($files as $file){
				if($file!='.' && $file!='..' && is_dir($dir.$file)){
					array_push($ff,$dir.$file);
					array_push($ff,$this->folderList($dir.$file));
				}
			}
			$s = implode(',',array_filter($ff));
			return $s;
		}
		$this->error($dir,'Op: Folder List. Folder not found!','notFoundFolder');
		return false;
	}
	public function download($path){
		$path = $this->realpath($path);
		if(file_exists($path)){
			if(is_file($path)){
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($path).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($path));
				readfile($path);
				exit;
			}
			exit('Directory download not support!');
		}
		else exit($this->error($path,'Op: File Download. File not found!','notFoundFile'));
	}
	public function show($path){
		$path2 = $this->realpath($path);
		if(file_exists($path2)){
			if(is_file($path2)){
				if(substr(mime_content_type($path2),0,5)!='image' and filesize($path2)>1048576) exit($this->download($path));
				header('Content-Type: '.mime_content_type($path2));
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($path2));
				readfile($path2);
				exit;
			}
			exit('Directory show not support!');
		}
		else exit($this->error($path2,'Op: File show. File not found!','notFoundFile'));
	}
	public function delete($paths){
		if(!is_array($paths)) $paths = array($paths);
		$err = $scc = array();
		foreach($paths as $path){
			$path = $this->realpath($path);
			if(!file_exists($path)){
				array_push($err,$this->url($path));
				$this->error($path,'Op: File delete. File not found!','notFoundFile');
			}
			else{
				if(is_file($path)){
					if(unlink($path)) array_push($scc,$this->url($path));
					else array_push($err,$this->url($path));
				}
				else{
					if($this->deleteFolder($path)) array_push($scc,$this->url($path));
					else array_push($err,$this->url($path));
				}
			}
		}
		return array('deleted'=>$scc,'undeleted'=>$err);
	}
	public function move($files,$path){
		$dir = $this->realpath($path).DIRECTORY_SEPARATOR;
		$x=0;
		if(!is_array($files)) $files = array($files);
		foreach($files as $file){
			$rf = $this->realpath($file);
			if(!file_exists($rf)) return $this->error($rf,'Op: Move. Not found file!','notFoundFile',1);
			$f = pathinfo($rf);
			$name = $f['basename'];
			if(is_dir($rf)){
				$rf .= DIRECTORY_SEPARATOR;
				if($name != '.' && $name !='..'){
					$to = $this->create($name,$path,'newFolder');
					if($to){
						$dir_ = opendir($rf); 
						while(false !== ($item = readdir($dir_))){ 
							if($item != '.' && $item!='..'){
								if(is_dir($rf.$item)) {
									if($this->move($file.DIRECTORY_SEPARATOR.$item,$path.DIRECTORY_SEPARATOR.$to)) $x++;
									else return $this->error($file.DIRECTORY_SEPARATOR.$item.' -> '.$path.DIRECTORY_SEPARATOR.$to,'Op: Move. Reload function error!','reloadFunction',1);
								}
								else {
									if(rename($rf.$item,$dir.$to.DIRECTORY_SEPARATOR.$item)) $x++;
									else return $this->error($dir.$to.DIRECTORY_SEPARATOR.$item,'Op: Move. Rename function error!','errorMoveFunction',1);
								}
							}
						}
						closedir($dir_);
						@$this->delete($file);
					}
					else return $this->error("$path -> $name",'Op: Move. New folder not created!','notCreateFolder',1);
				}
			}
			else{
				if(rename($rf,$dir.$name)) $x++;
				else return $this->error("$rf -> $dir$name",'Op: Move. Rename function error!','errorMoveFunction',1);
			}
		}
		if($x) return true;
		return $this->error($path,'Op: Move. Error not detect!','errorNotDetect',1);
	}
	public function copy($files,$path){
		$dir = $this->realpath($path).DIRECTORY_SEPARATOR;
		$x=0;
		if(!is_array($files)) $files = array($files);
		foreach($files as $file){
			$rf = $this->realpath($file);
			if(!file_exists($rf)) return $this->error($rf,'Op: Copy. Not found file!','notFoundFile',1);
			$f = pathinfo($rf);
			$name = $f['basename'];
			if(is_dir($rf)){
				$rf .= DIRECTORY_SEPARATOR;
				if($name != '.' && $name !='..'){
					$to = $this->create($name,$path,'newFolder');
					if($to){
						$dir_ = opendir($rf); 
						while(false !== ($item = readdir($dir_))){ 
							if($item != '.' && $item!='..'){
								if(is_dir($rf.$item)) {
									if($this->copy($file.DIRECTORY_SEPARATOR.$item,$path.DIRECTORY_SEPARATOR.$to)) $x++;
									else return $this->error($file.DIRECTORY_SEPARATOR.$item.' -> '.$path.DIRECTORY_SEPARATOR.$to,'Op: Copy. Reload function error!','reloadFunction',1);
								}
								else {
									if(copy($rf.$item,$dir.$to.DIRECTORY_SEPARATOR.$item)) $x++;
									else return $this->error($dir.$to.DIRECTORY_SEPARATOR.$item,'Op: Copy. Copy function error!','errorCopyFunction',1);
								}
							}
						}
						closedir($dir_);
					}
					else return $this->error("$path -> $name",'Op: Copy. New folder not created!','notCreateFolder',1);
				}
			}
			else{
				if(copy($rf,$dir.$name)) $x++;
				else return $this->error("$rf -> $dir$name",'Op: Copy. Copy function error!','errorMoveFunction',1);
			}
		}
		if($x) return true;
		return $this->error($path,'Op: Copy. Error not detect!','errorNotDetect',1);
	}
	public function rename($old,$new){
		$o = $this->realpath($old);
		$f = pathinfo($o);
		if(!file_exists($o)) return $this->error($o,'Op: Rename. Not found file!','notFoundFile',1);
		$name = $this->newName($o,$new);
		if(!$name) return $this->error($o,'Op: Rename. NewName func. error!','error',1);
		if(rename($o,$f['dirname'].DIRECTORY_SEPARATOR.$name)) return $name;
		else return $this->error($o,'Op: Rename. Rename error!','error',1);
	}
	public function upload($files,$path){
		$path = $this->realpath($path);
		$return['done'] = array();		
		$return['error'] = array();		
		$messages = array(
			'successfulUpload',
			'fileUploadMaxFileSizePhp',
			'fileUploadMaxFileSizeForm',
			'fileUploadPartly',
			'fileUploadNotFound',
			'fileUploadErrorUnknow',
			'fileUploadNotFoundTmp',
			'fileUploadNotWrite',
			'fileUploadErrorExtension'
		);
		$ss = $this->config('uploadFileMaxSize') * 1048576;
		$size = 0;
		for($i=0;$i<count($files['name']);$i++){
			$size += $files['size'][$i];
		}
		if(!is_dir($path)) return $this->error($path,'Op: Upload. Folder Not Found!','fileUploadErrorDir');
		if(!$this->checkMainFolder($path)) return $this->error($path,'Op: Upload. Access Denied','accessDenied');
		if(empty($files)) return $this->error($path,'Op: Upload. Upload files not found!',$messages[4]);
		if($size>$ss) return 'fileSizeError<br/>'.$size.' > '.$ss;
		for($i=0;$i<count($files['name']);$i++){
			if($files['error'][$i]!=0) array_push($return['error'], array($files['name'][$i],$messages[$files['error'][$i]]));
			else{
				$name = $this->newName($path,$files['name'][$i]);
				if(move_uploaded_file($files['tmp_name'][$i],$path.'/'.$name)) array_push($return['done'], array($files['name'][$i],$messages[0]));
				else array_push($return['error'], array($files['name'][$i],$messages[5]));
			}
		}
		return $return;
	}
	public function createZip($files,$path){
		$path = $this->realpath($path);
		if(!is_dir($path)) return $this->error($path,'Op: CreateZip. Folder not found!','notFoundFolder');
		if(!$this->checkMainFolder($path)) return $this->error($path,'Op: CreateZip. Access denied.','accessDenied');
		if(empty($files)) return $this->error($path,'Op: CreateZip. Not send files','notFoundCommand');
		if(!is_array($files)) $files = array($files);
		$r['done'] = array();
		$r['error'] = array();
		$zips = array();
		$zipName = false;
		foreach($files as $file){
			$f = $this->realpath($file);
			if(!file_exists($f)) {array_push($r['error'],array($file,'notFoundFile'));continue;}
			if(!$zipName){
				$z = $this->newName($path,basename($file).'.zip');
				if($z) $zipName = $z;
			}
			array_push($zips,$file);
		}
		if(!count($zips)) return $this->error($path,'Op: CreateZip. Not found zip files.'.print_r($r['error'],1),'archiveNotFoundFiles'.'<br/>'.print_r($r['error'],1));
		if(!$zipName) $zipName = time().'.zip';
		$zip = new \ZipArchive();
		$zip->open($path.DIRECTORY_SEPARATOR.$zipName,\ZipArchive::CREATE);
		foreach($zips as $file){
			$f = $this->realpath($file);
			if(is_dir($f)){
				$zip -> addEmptyDir($file);
				$ff = $this->zipDir($file,1);
				foreach($ff['file'] as $s){
					$zip -> addFile($f.DIRECTORY_SEPARATOR.$s, $file.DIRECTORY_SEPARATOR.$s);
					array_push ($r['done'],array($file.DIRECTORY_SEPARATOR.$s,'fileAddZipArchive'));
				}
			} else {
				$zip -> addFile($f,basename($file));
				array_push($r['done'],array($file,'fileAddZipArchive'));
			}
		}
		$zip -> close();
		return $r;		
	}
	private function zipDir($path,$x=0){
		$p = $this->realpath($path);
		$u = '';
		$files = scandir($p);
		$path .= DIRECTORY_SEPARATOR;
		$p .= DIRECTORY_SEPARATOR;
		$fo = $ff = array();
		asort($files);
		foreach($files as $file){
			if($file!='.' && $file!='..'){
				if(is_file($p.$file)) array_push($ff,$p.$file);
				else{
					array_push($fo,$p.$file);
					array_push($ff,$this->zipDir($path.$file));
				}
			}
		}
		$ex = implode('|',array_filter(array_merge($fo,$ff)));
		if(!$x) return $ex;
		$xx = explode('|',$ex);
		$re = array('file'=>array(),'folder'=>array());
		foreach($xx as $f){
			$x = substr($f,strlen($path)+strpos($f,$path));
			if(is_file($f)) array_push($re['file'],$x);
			else array_push($re['folder'],$x);
		}
		return $re;
	}
	public function unZip($file,$path=''){
		$f = array($file,$path);
		$file = $this->realpath($file);
		$path = ($path!=''?$this->realpath($path):dirname($file));
		if(!file_exists($file)) return $this->error($file,'Op: unZip. Zip not found!','notFoundFile',1);
		if(!file_exists($path)) return $this->error($path,'Op: unZip. Folder not found!','notFoundFolder',1);
		if(!$this->checkMainFolder($path)) return $this->error($path,'Op: UnZip. Access denied.','accessDenied',1);
		if(empty($file)) return $this->error($path,'Op: UnZip. Not send files','notFoundCommand',1);
		if(mime_content_type($file)!='application/zip') return $this->error($file,'Op: UnZip. Not support files','fileNotSupport',1);
		$zipName = $this->create('UnZip - '.date('Y-m-d H.i.s'),$path,'newFolder',1);
		if(!$zipName) return $this->error("$file --> ".basename($file),'Op: UnZip. Not create folder','fileNotCreate',1);
		$zip = new \ZipArchive;
		if($zip -> open($file)){
			if($zip -> extractTo($path.DIRECTORY_SEPARATOR.$zipName)) return true;
			else return $this->error($file,'Op: UnZip. Not extract Archive','archiveNotUnarchive',1);
		}
		else return $this->error($file,'Op: UnZip. Not open Archive','archiveNotOpen',1);		
	}
	public function imageCrop($file,$px,$add=false){
		$file = $this->realpath($file);
		if(!file_exists($file)) return $this->error($file,'Op: imageCrop. File not found!','notFoundFile',1);
		$type = mime_content_type($file);
		$path = dirname($file);
		$name = basename($file);
		if(substr($type,0,5)!='image') return $this->error($file,'Op: imageCrop. File not support!','fileNotSupport',1);
		$name = $this->newName($path,($add?$add.'-'.time().'-'.$name:$name));
		$img = imagecreatefromstring(file_get_contents($file));
		if($img===false){imagedestroy($img);return $this->error($file,'Op: imageCrop. File not create!','notCreateFile',1);}
		$px = array(
			(empty($px[0])?0:$px[0]),
			(empty($px[1])?0:$px[1]),
			(empty($px[2])?1:$px[2]),
			(empty($px[3])?1:$px[3])
		);
		$imgc = imagecreatetruecolor($px[2],$px[3]);
		if($imgc===false){imagedestroy($imgc);imagedestroy($img);return $this->error($file,'Op: imageCrop. File not crop!','notCreateFile',1);}
		list($w, $h) = getimagesize($file);
		// imagecopyresized($imgc, $img, 0, 0, $px[0], $px[1], $w, $h, $px[2], $px[3]);
		imagecopyresized($imgc, $img, 0, 0, $px[0], $px[1], $w, $h, $w, $h);
		imagepng($imgc,$path.DIRECTORY_SEPARATOR.$name);
		imagedestroy($imgc);
		imagedestroy($img);
		return true;
	}
}
