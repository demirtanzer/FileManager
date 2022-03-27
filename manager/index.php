<?php
/*!
 * FileManager (PHP/JS)
 * version: 2.4.4
 * Requires PHP v5.0 or later
 * Copyright (c) 2018 Tanzer DEMİR
 * Examples and documentation at: https://apps.tanzerdemir.net/#file_manager_v2/
 * https://tanzerdemir.net
*/
	if(substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))	ob_start("ob_gzhandler"); 
	else ob_start();
	// if(empty($_SESSION['FileManager'])) exit();
	define('FileManager',true);
	require_once('FileManager.php');
	$fm = new \FMP\fmp();
	if(!$fm->post('path')) exit(http_response_code(404));
	$drm = 0;
	switch($fm->post('op')){
		case 'new':
			if($fm->post('path') && $fm->post('name') && $fm->post('type')){
				$drm = $fm->create($fm->post('name'),$fm->post('path'),$fm->post('type'));
				echo ($drm?1:0);
			}
		break;
		case 'fileSave':
			$drm = $fm->fileSave($fm->post('path'),htmlspecialchars_decode($fm->post('cont')));
			echo (is_array($drm)?json_encode($drm):$drm);
		break;
		case 'imageCrop':
			$arr = array($fm->post('px'),$fm->post('py'),$fm->post('pw'),$fm->post('ph'));
			echo($fm->imageCrop($fm->post('path'),$arr,$fm->post('add'))?1:0);
		break;
		case 'language':
			$xmlList = $fm->languageList();
			$drm = count($xmlList);
			echo (count($xmlList)>0?json_encode($xmlList):'notFoundLanguageFile');
		break;
		case 'detail':
			$drm = $fm->details($fm->post('path'),$fm->post('type'));
			echo (is_array($drm)?json_encode($drm):$drm);
		break;
		case 'list':
			$drm = $fm->listing($fm->post('path'),$fm->post('type'));
			echo (is_array($drm)?json_encode($drm):$drm);
		break;
		case 'folderList':
			$drm = $fm->folderList();
			if($drm) $drm = explode(',',$drm);
			echo ($drm?json_encode($drm):'false');
		break;
		case 'move':
			$move = array();
			$drm = 0;
			if($fm->post('copys')){
				$x=array_filter(explode(',',$fm->post('copys')));
				foreach($x as $s) array_push($move,$s);
				$drm = $fm->copy($move,$fm->post('path'));
				$drm = ($drm?1:0);
			}
			if($fm->post('moves')){
				$x=array_filter(explode(',',$fm->post('moves')));
				foreach($x as $s) array_push($move,$s);
				$ss = $fm->move($move,$fm->post('path'));
				$drm = ($drm?$drm*$ss:$ss);
			}
			echo json_encode(array('c'=>$drm?1:0));
		break;
		case 'download':
			$drm = $fm->download($fm->post('path'));
			echo $drm;
		break;
		case 'show':
			$drm = $fm->show($fm->post('path'));
			echo $drm;
		break;
		case 'delete':
			$post = explode('|',substr($fm->post('path'),0,-1));
			$drm = $fm->delete($post);
			echo (is_array($drm)?json_encode($drm):$drm);
		break;
		case 'rename':
			if($fm->post('name') && $fm->post('oldName')){
				$drm=$fm->rename($fm->post('path').$fm->post('oldName'),$fm->post('name'));
				echo ($drm?$drm:0);
			}
			else echo 0;
		break;
		case 'upload':
			$re = array('m'=>'fileUploadNotFound','files'=>0);
			if(!empty($_FILES['file'])){
				$drm = $fm->upload($_FILES['file'],$fm->post('path'));
				if(is_array($drm))$re=array('m'=>'successfulUpload','files'=>array_merge($drm['done'],$drm['error']));
				else $re['m'] = $drm;
			}
			echo json_encode($re);
		break;
		case 'createzip':
			$re = array('m'=>'notFoundZipFiles','files'=>0);
			$drm = $fm->createZip(explode('|',substr($fm->post('files'),0,-1)),$fm->post('path'));
			if(is_array($drm))$re=array('m'=>'successfulCreateZip','files'=>array_merge($drm['done'],$drm['error']));
			else $re['m'] = $drm;
			echo json_encode($re);
		break;
		case 'unzip':
			echo ($fm->unzip($fm->post('file'))?1:0);
		break;
		default; exit(http_response_code(403));
	}
	/*if( $drm == 0 ){
		http_response_code(412);
		die();
	}*/
