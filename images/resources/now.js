var timeout = null;
var ie = (document.all) ? true : false;
var $mainLink = null;
var $subLink = null; 

$(".mainLinks").live('mouseover mouseout', function(event) {
 	 if (event.type == 'mouseover') {
//alert("Other BrowsersInside");
				reset();
				ieCompat('hide');
				
				// Saving this for later use in the popUpNav hover event
				$mainLink = $(this);	
			
				$popUpNav = $(".popUpNav", $mainLink.parent());
				
				// Default width is width of one column
				var popupWidth = $('.popUpNavSection').width() + 20;
				
				// Calculate popup width depending on the number of columns
				var numColumns = $popUpNav.find('.popUpNavSection').length;
				
				if (numColumns != 0) {
					popupWidth *= numColumns;
				}
				
				var elPos = $mainLink.position();
				var leftOffset = 0;

				if (elPos.left + popupWidth > 950) {
					leftOffset = elPos.left + popupWidth - 948;
				}
			
				$popUpNav.css({
					top : elPos.top + 31 + 'px',
					left : elPos.left - leftOffset + 'px',
					visibility : 'visible',
					width : popupWidth + 'px'
				});
			
				$('.popUpArrow').css({
					left : elPos.left + Math.round(($mainLink.width() / 2)) + 20 + 'px',
					top : '27px'
				}).show();
				
			
		
  	}
  	else{	//alert($(this).attr('id'));
	
			//	function() {
				var $this = $(this);			
				timeout = setTimeout(function() {
					$(".popUpNav", $this.parent()).css({
						visibility : 'hidden'
					});
					$('.popUpArrow').hide()
					ieCompat('show');
				}, 200);
			//});
	}
return false;
});


$(".subLinks").live('mouseover mouseout', function(event) {
  	if (event.type == 'mouseover') {
			$subLink = $(this);
			var elPos = $subLink.position();
			var popupWidth = $(".popUpNavLv2",$subLink.parent()).width();
			var leftOffset = 0;
			ieCompat('hide');
			$(".popUpNavLv2",$subLink.parent()).css({
				top : elPos.top + 32 + 'px',
				left : elPos.left - leftOffset + 'px',
				visibility : 'visible'
			});
		}
		else{
			var $this = $(this);
			timeout = setTimeout(function() {
			$(".popUpNavLv2", $this.parent()).css({
					visibility : 'hidden'
				});
			}, 200);
			ieCompat('show');
		}
return false;
});

$(".popUpNav").live('mouseover mouseout', function(event){
  	if (event.type == 'mouseover') {
			clearTimeout(timeout);			
			$mainLink.addClass('current');
			$(this).css('visibility', 'visible');
			$('.popUpArrow').show();
		}
	else{
			if(!$mainLink.hasClass('current currentPage')){
				$mainLink.removeClass('current');
			}
			$(this).css('visibility', 'hidden');
			$('.popUpArrow').hide();
			ieCompat('show');
		}	
return false;
});

$(".popUpNavLv2").live('mouseover mouseout', function(event){
  	if(event.type == 'mouseover') {	
			clearTimeout(timeout);
			$(this).css('visibility', 'visible');
			ieCompat('hide');
		}
	else{
			ieCompat('show');
			$(this).css('visibility', 'hidden');
			
		}
return false;	
});
	
// If on mac, reduce left padding on the tabs
if (/mac os x/.test(navigator.userAgent.toLowerCase())) {
		$('.mainLinks, .mainLinksHome').css('padding-left', '14px');
   }

function reset() {
	clearTimeout(timeout);	
	$('.popUpNav').css('visibility', 'hidden');
	$('.popUpNavLv2').css('visibility', 'hidden');
	$('.popUpArrow').hide();
	return false;
}

var Browser = {  Version: function() {    var version = 999; // we assume a sane browser    
if (navigator.appVersion.indexOf("MSIE") != -1)      // bah, IE again, lets downgrade version number     
 version = parseFloat(navigator.appVersion.split("MSIE")[1]);    return version; 
                 }
}
 


function ieCompat(status) {
	if (ie) {
		if(Browser.Version()< 7) { // if client is using IE6 or lower, run this code}
				if (status == 'show') {
					$('select').css('visibility', 'visible');
				}
				else {
					$('select').css('visibility', 'hidden');
				}
		}
	}
}



/*For Search Block Hover Menu*/

function hidePopup(popupNo){
//alert("popupNo");
	document.getElementById("searchPop"+popupNo).style.visibility='hidden';
	document.getElementById("searchPopArrow").style.visibility='hidden';
}

function showPopup(obj,linkType,popupNo){

//alert("showPopup");
	if(popupNo==1){
		document.getElementById("searchPop2").style.visibility='hidden';
	}else{
		document.getElementById("searchPop1").style.visibility='hidden';
	}
	var popup= document.getElementById("searchPop"+popupNo);
	var popupArrow = document.getElementById("searchPopArrow");
	objPos = findPos(obj);
	popup.style.left=objPos.left+"px";
	popup.style.top=objPos.top+15+"px";
	popup.style.visibility="visible";

	popupArrow.style.left=objPos.left+20+"px";
	popupArrow.style.top=objPos.top+11+"px";
	popupArrow.style.visibility="visible";

}

function findPos(obj) {
 var obj2 = obj;
 var curtop = 0;
 var curleft = 0;
 if (document.getElementById || document.all) {
  do  {
   curleft += obj.offsetLeft-obj.scrollLeft;
   curtop += obj.offsetTop-obj.scrollTop;
   obj = obj.offsetParent;
   obj2 = obj2.parentNode;
   while (obj2!=obj) {
				curleft -= obj2.scrollLeft;
				curtop -= obj2.scrollTop;
				obj2 = obj2.parentNode;
   }
  } while (obj.offsetParent)
 } else if (document.layers) {
  curtop += obj.y;
  curleft += obj.x;
 }
 return {"top":curtop, "left":curleft};
}





