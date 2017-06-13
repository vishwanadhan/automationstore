<!DOCTYPE html>
<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();

$packObj = new Pack();

$searchText = (isset($_POST['search']) ? $_POST['search'] : null);


// site head js include here 
include('includes/head.php');
?>   
<script language='javascript' type='text/javascript' src='js/expandcollapse.js'></script>




<script>
var hash;
var elementToExpand;
var contentExpanding;
var accordElems;
var allExpanded = false;
$(document).ready(function() {
	//toggleUnsupportedBrowserMessage();
	if($.browser.msie && $.browser.version=="6.0"){
		toggleUnsupportedBrowserMessage();
	}
	
	hash = window.location.hash.substring(1);
	elementToExpand = $("#" + hash);
    accordElems = $("#notaccordion").find("h3");

	$("#notaccordion").addClass("ui-accordion ui-accordion-icons ui-widget ui-helper-reset")
	  .find("h3")
		.addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom")
		.hover(function() { $(this).toggleClass("ui-state-hover"); })
		.click(function(){
			elementToExpand = $(this);
			accordionClickHandler()
		})
		.next()
		  .addClass("ui-accordion-content  ui-helper-reset ui-widget-content ui-corner-bottom inner-ul")
		  .hide();
		

	if(window.location.hash.lastIndexOf("#") != -1 && accordElems.length != 1)
	{

		if(hash == "allExpand")
		{
			expandAll();
		}else{
			accordionClickHandler();
		}
	}

	$("#ie6-content").vAlign();
	$("#ie6-content").hAlign();	
	
	if(accordElems.length == 1)
	{
		$("#notaccordion").find(accordElems[0]).click();
	};
});

function expandAll()
{
	var childElem;
	$(accordElems).each(function(i)
	{
		childElem = $(accordElems[i]).next();
		if($(childElem).hasClass("ui-accordion-content-active") == false)
		{
			// expand
			$(accordElems[i]).click();
		}
	});
}

function collapseAll()
{
	var childElem;
	$(accordElems).each(function(i)
	{
		childElem = $(accordElems[i]).next();
		if($(childElem).hasClass("ui-accordion-content-active") == true)
		{
			// expand
			$(accordElems[i]).click();
		}
	});
}

function toggleUnsupportedBrowserMessage(){
	var lmContent = $("#lm-content")[0];
	//alert($(lmContent).css("display"));
	if($("#unsupportedBrowser").css("display") == "none")
	{
		$("#form").hide();
		$("#unsupportedBrowser").show();
	}else{
		$("#form").show();
		$("#unsupportedBrowser").hide();
	}
}
  
function accordionClickHandler(){
  var elementId = elementToExpand.attr("id");
  if($("."+elementId)[0]){
	  var linkElement = $("."+elementId)[0];
	  if($(linkElement).attr("src") == "images/externalLink_blue.png"){
		linkElement.src = "images/externalLink_white.png";
	  }else{
		linkElement.src = "images/externalLink_blue.png";
	  }
  }
  contentExpanding = $(elementToExpand).next();
  $(elementToExpand)
	.toggleClass("ui-accordion-header-active ui-state-active ui-state-default ui-corner-bottom")
	.next().toggleClass("ui-accordion-content-active").slideToggle($(contentExpanding).height()+300);
}







</script>

<body>
<?php
    //site header include here  
    include('includes/header-landing.php');
    ?>
    <div id="nav-under-bg">
        <!-- -->
    </div>

  <div id="body_content">
  <section class="back-link">
						<a href="home.shtml"> &lt; Back</a>
						</section>

 <h2>Support FAQs</h2>
 <div class="acc-upperlink">
	  <a onClick="expandAll();">Expand All</a> | <a onClick="collapseAll();">Collapse All</a>
      
      </div>
      
      <div id="notaccordion">
								
									
									<h3 id="silver-partners">
										<div class="accordion-bar-wrapper">
											<a name="silver-partners" class="accordion-bar">What content is fully supported by NetApp?</a>
											
										</div>
									</h3>
									
									<div class="inner-ul">
                                     
      
     
									All NetApp developed content marked with <img src="images/netapp-certified-icon.png" width="15px" height="15px"  style="vertical-align: middle;"> logo would be fully supported</li>

                                    
</div>
								
									
									<h3 id="why-netapp">
										<div class="accordion-bar-wrapper">
											<a name="why-netapp" class="accordion-bar">What type of support does NetApp provide for the supported content?</a>
											
										</div>
									</h3>
									
									<div>
									  NetApp Technical Support would provide support in resolving issues pertaining to <b>importing</b>, <b>executing</b>, and <b>unexpected</b> outcome of the NetApp developed content
									</div>
								
									
									<h3 id="getting-started">
									  <div class="accordion-bar-wrapper">
											<a name="getting-started" class="accordion-bar">What is not supported by NetApp?</a>
											
										</div>
									</h3>
									
									<div>
									     The NetApp Technical Support will not provide support for the following:
                                    <ul >
									<li>Any NetApp developed content that is customized or cloned after being downloaded from Storage Automation Store</li>
									<li>Any content developed and posted on the Storage Automation Store by a 3rd Party</li>
									</ul>
									</div>
								
									
									<h3 id="solutions">
										<div class="accordion-bar-wrapper">
											<a name="solutions" class="accordion-bar">Where to go for help on non-NetApp supported content?</a>
											
										</div>
									</h3>
									
									<div>
                                  
		For any queries about the non-NetApp supported content please Use <a target="_blank" href="http://community.netapp.com/t5/OnCommand-Storage-Management-Software-Discussions/bd-p/oncommand-storage-management-software-discussions">NetApp Community</a> for assistance 
    
        </div>
		<h3 id="solutions">
										<div class="accordion-bar-wrapper">
											<a name="solutions" class="accordion-bar">Where do I get more information about NetApp's Storage Automation  Store?</a>
											
										</div>
									</h3>
									
									<div>
                                  
		For any general queries about the Storage Automation Store please use <a target="_blank" href="http://community.netapp.com/t5/OnCommand-Storage-Management-Software-Discussions/bd-p/oncommand-storage-management-software-discussions">NetApp Community</a> for assistance.
    
        </div>
								
									
									
								
	</div>
</div>
<?php

// site head js include here 
include('includes/footer.php');
?>  

</body>
</html>

    
  

