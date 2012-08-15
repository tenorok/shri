
var Core=(function($){var core={},parseUrl=function(path){var url=path.split('/');for(var i=0;i<url.length;i++){if(url[i]===''||url[i]==location.protocol||url[i]==location.hostname){url.splice(i,1);i--;}}
return url;},curLocation,request=function(){if(curLocation==location.href)
return;curLocation=location.href;window.ARGS={};var href=parseUrl(curLocation);nextroute:for(var route=0;route<routes.length;route++){var url=routes[route].url,ctrl=routes[route].ctrl,func=routes[route].func,rules=routes[route].rules||null,path=parseUrl(url);if(href.length!=path.length)
continue;var args=[];for(var part=0;part<path.length;part++){var arg=/^{(.*)}$/.exec(path[part]);if(arg){var rule;if(rules===null||(rule=rules[arg[1]])===undefined||rule.test(href[part])){window.ARGS[arg[1]]=href[part];args.push(href[part]);}
else{window.ARGS={};continue nextroute;}}
else if(href[part]!=path[part]){window.ARGS={};continue nextroute;}}
var method=eval(ctrl)[func];if(method)
method.apply(null,args);else
console.error('Function of Controller is undefined');break;}},monitor=function(){if(Modernizr.hashchange)
$(window).on('hashchange popstate locchange',request);else
setInterval(request,500);},saveLess=function(){var set=settings.saveless||null;if(set&&DEV){var links=$('link[rel="stylesheet/less"]');var css={},fileKey,fileHref,lessId;$.each(links,function(key,value){fileKey=$(value).attr('data-file');fileHref=$(value).attr('href');lessId=fileHref.replace(/\//g,'-').slice(1,-5);css[fileKey]=css[fileKey]||'';css[fileKey]+=$('style#less\\:'+lessId).html();});var compress=(set.compress===undefined||set.compress)?true:false;$.post('/sys/ajax/less.php',{event:'save_lesscss',css:JSON.stringify(css),path:set.path,compress:compress});}},init=function(){$(function(){saveLess();request();monitor();});return core;};core.locChange=function(func){func();$(window).trigger('locchange');};return init();}(jQuery));﻿
{var settings={saveless:{path:'/assets/css/'}};var routes=[{url:'/',ctrl:'index',func:'init'},{url:'/#/{anchor}',ctrl:'index',func:'init'}];};﻿var index={init:function(anchor){$('.menu__link_sub_yes').unbind('click').click(index.toggleSubMenu);if(anchor!==undefined){var menu=anchor.split('-')[0];var link=$('.menu__link_sub_yes[href="#/'+menu+'"]');if($(link).next('.menu__submenu').is(':hidden'))
$(link).trigger('click');}},toggleSubMenu:function(){var subMenu=$(this).next('.menu__submenu');var arrow=$(this).children('.menu__link-arrow');if($(subMenu).is(':hidden')){$(subMenu).slideDown('fast');$(arrow).html('&#9650;');}
else{$(subMenu).slideUp('fast');$(arrow).html('&#9660;');}
return false;}};window.DEV=true;