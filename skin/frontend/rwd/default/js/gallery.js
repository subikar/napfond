// JavaScript Document
var api;
var rotate;
var menubar=false;
$(function() {
    $('#mycarousel').jcarousel({
        size: total,
        scroll: scroll,
        wrap: 'last',
        itemLoadCallback: itemLoadCallbackFunction,
        itemFirstInCallback: setNewIndex
    });
    $("div.jcarousel-clip-horizontal").css('width', clipwidth);
    $("div.jcarousel-container").css('width', containerwidth);
});
function itemLoadCallbackFunction(carousel, state){
    if (carousel.has(carousel.first, carousel.last)) {
        return;
    }
    $.get(ajaxurl+'getNextSet.php',{
        first: carousel.first,
        last: carousel.last,
        category_id:category_id,
        user_id:user_id
    },function(json) {
        itemAddCallback(carousel, carousel.first, carousel.last, json);
    },'json');
}

function itemAddCallback(carousel, first, last, json){
    menubar = false;
    $(json).each(function(i) {
        carousel.add(first + i, '<a class="shadowboxCustom" id="'+json[i].id+'" height="'+json[i].height+'" href="/photos/content/'+json[i].id+'.php" title="'+json[i].title+'"><img style="width:65px; height:65px; border:2px solid white;" src="'+json[i].thumb+'" alt="'+json[i].title+'" /></a>');
    });
    $('a.shadowboxCustom').click(function(){
       menubar = true;
       mytitle = $(this).attr('title');
       $("#stage").empty();
       iheight = $(this).attr('height');
       if( iheight < 450 ){
           iheight = 450;
       }
       $("#stage").css({"height":"" + iheight + "px"})
       $("#stage").css({"width":"100%"})
       $("#stage").load( "/photos/content/"+$(this).attr('id')+".php", {title:$(this).attr('title')});
       return false;
    });
    $("#waiting").ajaxStart(function(){
       if( menubar ){
          $("#waiting").css({"height":"450px"});
          $("#stage").css({"height":"0px"});
          $("#waiting").show();
          if( api ){
             api.setOptions({hide: true });
             api.update();
             delete api;
          }
       }
    });
    $("#waiting").ajaxStop(function(){
       if( menubar ){
          $("#waiting").css({"height":"0px"});
          $("#stage").css({"height":"" + iheight + "px"});
          $("#stage").css({"height":"auto"});
          $("#waiting").hide();
        }
        menubar = false;
    });
    $("div#stage").mousemove(function(){
         $("#currentTitle").html(mytitle);
    });
    $("a.shadowboxCustom").hover(function(){
        $("#currentTitle").html($(this).attr('title'));
    }, function(){
        if( !$("#imgContainer").html()){
            $("#currentTitle").html('');
        }else{
            $("#currentTitle").html(mytitle);
        }
    })
}
function setNewIndex(carousel, element, index, state){
   return true;
}

