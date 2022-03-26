<?php 
/*!
 * FileManager (PHP/JS)
 * version: 2.4.4
 * Requires PHP v5.0 or later
 * Copyright (c) 2018 Tanzer DEMİR
 * Examples and documentation at: https://apps.tanzerdemir.net/#file_manager_v2/
 * https://tanzerdemir.net
*/
$adr = '//'.$_SERVER['HTTP_HOST'].'';
?>
<!doctype html>
<html>
 <head>
  <title>File Manager</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
 </head>
 <body>
  <div id="files">
   <div id="FileManagerArea" data-language="TR-tr" data-managerfolder="/manager/"></div>
  </div>
 <script type="text/javascript" src="<?=$adr?>/manager/assets/js/jquery-3.2.1.min.js"></script>
 <script type="text/javascript" src="<?=$adr?>/manager/assets/js/FileManager.js?16"></script>
 <script type="text/javascript">
  FileManager.managerCreate('#FileManagerArea');
 </script>
</html>
 