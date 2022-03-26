/*!
 * FileManager (PHP/JS)
 * version: 2.4.4
 * Requires PHP v5.0 or later
 * Copyright (c) 2018 Tanzer DEMİR
 * Examples and documentation at: https://apps.tanzerdemir.net/#file_manager_v2/
 * https://tanzerdemir.net
*/
if("undefined"==typeof jQuery) throw new Error("File Manager's JavaScript requires jQuery");
else {
	var FileManager = {
		id:	'',
		dir: null,
		uploading: false,
		keyP:false,
		managerFolder: function(url){
			if(FileManager.dir === null){
				var find = null;
				if($(FileManager.id).attr('data-managerfolder')) FileManager.dir = $(FileManager.id).attr('data-managerfolder');
				else{
					var 
						src = $("script").not('[src=""]'),
						fileName = 'FileManager.js';
					for(var i=0;i<src.length;i++){
						var who = src.eq(i).attr('src');
						if(who && who.substring(who.length - fileName.length)==fileName){
							find = who;
							break;
						}
					}
					if(find !== null) FileManager.dir = find.split('/').slice(0, -3).join('/')+'/';
					if(FileManager.dir === null || FileManager.dir === 'undefined')	FileManager.error('\tex: 1)\t&lt;script src="/bla/bla/FileManager.js"\&gt;&lt;/script&gt;\n\tex: 2)\t&lt;<i>dom</i> id="FileManagerArea" <b><u>data-managerfolder="/bla/bla/FileManager/"</u></b>&gt;&lt;/<i>dom</i>\&gt;\n','Path of File Manager main folders not found!\n');
				}
			}
			return FileManager.dir+url;
		},
		error: function(message,error){
			if('undefined' !== typeof error) message = '<b style="color:#ffffff">' + error + '</b>' + message;
			$(FileManager.id).html('<pre align="left" style="padding:5px 7px;border-radius:4px;background-color:#00796b;color:#efefef;font-family:monospace;white-space:pre;">'+message+'</pre>');
			if('undefined' !== typeof error) throw error;
		},
		cacheCreate: function(xml,name){
			$.get(FileManager.managerFolder('xml/'+xml),null,function(xmldata){
				var cache = {
					data: new XMLSerializer().serializeToString(xmldata),
					expires: new Date().getTime()
				}
				sessionStorage.setItem('FileManager'+name,JSON.stringify(cache));
			});
		},
		cacheRead: function(id){
			// if(sessionStorage.getItem(id)===null)
			var cache = JSON.parse(sessionStorage.getItem(id));
			return new DOMParser().parseFromString(cache.data, "text/xml");
		},
		config:function(type){
			var r = 'unknow';
			var config = FileManager.cacheRead('FileManagerConfig');
			var search = $(config).find(type);
			if(search.text()!=='') r = search.text();
			return r
		},
		managerCreate: function(id){
			FileManager.error('Loading...');
			FileManager.id = id;
			var lang = (!$(id).attr('data-language')?'TR-tr':$(id).attr('data-language'));
			$.when(
				FileManager.cacheCreate('config/config.xml','Config'),
				FileManager.cacheCreate('lang/'+lang+'.xml','Language.'+lang),
				$.getScript(FileManager.managerFolder('assets/js/jquery-ui-1.12.1.custom/jquery-ui.min.js')),
				$.getScript(FileManager.managerFolder('assets/js/jquery-form-3.51.0.js')),
				$.Deferred(function(deferred){$(deferred.resolve)})
			).done(function(){
				var buttons = ['back,arrow-circle-left,Alt+←','refresh,refresh,Alt+F5',['fileAndFolder,cogs','newFile,file-o,Alt+N','newFolder,folder-o,Alt+M','','edit,edit,Ctrl+E','copy,clone,Ctrl+C','move,arrows,Ctrl+X','rename,header,F2','','delete,trash,Del'],['archive,briefcase','archiveRar,file-archive-o','archiveZip,file-archive-o','','unarchive,archive'],'download,download,Alt+D','upload,upload,Alt+U','detail,info','settings,cog','clipboard,clipboard,Ctrl+V'];
				var buttonGroup = '';
				var write = function(text,type){
					var xhr = text.split(',');
					if(xhr[type]===undefined) return '';
					return xhr[type];
				}
				for(var i = 0;i<buttons.length;i++){
					var btn = buttons[i];
					if(typeof btn[1][1] === 'undefined')
						buttonGroup += '<li><a id="'+write(btn,0)+'" href="javascript:void(0)"><i class="fa fa-'+write(btn,1)+' fa-fw"></i>'+FileManager.text('button',write(btn,0))+'<small>'+write(btn,2)+'</small></a></li>';
					else{
						buttonGroup += '<li><a id="'+write(btn[0],0)+'" href="javascript:void(0)"><i class="fa fa-'+write(btn[0],1)+' fa-fw"></i>'+FileManager.text('button',write(btn[0],0))+' <i class="fa fa-caret-down fa-fw"></i></a><ul name="'+write(btn[0],0)+'">';
						for(var ii = 1;ii<btn.length;ii++){
							if(btn[ii]=='') buttonGroup += '<li class="line"></li>';
							else buttonGroup += '<li><a id="'+write(btn[ii],0)+'" href="javascript:void(0)"><i class="fa fa-'+write(btn[ii],1)+' fa-fw"></i>'+FileManager.text('button',write(btn[ii],0))+'<small>'+write(btn[ii],2)+'</small></a></li>';
						}
						buttonGroup += '</ul></li>';
					}
				}
				var 
					css = [['css/','FileManager.css'],['css/font-awesome-4.7.0/css/','font-awesome.min.css'],['js/jquery-ui-1.12.1.custom/','jquery-ui.min.css']],
					link = $('link').not('[href=""]'),
					who,html;
				for(var i=0;i<css.length;i++){
					if(!$('link[href*="'+css[i][1]+'"]').length) 
						$('head').append('<link rel="stylesheet" href="'+FileManager.managerFolder('assets/'+css[i][0]+css[i][1])+'" type="text/css" />');
				}
				$(id).html(
					html = '<div class="header"><img src="'+FileManager.managerFolder('assets/img/logo.svg')+'">'+FileManager.text('title','managerName')+'<span class="fa fa-arrows-alt fa-fw" title="'+FileManager.text('button','fullscreen')+'" id="FMfullscreen"></span><span class="fa fa-refresh fa-fw" style="visibility:visible" title="'+FileManager.text('button','refresh')+'" id="FMrefresh"></span></div>'+
					'<div class="buttonGroup"><ul>'+buttonGroup+'</ul><input type="hidden" name="copys" value="" /><input type="hidden" name="moves" value="" /></div>'+
					'<div class="folderList" style="float:left;"><ul><li data-url="/"><i class="fa fa-home fa-fw" onclick="FileManager.folderList(\'/\')"></i><a href="javascript:void(0)" onclick="FileManager.showList(\'/\')">'+FileManager.text('title','mainFolder')+'</a><ul></ul></li></ul></div>'+
					'<div class="content"><ul>&nbsp;</ul></div>'+
					'<div class="footer"></div>'
				).addClass('FileManagerArea');
				$(id+' > .header > span#FMrefresh').click(function(){
					for(var i=0;i<css.length;i++)	$('link[href*="'+css[i][1]+'"]').remove();
					sessionStorage.clear();
					FileManager.managerCreate(FileManager.id);
				});
				$(id+' > .header > span#FMfullscreen').click(function(){
					if($(id).hasClass('fullscreen')){
						$(id).removeClass('fullscreen');
						$('body').removeClass('FMfullscreen');
						$(id+' > .content').css('height','auto');
						$(id+' > .folderList').css('height','auto');
					}else{
						var t = $(window).height()-($(id+' > .header').height()+$(id+' > .buttonGroup').height()+$(id+' > .footer').height())-2;
						$(id).addClass('fullscreen');
						$('body').addClass('FMfullscreen');
						$(id+' > .folderList').height(t);
						$(id+' > .content').height(t-14);
					}
				});
				$(id+' > .folderList').resizable({
					containment: FileManager.id,
					maxWidth: $(FileManager.id).width()/2,
					minWidth: 110,
					handles: 'e'
				});
				if ($(id+' .content').addEventListener) {
					$(id+' .content').addEventListener('contextmenu', function(e) {
						FileManager.rightClick(e);
						e.preventDefault();
					}, false);
				} else {
					$(id+' .content').on('contextmenu', function(e) {
						FileManager.rightClick(e);
						window.event.returnValue = false;
					});
				}
				$(window).resize(function(){FileManager.resize()}).keydown(function(e){
					if(e.keyCode == 17 || e.keyCode == 91) FileManager.keyP = 'ctrl';
					if(e.keyCode == 18) FileManager.keyP = 'alt';
					if(!$(FileManager.id+'Dialog').length)FileManager.keyClick(e.keyCode);
					else if(e.keyCode == 13) $(FileManager.id+'Dialog .ui-dialog-buttonset button').eq(0).click();
				}).keyup(function(e){
					if(e.keyCode == 17 || e.keyCode == 18 || e.keyCode == 91) FileManager.keyP = false;
				});
				FileManager.showList('/',FileManager.btnClck());
			});
		},
		dialogCreate: function(json,width=350,height=270,c=false){
			if($(FileManager.id+'Dialog').length) $(FileManager.id+'Dialog').dialog('destroy');
			var title = json.title, content = json.content, close = [{text:FileManager.text('button',(json.btn?'cancel':'close')),click:function(){dialog.dialog("close")},'class':'red'}];
			if(json.btn=='menu'){
				json.btn=false; close=false;
			}
			var dialog = $('<div id="'+FileManager.id.substring(1)+'Dialog" class="'+FileManager.id.substring(1)+'Dialog" title="'+title+'">'+content+'</div>').dialog({
				autoOpen: true,
				width: width,
				height: height,
				modal: true,
				close: function(){if($(FileManager.id+'Dialog').length<2)$(FileManager.id+'Dialog').dialog('destroy');},
				buttons: (json.btn?json.btn.concat(close):close),
				closeText: FileManager.text('button','close'),
				open:function(){if(c)$('.ui-widget-overlay').addClass('custom');}
			});
			if(c) dialog.dialog({position:{my:'left+3 top+3',of:c}});
			dialog.dialog('open');
			$('.ui-widget-overlay').click(function(){
				$(FileManager.id+'Dialog').dialog('destroy');
				$(FileManager.id+'DialogUpload').dialog('close');
			});
		},
		btnCtrl: function(ctrl){
			var disabled = [];
			$(FileManager.id+' > .buttonGroup li').removeClass('disabled');
			switch(ctrl){
				case 'allDisabled':
					$(FileManager.id+' > .buttonGroup li').addClass('disabled');
				break;
				case 'first':
					disabled.push('edit','copy','move','rename','delete','archive','download','detail');
				break;
				case 'file':
					disabled.push('newFile','newFolder','upload');
					var type = $(FileManager.id+' li.ui-selected').attr('data-type');
					if(/*type=='application/x-rar-compressed' || */type!='application/zip') disabled.push('unarchive');
					if(type=='directory' || (type.indexOf('text')===-1 && type.indexOf('xml')===-1 && type.indexOf('javascript')===-1 && type.indexOf('x-empty')===-1 && type.indexOf('mage/')===-1)) disabled.push('edit');
					if(type=='directory') disabled.push('download');
				break;
				case 'multi':
					disabled.push('newFile','newFolder','edit','rename','upload','unarchive','detail','download');
				break;
			}
			if($(FileManager.id+' input[name=copys]').val()=='' && $(FileManager.id+' input[name=moves]').val()=='') disabled.push('clipboard');
			if(disabled.length>0){
				disabled.push('archiveRar');
				if($(FileManager.id).attr('data-showurl')=='/' || $(FileManager.id).attr('data-showurl')==FileManager.config('mainFolder')+'/') disabled.push('back');
				// alert(FileManager.config('mainFolder'));
				for(var i = 0;i<disabled.length;i++)	$(FileManager.id+' > .buttonGroup #'+disabled[i]).parent().addClass('disabled');
			}
		},
		btnClck: function(){
			$(FileManager.id+' > .buttonGroup li').click(function(){
				if(!$(this).hasClass('disabled')){
					var items = $(FileManager.id+' > .content > ul > li.ui-selected'),
						item = $(FileManager.id+' > .content > ul > li.selected .fa').attr('data-url'),
						folder = $(FileManager.id).attr('data-showurl');
					switch($(this).children('a').attr('id')){
						case 'back' : FileManager.showList(folder+'/../');	break;
						case 'refresh' : FileManager.showList(folder);	break;
						case 'newFile' : FileManager.itemEdit('newFile');	break;
						case 'newFolder' : FileManager.itemEdit('newFolder');	break;
						case 'edit' : FileManager.itemEdit('edit',item);	break;
						case 'copy' : FileManager.clipboard('add','copy',items);	break;
						case 'move' : FileManager.clipboard('add','move',items);	break;
						case 'rename' : FileManager.itemEdit('rename',item);	break;
						case 'delete' : FileManager.itemEdit('delete',items);	break;
						case 'archiveZip' : FileManager.itemArchive('addArchive',items,'zip');	break;
						case 'unarchive' : FileManager.itemArchive('unarchive',item);	break;
						case 'download' : FileManager.itemDownload(folder);	break;
						case 'upload' : FileManager.itemUpload(folder);	break;
						case 'detail' : FileManager.itemDetail(folder+item);	break;
						case 'settings' : FileManager.languageList();	break;
						case 'clipboard' : FileManager.clipboard();	break;
					}
				}
			});
		},
		keyClick: function(id){
			$p = false;
			switch(FileManager.keyP){
				case false:
					switch(id){
						case 13: $(FileManager.id+' #detail').click();$p=true; break;
						case 46: $(FileManager.id+' #delete').click();$p=true; break;
						case 113: $(FileManager.id+' #rename').click();$p=true; break;
					}
				break;
				case 'ctrl':
					switch(id){
						case 65: $(FileManager.id+' > .content > ul > li').addClass('selected ui-selected');$p=true; break;						
						case 67: $(FileManager.id+' #copy').click();$p=true; break;						
						case 69: $(FileManager.id+' #edit').click();$p=true; break;						
						case 86: $(FileManager.id+' #clipboard').click();$p=true; break;						
						case 88: $(FileManager.id+' #move').click();$p=true; break;						
					}
				break;
				case 'alt':
					switch(id){
						case 37: $(FileManager.id+' #back').click();$p=true; break;						
						case 68: $(FileManager.id+' #download').click();$p=true; break;						
						case 77: $(FileManager.id+' #newFolder').click();$p=true; break;						
						case 78: $(FileManager.id+' #newFile').click();$p=true; break;						
						case 85: $(FileManager.id+' #upload').click();$p=true; break;						
						case 116: $(FileManager.id+' #refresh').click();$p=true; break;
					}
				break;
			}
			// console.log(id);//64837251
			if($p) window.event.returnValue = false;
		},
		resize: function(){
			var id = FileManager.id;
			var	folderList = $(id+' > .folderList'), fmOut = $(id).outerWidth(), flOut = folderList.outerWidth()+7;
			if(fmOut<400 && !folderList.hasClass('ui-resizable-disabled')){
				folderList.css({'width':'100%','height':'auto','border-bottom':'1px dotted #cccccc'}).resizable('disable');
				$(id+' > .content').css({'width':'100%','height':'auto'}).find('ul').css('overflow-y','unset');
			}
			else{
				if(folderList.hasClass('ui-resizable-disabled')){
					folderList.css({'border':'none','width':'auto'}).resizable('enable');
					$(id+' > .content > ul').css('overflow-y','auto');
				}
				$(id+' > .content').css({'overflow-y':'auto','width':'calc(100% - '+flOut+'px)'}).find('ul').css('margin',0);
			}
			if($(id).hasClass('fullscreen')){
				var t = $(window).height()-($(id+' > .header').height()+$(id+' > .buttonGroup').height()+$(id+' > .footer').height())-2;$(id+' > .folderList').height(t);
				$(id+' > .content').height(t-14);
			}
		},
		loading: function(x){
			if(x==0)	$(FileManager.id+' .ui-widget-overlay').remove();
			else	$(FileManager.id).prepend('<div class="ui-widget-overlay ui-front" style="z-index: 100;text-align:center;color:#000000"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="margin-top:'+($(window).height()/2)+'px"></i></div>');
		},
		color: function(name,type){
			var 
				types = name.split('/'), 
				search, 
				r = '#000000';
				config = FileManager.cacheRead('FileManagerConfig');
			search = $(config).find('colorScheme '+types[0]);
			if(search.find(type).text() !== ''){
				r = search.find(type).text();
				if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
					r = FileManager.changeColor(r,-50);
				}
			}
			return r;
		},
		changeColor: function(color,amount){
			return '#' + color.replace(/^#/, '').replace(/../g, color => ('0'+Math.min(255, Math.max(0, parseInt(color, 16) + amount)).toString(16)).substr(-2));
		},
		text: function(addr,name,xhr){
			var 
				lang = FileManager.cacheRead('FileManagerLanguage.'+$(FileManager.id).attr('data-language')), 
				search = $(lang).find(addr+' '+name.replace('/','_').replace('+','-'));
			if(search.text() !== '') return search.text();
			if('undefined' !== typeof xhr){
				name = name.split('/');
				return name[1].toUpperCase()+' '+FileManager.text(addr,'fileIs');
			}
			return name;
		},
		icon: function(type){
			var icon = [];
			icon['folder'] = 'folder';
			icon['folder-o'] = 'folder-open';
			icon['directory'] = 'folder-open';
			icon['video'] = 'file-video-o';
			icon['image'] = 'file-image-o';
			icon['text/plain'] = 'file-text-o';
			icon['text/html'] = icon['text/css'] = icon['text/x-php'] = icon['application/xml'] = icon['application/javascript'] = 'file-code-o';
			icon['application/x-rar-compressed'] = icon['application/zip'] = 'file-archive-o';
			icon['audio/mpeg']  = 'file-audio-o';
			icon['application/pdf']  = 'file-pdf-o';
			icon['application/msword']  = 'file-word-o';
			icon['application/vnd.ms-excel']  = 'file-excel-o';
			icon['application/vnd.ms-powerpoint']  = 'file-powerpoint-o';
			if(typeof icon[type] !== 'undefined') return icon[type];
			var x = type.split('/');
			if(typeof icon[x[0]] !== 'undefined') return icon[x[0]];
			return 'file-o';
		},
		time: function(time){
			time = time.split(' ');
			var a = time[0].split('-');
			return FileManager.text('time > months','m'+a[1])+' '+a[2]+', '+a[0]+' - '+time[1];
		},
		languageList: function(){
			FileManager.loading(1);
			$.post(
				FileManager.managerFolder('index.php'),
				{
					op:'language',
					path:'/'
				},
				function(json){
					var infoText = FileManager.text('other','info').split('|n');
					var info1 = infoText[0].split('|s'); 
					var html = '<table border="0" cellspacing="0" style="color:#444;font-size:small;"><tbody><tr><td colspan="2"><p style="text-indent:1em;"><img src="'+FileManager.managerFolder('assets/img/logoimp.svg')+'" width="155" alt="logo" style="float:left;margin-right:7px"/>'+info1[0]+'<a href="https://tanzerdemir.net" target="_blank">Tanzer DEMİR</a>'+info1[1]+'</p><p style="text-indent:10px;">'+infoText[1]+'<a href="https://apps.tanzerdemir.net/#file_manager_v2" target="_blank"><i class="fa fa-fw fa-info-circle"></i></a></p></td></tr><tr><td colspan="2" style="border-top:1px solid #800;padding-top:10px;">'+infoText[2]+'</td></tr><tr>', lang = $(FileManager.id).attr('data-language');
					for(var i=0;i<json.length;i++) html+='<td><label><input type="radio" style="display:inline-block;width:1em" name="language" value="'+json[i][0]+'" '+(lang==json[i][0]?'checked':'')+'>'+json[i][1][0]+'</label></td>';
					html+='</tr></tbody></table>';
					FileManager.dialogCreate({
						title:FileManager.text('title','managerLanguageChange'),
						content:html,
						btn:[{
							text:FileManager.text('button','save'),
							click: function(){
								FileManager.languageChange($(this).find('input[name="language"]:checked').val());
							}
						}]
					},'400','auto');
				},
				'json'
			)
			.always(function(){
				FileManager.loading(0);
			})
			.fail(FileManager.postError);
		},
		languageChange: function(lang){
			if(lang){
				$(FileManager.id).attr('data-language',lang);
				sessionStorage.clear();
				FileManager.managerCreate(FileManager.id);
				$('.ui-dialog').hide(500,function(){$(this).remove()});
			}
			else FileManager.dialogCreate({
				title:FileManager.text('title','managerLanguageChange'),
				content:FileManager.text('error','languageNotSelect')
			});
		},
		editList: function(json,who){
			var 
				r = '',
				url = json.url,
				folders = '<i style="display:none;"></i>',
				icon = [],
				name, type, tmp,
				i;
			for(i=0;i<json.folders.length;i++){
				name = json.folders[i];
				folders += '<li data-url="'+url+name+'"><i class="fa fa-'+FileManager.icon('folder')+' fa-fw" onclick="FileManager.folderList(\''+url+name+'\')"></i><a href="javascript:void(0)" onclick="FileManager.showList(\''+url+name+'\')">'+name+'</a><ul></ul></li>';
				if('undefined' === typeof who) r += '<li data-type="directory"><table><tr><td title="'+FileManager.text('fileType','directory',true)+'"><i class="fa fa-'+FileManager.icon('directory')+' fa-fw" style="color:'+FileManager.color('directory','background')+'" data-url="'+name+'"></i></td></tr><tr><td title="'+name+'"><span>'+name+'</span></td></tr></table></li>';
			}
			if('undefined' === typeof who){
				for(i=0;i<json.files.length;i++){
					name = json.files[i][0];
					type = json.files[i][1];
					r += '<li data-type="'+type+'"><table><tr><td title="'+FileManager.text('fileType',type,true)+'"><i class="fa fa-'+FileManager.icon(type)+' fa-fw" style="color:'+FileManager.color(type,'background')+'" data-url="'+name+'"></i></td></tr><tr><td title="'+name+'"><span>'+name+'</span></td></tr></table></li>';
				}
				for(i=0;i<json.images.length;i++){
					name = json.images[i][0];
					type = json.images[i][1];
					if(name!=json.images[i][2] && json.images[i][2]!=='null') tmp = FileManager.managerFolder('assets/tempimg/'+json.images[i][2]);
					else tmp = url+name;
					r += '<li data-type="'+type+'"><table><tr><td title="'+FileManager.text('fileType',type,true)+'"><img class="fa" src="'+tmp+'" data-url="'+name+'"/></td></tr><tr><td title="'+name+'"><span>'+name+'</span></td></tr></table></li>';
				}
			}
			return [r,url,folders];
		},
		showList: function(url,fn){
			$(FileManager.id+' > .content > ul').css('visibility','hidden');
			$.post(FileManager.managerFolder('index.php'),{op:'list',path:url,'type':'all'},function(list){
				var htm = FileManager.editList(list);
				FileManager.folderList(url,htm[2]);
				$(FileManager.id + ' > .content > ul').html(htm[0]).css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0},100);
				$(FileManager.id).attr('data-showurl',htm[1]);
				$(FileManager.id + ' > .footer').html(list.folders.length+' '+FileManager.text('other','folders')+', '+(list.images.length+list.files.length)+' '+FileManager.text('other','files')+'<span style="margin-left:25px;display:unset" class="code">'+list.url+'</span>');
			},'json').always(function(){
				FileManager.btnCtrl('first');
				$(FileManager.id+' > .content').selectable({
					filter: 'li',
					stop: function(){
						var selected = $(FileManager.id+' > .content > ul > li.ui-selected').length;
						$('.ui-selected', this).each(function(){
							var
								index = $(FileManager.id+' > .content > ul > li').index(this),
							 who = $(FileManager.id+' > .content > ul > li').eq(index),
								whos = $(FileManager.id+' > .content > ul > li.ui-selected');
							if(who.hasClass('selected') && whos.length==1 && whos.index()==index){
								if(who.attr('data-type')=='directory') FileManager.showList($(FileManager.id).attr('data-showurl')+who.find('.fa').attr('data-url'));
								else $(FileManager.id+' #detail').click();
							}
						});
						$(FileManager.id+' > .content .selected').removeClass('selected');
						if(selected == 0){
							FileManager.btnCtrl('first');
						}
						else if(selected == 1){
							$(FileManager.id+' > .content > ul > li.ui-selected').addClass('selected');
							FileManager.btnCtrl('file');
						}
						else{
							FileManager.btnCtrl('multi');
						}
					}
				});
				$(FileManager.id+' .content li.ui-selectee').hover(function(){$(this).addClass('selecting');},function(){$(this).removeClass('selecting');});
				if('undefined' !== typeof fn) fn();
				FileManager.loading(0);
			}).fail(FileManager.postError);
		},
		folderList: function(url,folders){
			if(!$(FileManager.id+' > folderList .fa-spinner').length){
				var icon = ['fa-'+FileManager.icon('folder'),'fa-'+FileManager.icon('folder-o')];
				var who = $(FileManager.id+' > .folderList li[data-url="'+url+'"]');
				if(who.find('ul').html()==''){
					if('undefined' !== typeof folders)	who.find('.fa').removeClass(icon[0]).addClass(icon[1]).parent().find('ul').html(folders);
					else {
						who.find('.fa').removeClass(icon[0]).removeClass(icon[1]).addClass('fa-spinner fa-pulse');
						$.post(FileManager.managerFolder('index.php'),{op:'list',path:url,type:'folder'},function(json){
							var html = FileManager.editList(json,true);
							who.find('.fa').removeClass('fa-spinner fa-pulse').addClass(icon[1]).parent().find('ul').html(html[2]);
						},'json');
					}
				}
				else if(!who.find('ul').is(':visible')) who.find('ul').show().parent().find('.fa').eq(0).removeClass(icon[0]).addClass(icon[1]);
				else if(who.is(':hover')) who.find('ul').hide().parent().find('.fa').eq(0).removeClass(icon[1]).addClass(icon[0]);
			}
		},
		ucFirst: function(w){return w.charAt(0).toUpperCase() + w.slice(1)},
		realpath: function(path){
			if(typeof window === 'undefined') {
				var nodePath = require('path');
				return nodePath.normalize(path);
			}
			var p = 0, arr = [];
			path = (path + '').replace('\\', '/');
			arr = path.split('/');
			path = [];
			for(var k in arr){
				if(arr[k] === '.') continue;
				if(arr[k] === '..'){
					if(path.length > 3) path.pop();
				}
				else{
					if((path.length < 2) || (arr[k] !== '')) path.push(arr[k]);
				}
			}
			return path.join('/')
		},
		arrayFilter: function(a){var x=[];for(var i=0;i<a.length;i++)if(a[i].length)x.push(a[i]);return x;},
		rightClick: function(e){
			var li = $(FileManager.id+' ul[name=fileAndFolder] li'),btns='',btn;
			if($(FileManager.id+' .content li.ui-selectee').hasClass('selecting')){
				$(FileManager.id+' .content li.selecting').addClass('selected ui-selected');
				FileManager.btnCtrl('file');
			}
			for(var i=0;i<li.length;i++) if(li.eq(i).is('.disabled, .line')==false){
				btn = li.eq(i).find('a');
				btns += '<li onclick="$(\''+FileManager.id+' #'+btn.attr('id')+'\').click();">'+btn.html()+'</li>';
			}
			if($(FileManager.id+' #clipboard').parent().is('.disabled')==false) btns += '<li onclick="$(\''+FileManager.id+' #clipboard\').click();">'+$(FileManager.id+' #clipboard').html()+'</li>';
			FileManager.dialogCreate({
				title:FileManager.text('title','rightMenu'),
				content:'<ul class="rightMenu">'+btns+'</ul>',
				btn:'menu'
			},180,'auto',e);
		},
		dialogLock: function(i){
			if(i){
				$('.ui-dialog-buttonset button').css('visibility','hidden');
				$('.ui-dialog-buttonset').prepend('<span id="loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>'+FileManager.text('other','runing')+'</span>');
			}
			else{
				$('.ui-dialog-buttonset button').css('visibility','visible');
				$('.ui-dialog-buttonset #loading').remove();
			}
		},
		itemEdit: function(prcss,items){
			FileManager.loading(1);
			switch(prcss){
				case 'newFile':
				case 'newFolder':
					var folder = $(FileManager.id).attr('data-showurl');
					FileManager.dialogCreate({
						title:FileManager.text('title',prcss),
						content:'<ul style="margin:0;padding:0;list-style-type:none;"><li>'+FileManager.text('sure',prcss+'Message')+'</li><li style="margin:1em 0;"><span style="font-size:.8em">'+FileManager.text('title','itemLocation')+'</span>: <span class="code">'+folder+'</span></li><li><input type="text" name="name" placeholder="'+FileManager.text('sure',prcss+'Name')+'" /></li></ul>',
						btn:[{
							text:FileManager.text('button','save'),
							click:function(){
								var 
									name = $(FileManager.id+'Dialog input').val(),
									error = function(){
										FileManager.dialogCreate({
											title:FileManager.text('error','unsuccessful'),
											content:FileManager.text('error','notCreate'+prcss.substr(3))
										})
									};
								if(name.length<1 || name=='.' || name=='..' || name.search('/')!=-1 || name.search(/\\/)!=-1) error();
								else{
									FileManager.dialogLock(1);
									$.post(FileManager.managerFolder('index.php'),{op:'new',path:folder,name:name,type:prcss},function(r){
										FileManager.dialogLock(0);
										if(r==1)	FileManager.showList(folder);
										FileManager.dialogCreate({
											title:FileManager.text('title',prcss),
											content:FileManager.text((r==1?'done':'error'),(r==1?'':'un')+'successful')
										});
									});
								}
							}
						}]
					});
					FileManager.loading(0);
				break;
				case 'edit':
					$(FileManager.id+'Dialog').dialog('destroy');
					var folder = $(FileManager.id).attr('data-showurl'),open;
					open = window.open(
						FileManager.managerFolder('pages/itemEdit.php')+'?file='+folder+items+'&assets='+FileManager.managerFolder('assets')+'&lang='+$(FileManager.id).attr('data-language'),
						'_blank',
						'toolbar=0,location=0,directories=0,status=0,menubar=0,dependent=0,replace=0,channelmode=0,top='+($(window).height()-480)/2+',left='+($(window).width()-680)/2+',width=680,height=480',
						false
					);
					open.onbeforeunload = function(){FileManager.showList(folder);};
					FileManager.loading(0);
				break;
				case 'rename': 
					var folder = $(FileManager.id).attr('data-showurl');
					FileManager.dialogCreate({
						title:FileManager.text('title',prcss),
						content:'<ul style="margin:0;padding:0;list-style-type:none;"><li style="margin:1em 0;"><span style="font-size:.8em">'+FileManager.text('title','itemLocation')+'</span>: <span class="code">'+folder+'</span></li><li><input type="text" name="name" placeholder="'+items+'" value="'+items+'" /></li></ul>',
						btn:[{
							text:FileManager.text('button','save'),
							click:function(){
								var 
									name = $(FileManager.id+'Dialog input').val(),
									error = function(){
										FileManager.dialogCreate({
											title:FileManager.text('error','unsuccessful'),
											content:FileManager.text('error','unsuccessfulRename')
										})
									};
								if(name.length<1 || name=='.' || name=='..' || name.search('/')!=-1 || name.search(/\\/)!=-1) error();
								else {
									$.post(FileManager.managerFolder('index.php'),{op:'rename',path:folder,name:name,oldName:items},function(r){
										FileManager.dialogLock(0);
										if(r!=0)	FileManager.showList(folder);
										FileManager.dialogCreate({
											title:FileManager.text('title',prcss),
											content:FileManager.text((r!=1?'done':'error'),(r!=1?'':'un')+'successful')+'<br/><span class="code">'+r+'</span>'
										});
									});
								}
							}
						}]
					},'auto','auto');
					FileManager.loading(0);
				break;
				case 'delete':
					var path = '', folder = $(FileManager.id).attr('data-showurl');
					for(var i=0;i<items.length;i++) path += folder + items.eq(i).find('.fa').attr('data-url')+'|';
					FileManager.dialogCreate({
						title:FileManager.text('sure','areYouSure'),
						content:'<p>'+items.length+' '+FileManager.text('sure','fileDelete')+' <br/><small style="color:#a55">'+FileManager.text('sure','canNotBeDone')+'!'+'</small></p>',
						btn:[{
							text:FileManager.text('button','delete'),
							click:function(){
								FileManager.dialogLock(1);
								$.post(
									FileManager.managerFolder('index.php'),
									{op:'delete',path:path},
									function(json){
										FileManager.dialogLock(0);
										var html = '', scc = json.deleted, err = json.undeleted;
										if(scc.length>0){
											html += '<p style="color:#5a5;font-weight:bold">'+FileManager.text('done','successfulDelete')+'</p><pre>';
											for(var i=0;i<scc.length;i++) html += scc[i]+'<br/>';
											html = html.substr(0,html.length-5);
											html += '</pre>';
										}
										if(err.length>0){
											html += '<p style="color:#a55;font-weight:bold">'+FileManager.text('error','unsuccessfulDelete')+'</p><pre>';
											for(var i=0;i<err.length;i++) html += err[i]+'<br/>';
											html = html.substr(0,html.length-5);
											html += '</pre>';
										}
										$(FileManager.id+'Dialog').html(html);
										$('div.ui-dialog button.ui-button').not('.red').remove();
										$('div.ui-dialog button.ui-button.red').html(FileManager.text('button','close'));
										$('div.ui-dialog .ui-dialog-title').html(FileManager.text('done','successfulDelete'));
										FileManager.showList(folder);
									},
									'json'
								)
								.fail(FileManager.postError);
							}
						}]
					});
					FileManager.loading(0);
				break;
			}
		},
		itemArchive: function(prcss,items,type){
			var path = '', folder = $(FileManager.id).attr('data-showurl');
			if(prcss=='addArchive'){
				for(var i=0;i<items.length;i++) path += items.eq(i).find('.fa').attr('data-url')+'|';
				FileManager.dialogCreate({
					title:FileManager.text('button','archiveZip'),
					content:''
				});
				FileManager.dialogLock(1);
				$.post(
					FileManager.managerFolder('index.php'),
					{op:'create'+type,path:folder,files:path},
					function(json){
						var html = '',x;
						if(json.m.search('<br/>')!=-1){
							x = json.m.split('<br/>');
							html+='<b>'+FileManager.text('message',x[0])+'</b></br>'+x[1];
						}
						else html+='<b>'+FileManager.text('message',json.m)+'</b></br>';
						if(json.files!=0){
							html+='<pre>';
							for(var i=0;i<json.files.length;i++){
								if(json.files[i][1].search('<br/>')!=-1){
									x = json.files[i][1].split('<br/>');
									html+=json.files[i][0]+' -> '+FileManager.text('message',x[0])+x[1];
								}									
								else html+=json.files[i][0]+' -> '+FileManager.text('message',json.files[i][1]);
								html+='<br/>';
							}
							html+='</pre>';
							FileManager.showList(folder);
						}
						$(FileManager.id+'Dialog').html(html);
						$('div.ui-dialog button.ui-button').not('.red').remove();
						$('div.ui-dialog button.ui-button.red').html(FileManager.text('button','close'));
						FileManager.showList(folder);
						FileManager.dialogLock(0);
					},
					'json'
				)
				.fail(FileManager.postError);
			}
			else if(prcss=='unarchive'){
				FileManager.dialogCreate({
					title:FileManager.text('button','unarchive'),
					content:''
				},'auto','auto');
				FileManager.dialogLock(1);
				$.post(
					FileManager.managerFolder('index.php'),
					{op:'unzip',path:folder,file:folder+items},
					function(r,t){
						if(r==1 || r==0){
							FileManager.showList(folder);
							$(FileManager.id+'Dialog').html(FileManager.text((r==1?'done':'error'),(r==1?'':'un')+'successful'));
							$('div.ui-dialog button.ui-button').not('.red').remove();
							$('div.ui-dialog button.ui-button.red').html(FileManager.text('button','close'));
						}
						else FileManager.postError(r,t);
						FileManager.dialogLock(0);
					}
				)
				.fail(FileManager.postError);
			}
		},
		itemDownload: function(folder){
			var 
				file = $(FileManager.id+' .content li.ui-selected'),
				fileType = file.attr('data-type'),
				name = file.find('tr').text(),
				xhr = new XMLHttpRequest();
				xhr.open('POST', FileManager.managerFolder('index.php'), true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");;
				xhr.responseType = 'arraybuffer';
				xhr.onload = function(e) {
				if(this.status == 200) {
					var blob=new Blob([this.response], {type:fileType});
					var link=document.createElement('a');
					link.href=window.URL.createObjectURL(blob);
					link.download=name;
					link.click();
				}
			};
			xhr.send('op=download&path='+folder+file.find('.fa').attr('data-url'));
		},
		clipboard: function(op='list',p,items){
			switch(op){
				case 'list' :
					FileManager.loading(1);
					var 
						c = $(FileManager.id+' input[name=copys]').val(),
						m = $(FileManager.id+' input[name=moves]').val();
					if(m.length || c.length){
						var 
							folder = $(FileManager.id).attr('data-showurl'), 
							files='', 
							x, name,
							y=[c,m], yy=['copy','move'];
						for(var i=0;i<2;i++){
							x = FileManager.arrayFilter(y[i].split(','));
							for(var ii=0;ii<x.length;ii++){
								if(ii==0) files += '<li style="border-bottom:1px solid #666;"><b>'+FileManager.text('title',yy[i]+'sFiles')+'</b></li>';
								name = x[ii].split('/');
								name = name[name.length-1];
								files += '<li style="font-family:initial;margin:3px 0 2px 0;border-bottom:1px dotted #aaa;" title="'+x[ii]+'">'+name+'<span style="float:right;cursor:pointer;color:#f00;" onclick="FileManager.clipboard(\'remove\',\''+yy[i]+'\',\''+x[ii]+'\')">'+FileManager.text('button','cancel')+'</span></li>';
							}
						}
						FileManager.dialogCreate({
							title:FileManager.text('button','clipboard'),
							content:'<ul style="margin:0;padding:0;list-style-type:none;">'+files+'</ul>',
							btn:[{
								text:FileManager.text('button','completeAction'),
								click:function(){
									FileManager.dialogLock(1);
									$.post(FileManager.managerFolder('index.php'),{
										op:'move',
										path:folder,
										copys:$(FileManager.id+' input[name=copys]').val(),
										moves:$(FileManager.id+' input[name=moves]').val(),
									},
									function(r){
										FileManager.dialogLock(0);
										var c = ['error','un'];
										if(r.c==1){
											FileManager.showList(folder);
											$(FileManager.id+' input[name=copys]').val('');
											$(FileManager.id+' input[name=moves]').val('');
											$(FileManager.id+'Dialog').dialog('destroy');
											c = ['done',''];
										}
										FileManager.dialogCreate({
											title:FileManager.text('button','clipboard'),
											content:FileManager.text(c[0],c[1]+'successful')
										});
									},'json');
								}
							}]
						});
					}
					FileManager.loading(0);
				break;
				case 'add' :
					if(items.length){
						var x = $(FileManager.id+' input[name='+p+'s]').val(), y=[], z;
						for(var i=0;i<items.length;i++){
							z = $(FileManager.id).attr('data-showurl')+items.eq(i).find('.fa').attr('data-url');
							q = ','+x+',';
							if(q.search(','+z+',')==-1 && y.indexOf(z)==-1) y.push(z);
						}
						if(x!='') x += ',';
						if(y.length>0)$(FileManager.id+' input[name='+p+'s]').val(x+y.join(','));
						$(FileManager.id+'Dialog').dialog('destroy');
						FileManager.btnCtrl('first');
					}
				break;
				case 'remove' :
					var x = $(FileManager.id+' input[name='+p+'s]').val(), y=[], z;
					z = x.split(',');
					for(var i=0;i<z.length;i++) if(z[i]!=items) y.push(z[i]);
					$(FileManager.id+'Dialog li[title="'+items+'"]').remove();
					$(FileManager.id+' input[name='+p+'s]').val(y.join(','));
					if(y.length<1) $(FileManager.id+'Dialog').dialog('destroy');
				break;
			}
		},
		itemUpload: function(url){
			if(!$(FileManager.id+' .buttonGroup .progress').length) $(FileManager.id+' .buttonGroup #upload').parent().append('<span class="progress"></span>');
			var content ='', folder = $(FileManager.id).attr('data-showurl');
			if(!$(FileManager.id+'DialogUpload').length){
				var dialog = $('<div id="'+FileManager.id.substring(1)+'DialogUpload" class="'+FileManager.id.substring(1)+'Dialog" title="'+FileManager.text('title','fileUpload')+'"><form enctype="multipart/form-data" action="'+FileManager.dir+'index.php" method="post"><p style="margin:1em 0;"><span style="font-size:.8em">'+FileManager.text('title','itemLocation')+'</span>: <span class="code">'+folder+'</span></p><p><input type="file" name="file[]" id="file" multiple style="display:none;" onchange="FileManager.progress.detail(event)"/><input type="hidden" name="path" value="'+$(FileManager.id).attr('data-showurl')+'"/><input type="hidden" name="op" value="upload"/><label for="file">'+FileManager.text('button','upload')+'</label></p><div class="detail" style="max-height:'+$(window).height()*0.50+'px;overflow-y:scroll"></div><pre></pre></form></div>').dialog({
					autoOpen: true,
					width: '80%',
					height: 'auto',
					modal: true,
					close: function(){
						if(FileManager.uploading==false){
							/*$('div[aria-describedby="'+FileManager.id.substr(1)+'DialogUpload"] .ui-dialog-buttonset button').eq(0).show();
							$(FileManager.id+'DialogUpload form label').html(FileManager.text('button','upload')).show().removeClass('disabled').attr('onclick','');
							$(FileManager.id+'DialogUpload .detail').html('');*/
							$(FileManager.id+'DialogUpload').dialog('destroy');
						}
					},
					buttons: [
						{
							text:FileManager.text('button','upload'),
							click:function(){
								FileManager.progress.load();
							}
						},
						{
							text:FileManager.text('button','cancel'),
							click:function(){dialog.dialog("close")},
							'class':'red'
						}
					],
					closeText: FileManager.text('button','close')
				});
				dialog.dialog('open'),
				$(FileManager.id+'DialogUpload #file').click();
				$('.ui-widget-overlay').click(function(){
					$(FileManager.id+'Dialog').dialog('destroy');
					$(FileManager.id+'DialogUpload').dialog('close');
				});
			}
			else $(FileManager.id+'DialogUpload').dialog('open');
		},
		progress: {
			load: function(){
				var 
					bar = $(FileManager.id+' .buttonGroup .progress'), 
					btn = FileManager.text('other','uploading'), 
					label = $(FileManager.id+'DialogUpload form label'), 
					detail = $(FileManager.id+'DialogUpload form .detail'),
					folder = $(FileManager.id).attr('data-showurl');
				$(FileManager.id+'DialogUpload form').ajaxForm({
					beforeSend: function() {
						var percentVal = '0%';
						label.html(btn+' - '+percentVal).addClass('disabled').attr('onclick','return false');
						bar.width(percentVal);
						FileManager.dialogLock(1);
					},
					uploadProgress: function(event, position, total, percentComplete) {
						var percentVal = percentComplete + '%';
						FileManager.uploading = percentComplete;
						label.html(btn+' - '+percentVal);
						bar.width(percentVal);
					},
					complete: function(xhr){		
						var html = '',x;
						try{
						   var xhr = JSON.parse(xhr.responseText);
							if(xhr.m.search('<br/>')!=-1){
								x = xhr.m.split('<br/>');
								html+='<b>'+FileManager.text('message',x[0])+'</b></br>'+x[1];
							}
							else html+='<b>'+FileManager.text('message',xhr.m)+'</b></br>';
							if(xhr.files!=0){
								for(var i=0;i<xhr.files.length;i++){
									if(xhr.files[i][1].search('<br/>')!=-1){
										x = xhr.files[i][1].split('<br/>');
										html+=xhr.files[i][0]+' -> '+FileManager.text('message',x[0])+x[1];
									}									
									else html+=xhr.files[i][0]+' -> '+FileManager.text('message',xhr.files[i][1]);
									html+='<br/>';
								}
								FileManager.showList(folder);
							}
						}
						catch(e){
							html = FileManager.text('message','fileUploadErrorUnknow')+'<br/><br/>'+e;
						}
						label.hide();
						detail.html(html);
						FileManager.dialogLock(0);
						$('div[aria-describedby="'+FileManager.id.substr(1)+'DialogUpload"] .ui-dialog-buttonset button').eq(0).hide();
						$('div[aria-describedby="'+FileManager.id.substr(1)+'DialogUpload"] .ui-dialog-buttonset button').eq(1).html(FileManager.text('button','close'));
						bar.width('0');
						$(FileManager.id+'DialogUpload pre').html('');
						FileManager.uploading = false;
					}
				}).submit();
			},
			detail: function(event){
				var 
					text = '',
					size = 0,
					file = 0,
					files = $(FileManager.id+"DialogUpload #file").prop("files");
				if(!$(FileManager.id+'DialogUpload form .detail').length){
					$(FileManager.id+'DialogUpload form ul').append('<li class="detail" style="margin-top:15px;"></li>');
				}
				var detail = $(FileManager.id+'DialogUpload form .detail');
				if(files.length>0){
					var 
						names = $.map(files,function(val){return val.name}),
						sizes = $.map(files,function(val){return val.size}),
						types = $.map(files,function(val){return val.type});
					for(ii=0;ii<files.length;ii++){
						text+=FileManager.progress.editDetail(event.target.files[ii],names[ii],sizes[ii],types[ii]);
						size += sizes[ii];
						file++;;
					}
				}
				if(file!=0){
					$(FileManager.id+'DialogUpload').parent().css('top','3em');
					$(FileManager.id+'DialogUpload pre').html('Toplam '+file+' dosya, '+FileManager.progress.size(size));
					detail.html(text);
				}
			},
			editDetail: function(event,n,s,t){
				var 
					tt = t+'',
					ext = n,
					r = '';
				if(ext.search('.')!=-1){
					ext = n.split('.');
					ext = ext[ext.length -1];
				}
				tt = tt.split('/');
				switch(tt[0]){
					case'image': r = '<img src="'+URL.createObjectURL(event)+'"/>';	break;
					default: r = '<i class="fa fa-fw fa-file-o" data-ext="'+ext+'"></i>';
				}
				return '<span class="preview">'+r+'<small>'+n+'<br/>('+FileManager.progress.size(s)+')</small></span>';
			},
			size: function(bytes){
			var sonuc = "0 bytes";
				if(bytes>=1073741824){sonuc=(bytes/1073741824).toFixed(2)+' GB';}else 
				if(bytes>=1048576){sonuc=(bytes/1048576).toFixed(2)+' MB';}else 
				if(bytes>=1024){sonuc=(bytes/1024).toFixed(2)+' KB';}else 
				if(bytes>1){sonuc=bytes+' bytes';}else 
				if(bytes==1){sonuc=bytes+' byte';}
				else{sonuc = '0 bytes';};
				return sonuc;
			}
		},
		itemDetail: function(item){
			FileManager.loading(1);
			$.post(
				FileManager.managerFolder('index.php'),
				{
					op:'detail',
					path:item,
					type:$(FileManager.id+' > .content > ul > li.selected').attr('data-type')
				},
				function(json){
					var cont = '<table class="itemDetail"><tr><td style="text-align:center" width="120">'+(json.is_image==0?'<i class="fa fa-'+FileManager.icon(json.type)+' fa-fw" style="color:'+FileManager.color(json.type,'background')+'"></i>':'<a href="javascript:void(0)" onclick="$(\'#show\').click()"><img src="'+$('#files .content li.selected img').attr('src')+'" alt="'+json.name+'" /></a>')+'</td><td width="236">'+json.name+'</td></tr><tr><td>'+FileManager.text('title','itemType')+'</td><td title="'+json.type+'">'+FileManager.text('fileType',json.type,true)+'</td></tr><tr><td>'+FileManager.text('title','itemLocationServer')+'</td><td class="code" style="border-radius:0">'+json.location+'</td></tr><tr><td>'+FileManager.text('title','itemLocation')+'</td><td class="code" style="border-radius:0">'+json.location2+'</td></tr><tr><td>'+FileManager.text('title','itemSize')+'</td><td>'+json.size+'</td></tr><tr><td>'+FileManager.text('title','itemTime')+'</td><td>'+FileManager.time(json.date)+'</td></tr><tr><td>'+FileManager.text('title','itemPermission')+'</td><td>'+json.perm+'</td></tr></table>';
					var btns = [{
						text:FileManager.text('button','show'),
						id:'show',
						click: function(){
							var f = $('<form action="'+FileManager.managerFolder('index.php')+'" method="post" style="display:none;" target="_blank"><input type="hidden" name="op" value="show" /><input type="hidden" name="path" value="'+json.location2+'" /></form>').appendTo(document.body);
							f.submit();
							f.remove();
						}
					},{
						text:FileManager.text('button','download'),
						click: function(){FileManager.itemDownload($(FileManager.id).attr('data-showurl'))}
					}],type=json.type;
					
					if(type!='directory' && (type.indexOf('text')>-1 || type.indexOf('xml')>-1 || type.indexOf('javascript')>-1 || type.indexOf('x-empty')>-1 || type.indexOf('mage/')>-1)) btns.push({text:FileManager.text('button','edit'),click:function(){FileManager.itemEdit('edit',json.name)}});
					
					FileManager.dialogCreate({
						title:FileManager.text('title','itemDetail'),
						content:cont,
						btn:(json.type!='directory'?btns:undefined)
					},'490','auto');
				}
			,'json')
			.always(function(){
				FileManager.loading(0);
			})
			.fail(FileManager.postError);
		},
		postError(text,t){
			var html = '';
			if(typeof text.responseText !== 'undefined') html += 'ResponseText => '+text.responseText+'<br/>';	
			switch(text.readyState){
				case 0:	html += '<b>Network Error</b><br/>'+t; break;
				case 1: html += '<b>Opened Error</b><br/>'+t; break;
				case 2: html += '<b>Headers Error</b><br/>'+t; break;
				case 3: html += '<b>Loading Error</b><br/>'+t; break;
				case 4: html += '<b>HTTP Error</b><br/>'+text.responseText;	break;
			}
			if(typeof text.statusText !== 'undefined') html += '<br/>StatusText => '+text.statusText;
			FileManager.dialogCreate({
				title:FileManager.text('error','unsuccessful'),
				content:html
			});
		}
	}
	// if($(FileManager.id) !== undefined && $(FileManager.id).length!==0)	FileManager.managerCreate();
}