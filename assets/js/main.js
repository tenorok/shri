﻿
var Core=(function($){var core={},parseUrl=function(path){var url=path.split('/');for(var i=0;i<url.length;i++){if(url[i]===''||url[i]==location.protocol||url[i]==location.hostname){url.splice(i,1);i--;}}
return url;},curLocation,request=function(){if(curLocation==location.href)
return;var firstCall=(curLocation===undefined)?true:false;curLocation=location.href;window.ARGS={};var href=parseUrl(curLocation);nextroute:for(var route=0;route<routes.length;route++){var url=routes[route].url,ctrl=routes[route].ctrl,func=routes[route].func,call=routes[route].call||'ever',rules=routes[route].rules||null,pathArr=[];if(typeof(url)=='string')
pathArr[0]=parseUrl(url);else{for(var p=0;p<url.length;p++)
pathArr[p]=parseUrl(url[p]);}
nextpath:for(var p=0;p<pathArr.length;p++){var path=pathArr[p];if(href.length!=path.length||call=='load'&&!firstCall)
continue nextpath;var args=[];for(var part=0;part<path.length;part++){var arg=/^{(.*)}$/.exec(path[part]);if(arg){var rule;if(rules===null||(rule=rules[arg[1]])===undefined||rule.test(href[part])){window.ARGS[arg[1]]=href[part];args.push(href[part]);}
else{window.ARGS={};continue nextpath;}}
else if(href[part]!=path[part]){window.ARGS={};continue nextpath;}}
var method=eval(ctrl)[func];if(method)
method.apply(null,args);else
console.error('Function of Controller is undefined');break nextroute;}}},monitor=function(){if(Modernizr.hashchange)
$(window).on('hashchange popstate locchange',request);else
setInterval(request,500);},saveLess=function(){var set=settings.saveless||null;if(set&&DEV){var links=$('link[rel="stylesheet/less"]');var css={},fileKey,fileHref,lessId,tag,lessTags=[];$.each(links,function(key,value){fileKey=$(value).attr('data-file');fileHref=$(value).attr('href');lessId=fileHref.replace(/\//g,'-').slice(1,-5);css[fileKey]=css[fileKey]||'';tag=$('style#less\\:'+lessId);lessTags.push(tag)
css[fileKey]+=tag.html();});var compress=(set.compress===undefined||set.compress)?true:false;$.post('/sys/ajax/less.php',{event:'save_lesscss',css:JSON.stringify(css),path:set.path,compress:compress},function(){for(var t=0;t<lessTags.length;t++)
lessTags[t].remove();});}},init=function(){$(function(){saveLess();request();monitor();});return core;};core.locChange=function(func){func();$(window).trigger('locchange');};return init();}(jQuery));﻿
{var settings={saveless:{path:'/assets/css/'}};var routes=[{url:['/','/#/{anchor}'],ctrl:'index',func:'init',call:'load'},{url:'/print',ctrl:'index',func:'initPrint',call:'load'}];};﻿var index={init:function(anchor){menuMod.init(anchor);hljs.initHighlightingOnLoad();infoMod.copyClipboardInit();contentMod.init();},initPrint:function(){hljs.initHighlightingOnLoad();}};window.DEV=false;﻿var contentMod={init:function(){$('.content__link_type_print').click(contentMod.openPrintVer);},openPrintVer:function(){var url=$(this).attr('href');window.open(url,'Версия для печати','left=20px, top=0, width=700px, scrollbars=yes');return false;}};﻿var infoMod={copyClipboardInit:function(){ZeroClipboard.setMoviePath('/assets/flash/ZeroClipboard.swf');var clip={},button='<button id="{id}" class="info__copy">копировать</button>',copiedtext='скопировано';$('.info__copy-code').each(function(i){var id='pre_'+i,ids='#'+id;$(this).prepend(button.replace('{id}',id));clip[id]=new ZeroClipboard.Client();clip[id].setText($(this).find('code').html());clip[id].glue(id);$(window).bind('load resize',function(){clip[id].reposition();});clip[id].addEventListener('onComplete',function(){var buttonText=$(ids).text();$(ids).text(copiedtext).addClass('info__copy_state_copied');setTimeout(function(){$(ids).text(buttonText).removeClass('info__copy_state_copied');},1000);});});}};﻿var menuMod={init:function(anchor){$('.menu__link_sub_yes').unbind('click').click(menuMod.toggleSubMenu);$('.menu__link, .menu__submenu-link').not('.menu__link_sub_yes').unbind('click').click(menuMod.gotoAnchor);if(anchor!==undefined){var menu=anchor.split('-')[0],link=$('.menu__link_sub_yes[href="#/'+menu+'"]');if(link.next('.menu__submenu').is(':hidden'))
link.trigger('click');}},toggleSubMenu:function(){var subMenu=$(this).next('.menu__submenu'),arrow=$(this).children('.menu__link-arrow');if($(subMenu).is(':hidden')){$(subMenu).slideDown('fast');$(arrow).html('&#9650;');}
else{$(subMenu).slideUp('fast');$(arrow).html('&#9660;');}
return false;},gotoAnchor:function(){var href=$(this).attr('href'),anchorId=href.substring(1),anchorPosTop=$('(h1, h2)[id="'+anchorId+'"]').offset().top;$('html, body').animate({scrollTop:anchorPosTop},'fast',function(){location.hash=href;});return false;}};