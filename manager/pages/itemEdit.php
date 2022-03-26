<?php
 /*!
 * FileManager (PHP/JS)
 * version: 2.4.4
 * Requires PHP v5.0 or later
 * Copyright (c) 2018 Tanzer DEMİR
 * Examples and documentation at: https://apps.tanzerdemir.net/#file_manager_v2/
 * https://tanzerdemir.net
*/
// namespace File_Manager;
 
 $charset = array(
  'ansi_x3.4-1968',		'armscii-8',		'big5',			'cp1254',
  'euc-jp',				'euc-kr',			'euc-tw',		'gb18030',
  'gb2312',				'gbk',				'hz-gb-2312',	'iso-8859-1',
  'iso-8859-10',		'iso-8859-13',		'iso-8859-14',	'iso-8859-15',
  'iso-8859-16',		'iso-8859-2',		'iso-8859-3',	'iso-8859-4',
  'iso-8859-5',			'iso-8859-6',		'iso-8859-7',	'iso-8859-8',
  'iso-8859-9',			'iso_8859-1,gl',	'koi8-r',		'koi8-u',
  'shift_jis',			'us-ascii',			'utf-7',		'utf-8',
  'windows-1251',		'windows-1252',		'windows-31j'
  );
 function file_get_contents_utf8($fn,$ch) {
  $content = file_get_contents($fn);
  return mb_convert_encoding($content, 'UTF-8', $ch);
 }
 function ext(){
	 return array("abap"=>"abap",
		"abc"=>"abc",
		"actionscript"=>"as",
		"ada"=>"ada|adb",
		"alda"=>"alda",
		"apache_conf"=>"^htaccess|^htgroups|^htpasswd|^conf|htaccess|htgroups|htpasswd",
		"apex"=>"apex|cls|trigger|tgr",
		"aql"=>"aql",
		"asciidoc"=>"asciidoc|adoc",
		"asl"=>"dsl|asl|asl.json",
		"assembly_x86"=>"asm|a",
		"autohotkey"=>"ahk",
		"batchfile"=>"bat|cmd",
		"c_cpp"=>"cpp|c|cc|cxx|h|hh|hpp|ino",
		"c9search"=>"c9search_results",
		"cirru"=>"cirru|cr",
		"clojure"=>"clj|cljs",
		"cobol"=>"CBL|COB",
		"coffee"=>"coffee|cf|cson|^Cakefile",
		"coldfusion"=>"cfm",
		"crystal"=>"cr",
		"csharp"=>"cs",
		"csound_document"=>"csd",
		"csound_orchestra"=>"orc",
		"csound_score"=>"sco",
		"css"=>"css",
		"curly"=>"curly",
		"d"=>"d|di",
		"dart"=>"dart",
		"diff"=>"diff|patch",
		"dockerfile"=>"^Dockerfile",
		"dot"=>"dot",
		"drools"=>"drl",
		"edifact"=>"edi",
		"eiffel"=>"e|ge",
		"ejs"=>"ejs",
		"elixir"=>"ex|exs",
		"elm"=>"elm",
		"erlang"=>"erl|hrl",
		"forth"=>"frt|fs|ldr|fth|4th",
		"fortran"=>"f|f90",
		"fsharp"=>"fsi|fs|ml|mli|fsx|fsscript",
		"fsl"=>"fsl",
		"ftl"=>"ftl",
		"gcode"=>"gcode",
		"gherkin"=>"feature",
		"gitignore"=>"^.gitignore",
		"glsl"=>"glsl|frag|vert",
		"gobstones"=>"gbs",
		"golang"=>"go",
		"graphqlschema"=>"gql",
		"groovy"=>"groovy",
		"haml"=>"haml",
		"handlebars"=>"hbs|handlebars|tpl|mustache",
		"haskell"=>"hs",
		"haskell_cabal"=>"cabal",
		"haxe"=>"hx",
		"hjson"=>"hjson",
		"html"=>"html|htm|xhtml|vue|we|wpy",
		"html_elixir"=>"eex|html.eex",
		"html_ruby"=>"erb|rhtml|html.erb",
		"ini"=>"ini|conf|cfg|prefs",
		"io"=>"io",
		"jack"=>"jack",
		"jade"=>"jade|pug",
		"java"=>"java",
		"javascript"=>"js|jsm|jsx",
		"json"=>"json",
		"json5"=>"json5",
		"jsoniq"=>"jq",
		"jsp"=>"jsp",
		"jssm"=>"jssm|jssm_state",
		"jsx"=>"jsx",
		"julia"=>"jl",
		"kotlin"=>"kt|kts",
		"latex"=>"tex|latex|ltx|bib",
		"latte"=>"latte",
		"less"=>"less",
		"liquid"=>"liquid",
		"lisp"=>"lisp",
		"livescript"=>"ls",
		"logiql"=>"logic|lql",
		"lsl"=>"lsl",
		"lua"=>"lua",
		"luapage"=>"lp",
		"lucene"=>"lucene",
		"makefile"=>"^Makefile|^GNUmakefile|^makefile|^OCamlMakefile|make",
		"markdown"=>"md|markdown",
		"mask"=>"mask",
		"matlab"=>"matlab",
		"maze"=>"mz",
		"mediawiki"=>"wiki|mediawiki",
		"mel"=>"mel",
		"mips"=>"s|asm",
		"mixal"=>"mixal",
		"mushcode"=>"mc|mush",
		"mysql"=>"mysql",
		"nginx"=>"nginx|conf",
		"nim"=>"nim",
		"nix"=>"nix",
		"nsis"=>"nsi|nsh",
		"nunjucks"=>"nunjucks|nunjs|nj|njk",
		"objectivec"=>"m|mm",
		"ocaml"=>"ml|mli",
		"pascal"=>"pas|p",
		"perl"=>"pl|pm",
		"pgsql"=>"pgsql",
		"php"=>"php|inc|phtml|shtml|php3|php4|php5|phps|phpt|aw|ctp|module",
		"php_laravel_blade"=>"blade.php",
		"pig"=>"pig",
		"powershell"=>"ps1",
		"praat"=>"praat|praatscript|psc|proc",
		"prisma"=>"prisma",
		"prolog"=>"plg|prolog",
		"properties"=>"properties",
		"protobuf"=>"proto",
		"puppet"=>"epp|pp",
		"python"=>"py",
		"qml"=>"qml",
		"r"=>"r",
		"raku"=>"raku|rakumod|rakutest|p6|pl6|pm6",
		"razor"=>"cshtml|asp",
		"rdoc"=>"Rd",
		"red"=>"red|reds",
		"rhtml"=>"Rhtml",
		"rst"=>"rst",
		"ruby"=>"rb|ru|gemspec|rake|^Guardfile|^Rakefile|^Gemfile",
		"rust"=>"rs",
		"sass"=>"sass",
		"scad"=>"scad",
		"scala"=>"scala|sbt",
		"scheme"=>"scm|sm|rkt|oak|scheme",
		"scrypt"=>"scrypt",
		"scss"=>"scss",
		"sh"=>"sh|bash|^.bashrc",
		"sjs"=>"sjs",
		"slim"=>"slim|skim",
		"smarty"=>"smarty|tpl",
		"smithy"=>"smithy",
		"snippets"=>"snippets",
		"soy_template"=>"soy",
		"space"=>"space",
		"sql"=>"sql",
		"sqlserver"=>"sqlserver",
		"stylus"=>"styl|stylus",
		"svg"=>"svg",
		"swift"=>"swift",
		"tcl"=>"tcl",
		"terraform"=>"tf,tfvars,terragrunt",
		"tex"=>"tex",
		"text"=>"txt",
		"textile"=>"textile",
		"toml"=>"toml",
		"tsx"=>"tsx",
		"twig"=>"twig|swig",
		"typescript"=>"ts|typescript|str",
		"vala"=>"vala",
		"vbscript"=>"vbs|vb",
		"velocity"=>"vm",
		"verilog"=>"v|vh|sv|svh",
		"vhdl"=>"vhd|vhdl",
		"visualforce"=>"vfp|component|page",
		"wollok"=>"wlk|wpgm|wtest",
		"xml"=>"xml|rdf|rss|wsdl|xslt|atom|mathml|mml|xul|xbl|xaml",
		"xquery"=>"xq",
		"yaml"=>"yaml|yml",
		"zeek"=>"zeek|bro",
		"django"=>"html"
	);
 }
 function content($file,$ch,$lang,$dir){
	$file = $dir.'/'.$file;
	if(!file_exists($file)) return array(false,'fileNotFound',$lang['message']['error']['notFoundFile'].': '.$file);
	if(!is_file($file)) return array(false,'folderNotSupport',$lang['message']['error']['fileNotSupport'].': '.$file);
	$type = mime_content_type($file);
	$image = strpos($type,'mage');
	$swf = strpos($type,'x-shockwave-flash');
	$flv = strpos($type,'x-flv');
	$zip = strpos($type,'zip');
	$rar = strpos($type,'x-rar');
	$exe_msi = strpos($type,'x-msdownload');
	$audio = strpos($type,'udio');
	$video = strpos($type,'ideo');
	$pdf = strpos($type,'pdf');
	$ai_eps_ps = strpos($type,'postscript');
	$word = strpos($type,'msword');
	$rtf = strpos($type,'rtf');
	$vnd = strpos($type,'vnd');
	$other = strpos($type,'octet-stream');
	if($image && in_array(exif_imagetype($file),array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) return array('image',"data:$type;base64,".base64_encode(file_get_contents($file)),$file,getimagesize($file));
	if($image || $swf || $flv || $zip || $rar || $exe_msi || $audio || $video || $pdf || $ai_eps_ps || $word || $rtf || $vnd || $other) return array(false,'fileNotSupport',$lang['message']['error']['fileNotSupport'].': '.$file);
	$size = filesize($file);
	if($size>=1048576) return array(false,'fileSizeError',$lang['message']['error']['editFileSizeError'].': '.$file." ($size)");
	$cont = file_get_contents_utf8($file,$ch);
	$drm = pathinfo($file);
	$re = array('text',$cont,$drm['basename'],'');
	foreach(ext() as $a=>$b){
		foreach(explode('|',$b) as $c){
			if($c==$drm['extension']){
				$re[3] = $a;
				break;
			}
		}
		if($re[3]!='') break;
	}
	if($re[3]=='') $re[3] = 'text';
	return $re;
 }
 if(!empty($_GET['file']) && !empty($_GET['assets']) && file_exists(__DIR__ .'/../xml/lang/'.@$_GET['lang'].'.xml')){
  $file = $_GET['file'];
  $assets = $_GET['assets'];
  $lang = $_GET['lang'];
  $xml = simplexml_load_string(file_get_contents(__DIR__ ."/../xml/lang/$lang.xml"), "SimpleXMLElement", LIBXML_NOCDATA);
  $main = simplexml_load_string(file_get_contents(__DIR__ ."/../xml/config/config.xml"), "SimpleXMLElement", LIBXML_NOCDATA);
  $main = json_decode(json_encode($main),TRUE);
  $backDir = '/'; $s=count(explode('/',$assets)) - 1;
  for($a=0;$a<$s;$a++) $backDir.='../';
  $main = realpath(__DIR__ .$backDir.$main['mainFolder']);
  $lang=strtolower(substr($lang,0,2));
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);
  $ch = 'utf-8';
  if(!empty($_GET['charset'])) if(in_array($_GET['charset'],$charset)) $ch = $_GET['charset'];
  $cont = content($file,$ch,$array,$main);
?>
<html>
 <head>
  <title><?=$array['button']['edit'].': '.$cont[2]?></title>
  <link rel="stylesheet" href="<?=$assets?>/css/itemEdit.css" />
 </head>
 <body>
  <form action="javascript:void(0)" onsubmit="loadfdata('sform_submit'); return false;" class="form-inline">
   <table class="main">
    <thead>
	 <tr>
	  <td class="title"><?=$array['title']['managerName'].' - '.$array['button']['edit']?></td>
	 </tr>
     <tr>
      <td <?=$cont[0]=='image'?'style="position:relative;z-index:10;"':''?>>
       <button type="button" onclick="reopen()" class="form"><?=$array['button']['reopen']?></button>
       <button type="button" onclick="save('<?=$cont[0]?>')" class="form"><?=$array['button']['save']?></button>
	   <?php if($cont[0]=='text'){ ?>
        <select name="charset" class="form">
         <?php foreach($charset as $c) echo '<option value="'.$c.'"'.(($c==$ch)?' selected> ':"").'> '.$c.'</option>'; ?>
        </select>
		<select id="-mode" class="form">
		 <?php foreach(ext() as $a=>$b) { ?><option value="ace/mode/<?=$a?>"><?=ucfirst($a)?></option><?php } ?>
		</select>
	   <?php } ?>
        <input type="text" value="<?=$file?>" class="form" disabled/>
		<?php if($cont[0]=='image'){ ?>
		<input type="text" value="<?=$cont[3][0].'px * '.$cont[3][1].'px'?>" class="form" disabled/>
        <button type="button" onclick="imageCrop()" class="form crop"><?=$array['button']['imageCrop']?></button>
		<?php } ?>
      </td>
     </tr>
    </thead>
    <tbody>
     <tr>
      <td class="area-<?=$cont[0]?>">
	   <?php 
		if($cont[0]=='text') echo '<textarea wrap="soft" name="page" style="visibility:hidden;display:none;">'.$cont[1].'</textarea><pre id="editor"></pre>';
		elseif($cont[0]=='image'){ 
	   ?>
	   <div class="image" style="background:url('<?=$cont[1]?>');background-size:100%;width:90%" data-size="<?=$cont[3][0].'x'.$cont[3][1]?>">
		<table class="area" data-size="0 x 0">
			<tr><td class="sure" data-text="<?=$array['other']['cropMe']?>"></td><td></td><td></td></tr>
			<tr><td></td><td></td><td></td></tr>
			<tr><td></td><td></td><td></td></tr>
		</table>
		</div>
	   <?php 
		}
		else echo $cont[2];
		?>
      </td>
     </tr>
    </tbody>
   </table>
  </form>
  <script language="javascript" type="text/javascript" src="<?=$assets?>/js/jquery-3.2.1.min.js"></script>
  <?=$cont[0]=='text'?'<script language="javascript" type="text/javascript" src="'.$assets.'/js/plugins/ace_area/src/ace.js"></script><script language="javascript" type="text/javascript" src="'.$assets.'/js/plugins/ace_area/src/ext-language_tools.js"></script>':'<script language="javascript" type="text/javascript" src="'.$assets.'/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script><link rel="stylesheet" href="'.$assets.'/js/jquery-ui-1.12.1.custom/jquery-ui.min.css" />'?>
  <script language="javascript" type="text/javascript">
  <?php if($cont[0]=='text'){ ?>
   ace.require("ace/ext/language_tools");
   var editor = ace.edit("editor");
   $(document).ready(function(){
	editor.session.setMode('ace/mode/<?=$cont[3]?>');
	$('select#-mode option[value="ace/mode/<?=$cont[3]?>"]').attr('selected','selected');
    editor.setTheme("ace/theme/"+(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches?"merbivore_soft":"chrome"));
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: false
    });
	var textarea = $('textarea[name="page"]').hide();
	editor.getSession().setValue(textarea.val());
    $(window).resize(function(){
     $('#frame_textarea').width($(window).width()-6).height($(window).height()-$('thead').height()-6);
    });
    $('select#-mode').change(function(){editor.session.setMode($(this).val());});
    $('select[name=charset]').change(function(){
     reopen();
    });
    $(window).bind("beforeunload", function() {
	 if($('textarea[name="page"]').val()!==editor.getSession().getValue()){
      if(confirm("<?=$array['message']['sure']['fileEdit']?>")) return true;
      return false;
	 }
    });
   });
  <?php } if($cont[0]=='image'){ ?>
   var xx,xy,cc,s,o,grid,g,img=false,imga=false;
   $(document).ready(function(){
		s=$('div.image').attr('data-size').split('x');
		xx = $('div.image').width();
		xy = Math.floor((s[1]*xx)/s[0]);
		$('div.image').css({'height':xy+'px'});
   });
   function imageCrop(){
	   if(img){
		   imga.draggable('destroy').resizable('destroy');
		   img.resizable('destroy');
		   $('div.image').removeClass('crop');
		   $('button.crop').removeClass('active');
		   img = false;
		   imga = false;
		   $(window).off('keydown');
	   }
	   else{
	    $('div.image').addClass('crop');
		$('button.crop').addClass('active');
		reset();
		img = $('.image').resizable({hand:'e',resize:reset});
		$('.area > .ui-resizable-handle').dblclick(function(){$('div.image .area').css({width:'100%',height:'100%',top:0,left:0});});
		$('.image > .ui-resizable-handle').dblclick(function(){$('div.image').css({width:'100%'});resize();});
		window.addEventListener('resize', reset);
		$(window)/*.on('resize',reset)*/.
			keydown(function(e){
			var x = $('.image .area'), p = false, y;
			switch(e.keyCode){
				case 27:
					imageCrop();
				break;
				case 38:
					y = x.position().top-grid;
					if(y>0) x.css('top',y),p=true;
				break;
				case 39:
					y = x.position().left+grid;
					if(y+x.width()+1<xx) x.css('left',y),p=true;
				break;
				case 40: 
					y = x.position().top+grid;
					if(y+x.height()+1<xy) x.css('top',y),p=true; 
				break;
				case 37:
					y = x.position().left-grid;
					if(y>0) x.css('left',y),p=true;
				break;
			}
			if(p) window.event.returnValue = false, resizeCC();
		});
	   }
   }
   function resize(){
	   xx = $('div.image').width();
	   xy = Math.floor((s[1]*xx)/s[0]);
	   $('div.image').css({'height':xy+'px'});
	   o = s[0]*100/xx;
	   grid = xx/s[0];
	   resizeCC();
	   g = (grid<1?[1,1]:[Math.floor(grid),Math.floor(grid)]);
	   imga = $('div.image .area').draggable({
		   containment:'div.image',
		   drag:resizeCC,
		   grid:g
	   }).resizable({
		   containment:'div.image',
		   resize:resizeCC,
		   minHeight:grid,
		   minWidth:grid,
		   grid:g
	   })
   }
   function reset(){
		resize();
		$('div.image .area').css({
			width:Math.floor(xx/2),
			height:Math.floor(xy/2),
			top:Math.floor(xy/4),
			left:Math.floor(xx/4)
		});
		resizeCC();
   }
   function resizeCC(){
	   cc=$('div.image .area');
	   cc=[
		cc.position().left/100*o,
		cc.position().top/100*o,
		cc.width()/100*o,
		cc.height()/100*o
	   ];
	   $('div.image .area').attr({'data-size':Math.floor(cc[0])+'px * '+Math.floor(cc[1])+'px / '+Math.floor(cc[2])+'px * '+Math.floor(cc[3])+'px'});
   };
  <?php } ?>
   function reopen(){
    var a = location.search.substr(1);
    if(a.indexOf('charset')>0){
     var b = a.split('&');
     a = '';
     for(i=0;i<b.length;i++){
      if(b[i]!=''){
       if(i!=0) a += '&';
       if(b[i].indexOf('charset')==0){
        a += 'charset=' + $('select[name=charset]').val();
       }else{
        a += b[i];
       }
      }
     }
    }else{
     a += '&charset=' + $('select[name=charset]').val();
    }
    window.open(location.origin+location.pathname+'?'+a, '_parent');
   }
   function save(x){
    if(!confirm("<?=$array['message']['sure']['areYouSure']?>")) return false;
	switch(x){
		case 'text':
			$('textarea[name="page"]').val(editor.getSession().getValue());
			$.post("<?=$assets?>/../index.php",{"path":"<?=$file?>","cont":$('textarea[name=page]').val(),"op":"fileSave"}).done(function(a){
				alert(a?"<?=$array['message']['done']['successfulFileSave']?>":"<?=$array['message']['error']['unsuccessful']?>");
			});
		break;
		case 'image':
			$.post("<?=$assets?>/../index.php",{
				"path":"<?=$file?>",
				"px":cc[0],
				"py":cc[1],
				"pw":cc[2],
				"ph":cc[3],
				"op":"imageCrop",
				"add":"<?=$array['other']['croped']?>"
			}).done(function(e){
				alert(e?"<?=$array['message']['done']['successfulFileSave']?>":"<?=$array['message']['error']['unsuccessful']?>");
			});
		break;
	}
	return false;
   }
  </script>
 </body>
</html>
 <?php } else {
	 http_response_code(412);
	 die(print_r($_REQUEST,1)); 
 }