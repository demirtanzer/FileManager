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
	function _mime_content_type($filename){
		$mime_types = array(
			'txt' => 'text/plain',
			// 'htaccess' => 'text/plain',
			'htm' => 'text/html',
			'html' => 'text/html',
			'php' => 'text/x-php',
			'css' => 'text/css',
			'js' => 'application/javascript',
			'json' => 'application/json',
			'xml' => 'application/xml',
			'swf' => 'application/x-shockwave-flash',
			'flv' => 'video/x-flv',

			// images
			'png' => 'image/png',
			'jpe' => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'gif' => 'image/gif',
			'bmp' => 'image/bmp',
			'ico' => 'image/vnd.microsoft.icon',
			'tiff' => 'image/tiff',
			'tif' => 'image/tiff',
			'svg' => 'image/svg+xml',
			'svgz' => 'image/svg+xml',

			// archives
			'zip' => 'application/zip',
			'rar' => 'application/x-rar-compressed',
			'exe' => 'application/x-msdownload',
			'msi' => 'application/x-msdownload',
			'cab' => 'application/vnd.ms-cab-compressed',

			// audio/video
			'mp3' => 'audio/mpeg',
			'qt' => 'video/quicktime',
			'mov' => 'video/quicktime',

			// adobe
			'pdf' => 'application/pdf',
			'psd' => 'image/vnd.adobe.photoshop',
			'ai' => 'application/postscript',
			'eps' => 'application/postscript',
			'ps' => 'application/postscript',

			// ms office
			'doc' => 'application/msword',
			'docx' => 'application/msword',
			'rtf' => 'application/rtf',
			'xls' => 'application/vnd.ms-excel',
			'xlsx' => 'application/vnd.ms-excel',
			'ppt' => 'application/vnd.ms-powerpoint',
			'pptx' => 'application/vnd.ms-powerpoint',

			// open office
			'odt' => 'application/vnd.oasis.opendocument.text',
			'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
		);
		$ext = pathinfo($filename,PATHINFO_EXTENSION);
		if(array_key_exists($ext, $mime_types)){
			return $mime_types[$ext];
		} else if(function_exists('mime_content_type')){
			return mime_content_type($filename);
		} else {
			return 'application/octet-stream';
		}
	}