var matchMediaAlllListenre = true;
if(matchMediaAlllListenre){
(function(){if(window.matchMedia&&window.matchMedia('all').addListener){return false;}
var localMatchMedia=window.matchMedia,hasMediaQueries=localMatchMedia('only all').matches,isListening=false,timeoutID=0,queries=[],handleChange=function(evt){clearTimeout(timeoutID);timeoutID=setTimeout(function(){for(var i=0,zz=queries.length;i < zz;i++){var mql=queries[i].mql,listeners=queries[i].listeners||[],matches=localMatchMedia(mql.media).matches;if(matches!==mql.matches){mql.matches=matches;for(var j=0,jl=listeners.length;j < jl;j++){listeners[j].call(window,mql);}}}},30);};window.matchMedia=function(media){var mql=localMatchMedia(media),listeners=[],index=0;mql.addListener=function(listener){if(!hasMediaQueries){return;}
if(!isListening){isListening=true;window.addEventListener('resize',handleChange,true);}
if(index===0){index=queries.push({mql:mql,listeners:listeners});}
listeners.push(listener);};mql.removeListener=function(listener){for(var i=0,zz=listeners.length;i < zz;i++){if(listeners[i]===listener){listeners.splice(i,1);}}};return mql;};}());
}