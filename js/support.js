$(function(){
var appendthis =  ("<div class='modal-overlay js-modal-close'></div>");
	$('a[data-modal-id]').click(function(e) {	 
		e.preventDefault();
    $("body").append(appendthis);
    $(".modal-overlay").fadeTo(500, 0.7);
    //$(".js-modalbox").fadeIn(500);
		var modalBox = $(this).attr('data-modal-id');
		$('#'+modalBox).fadeIn($(this).data());
		
		var text =0;
		for(var i=0; i<100; i++)
		{
			text = i;
			
 		var windowWidth = document.documentElement.clientWidth;
        var windowHeight = document.documentElement.clientHeight;
        var popMargTop = jQuery("#popupNETAPP"+text).height();
        var popMargLeft = jQuery("#popupNETAPP"+text).width();
         var top=Math.floor((windowHeight-popMargTop)/2);
                               var left=Math.floor((windowWidth-popMargLeft)/2);
                               jQuery("#popupNETAPP"+text).css({ 
                                 "position": "fixed",
                                   "top": top,
                                   "left": left
                               });
		var popMargTop = jQuery("#popupNONE"+text).height();
        var popMargLeft = jQuery("#popupNONE"+text).width() ;
         var top=Math.floor((windowHeight-popMargTop)/2);
                               var left=Math.floor((windowWidth-popMargLeft)/2);
                               jQuery("#popupNONE"+text).css({ 
                                 "position": "fixed",
                                   "top": top,
                                   "left": left
                               });
		 var popMargTop = jQuery("#popup"+text).height();
        var popMargLeft = jQuery("#popup"+text).width() ;
         var top=Math.floor((windowHeight-popMargTop)/2);
                               var left=Math.floor((windowWidth-popMargLeft)/2);
                               jQuery("#popup"+text).css({ 
                                 "position": "fixed",
                                   "top": top,
                                   "left": left
                               });
							   }

	});  
  
  
$(".js-modal-close, .modal-overlay").click(function() {
    $(".modal-box, .modal-overlay").fadeOut(500, function() {
        $(".modal-overlay").remove();
		
    });
 
});
 
$(window).resize(function() {
    $(".modal-box").css({
        top: ($(window).height() - $(".modal-box").outerHeight()) / 2,
        left: ($(window).width() - $(".modal-box").outerWidth()) / 2
    });
});
 
$(window).resize();
 
});


$('input,textarea[placeholder]').on('focus', function () {
    var $this = $(this);
    $this.data('placeholder', $this.prop('placeholder')).removeAttr('placeholder')
}).on('blur', function () {
    var $this = $(this);
    $this.prop('placeholder', $this.data('placeholder'));
});

$(
  function(){
      $(".rows").click(
        function(e){
            alert("Clicked on row");
            alert(e.target.innerHTML);
        }
      )
  }
)

	$(document).on('ready ajaxComplete', function() {
	
			var moveLeft = -340;
			var moveDown = -80;
			
			$('a.triggersmall').hover(function(e) {
			  $(this).next('div.pop-up').show();
			  
			  //.css('top', e.pageY + moveDown)
			  //.css('left', e.pageX + moveLeft)
			  //.appendTo('body');
			}, function() {
			  $('div.pop-up').hide(); 
			});
			
			$('a.triggersmall').mousemove(function(e) {
			  $("div.pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
			});
	});

  $(function() {

        if(window.innerWidth > 1500 && window.innerHeight > 700){
          var moveLeft = -520;
          var moveDown = -80;
        }else{
          var moveLeft = -445;
          var moveDown = -80;
        }
        $('a.trigger').hover(function(e) {
          
		  $(this).next('div.pop-up').show();
		  
          //.css('top', e.pageY + moveDown)
          //.css('left', e.pageX + moveLeft)
          //.appendTo('body');
        }, function() {
          $('div.pop-up').hide();
        });
        
        $('a.trigger').mousemove(function(e) {
          $("div.pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
        });
        
      });
	  
	  
	  $(function() {
        var moveLeft = -380;
        var moveDown = -80;
        
        $('a.triggerAdmin').hover(function(e) {
          
		  $(this).next('div.pop-up').show();
		  
          //.css('top', e.pageY + moveDown)
          //.css('left', e.pageX + moveLeft)
          //.appendTo('body');
        }, function() {
          $('div.pop-up').hide();
        });
        
        $('a.triggerAdmin').mousemove(function(e) {
          $("div.pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
        });
        
      });
	  ////for Profile tabs /////

$(document).ready(function() {
$.fn.simpleTabs = function(){ 
	//Default Action
	$(this).find(".tab_content").hide(); //Hide all content
	$(this).find("ul.tabs li a:first").addClass("selected").show(); //Activate first tab
	$(this).find(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$(this).parent().find("li a.selected").removeClass("selected"); //Remove any "active" class
		$(this).find("a").addClass("selected"); //Add "active" class to selected tab
		$(this).parent().parent().find(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).show(); //Fade in the active content
		return false;
	});


};//end function

$("div[class^='simpleTabs']").simpleTabs(); //Run function on any div with class name of "Simple Tabs"


});
	