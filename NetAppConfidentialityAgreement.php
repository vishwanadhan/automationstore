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
		$("#notaccordion").addClass("ui-accordion ui-accordion-icons ui-widget ui-helper-reset  ")
                  .find("h3")
                                .addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-top ui-corner-bottom ui-accordion-header-active ")
                                .hover(function() { $(this).toggleClass("ui-state-hover"); })
                                .click(function(){
                                                elementToExpand = $(this);
                                                accordionClickHandler()
                                })
                                .next()
                                  .addClass("ui-accordion-content  ui-helper-reset ui-widget-content ui-corner-bottom inner-ul")
                                  .show();


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
	<!-- <h2>How to Use workflow packs </h2>-->
	</br>

  <div id="notaccordion">
								
									
									<h3 >
										<div class="accordion-bar-wrapper">
											<a name="silver-partners" id="silver" class="accordion-bar">NetApp Confidentiality Agreement</a>
											
										</div>
									</h3>
									
									<div class="inner-ul">
                                     
      <p>To start executing the automated workflows, you need to download the workflow packs from the Storage Automation Store and then import the packs into OnCommand Workflow Automation (WFA) software.<br><br>
        <strong>Before you begin : </strong>
        <ul>
        	<li>You must have installed WFA 3.0.</li>
            <li>You must have the necessary WFA login credentials.</li>
            <li>You must have the operator role to execute the workflows in WFA.</li>
        </ul>
     <br>
        <strong>Steps</strong>
       <ol> 
       <li> Log in to Storage Automation Store.</li>
		<li> Using the Search filter, locate the appropriate workflow pack.</li>
<li>Click <strong>Download Pack</strong>.</li>
<li> Save the pack in your local directory.</li>
<li> Log in to WFA by providing necessary credentials./li>
<li> Click <strong>Administration &gt; Import</strong></li>
<li>Locate the .dar file in your directory, and then click <strong>Open</strong>.
The workflow pack is imported to WFA.
<br>
<strong>After you finish:</strong>
Based on the category specified in the workflow pack description, you should locate your workflow in the WFA portal and execute the workflow
For more information about how to execute a workflow, see the workflow-specific Help.</strong>
wfa-pack pack create -info=pack_info_file -dar=dar_file_path -
output=new_pack_file_path</li>
<li> <strong>Verify that the pack file is created successfully:</strong>
wfa-pack pack show - pack=new_pack_file_path</li>
  </ol>
     
       
       <br>          
       <strong>Deleting a workflow:</strong><br>
       If you do not need a workflow for use, you can delete the workflow by logging into WFA with administrator privileges.   
       <ol>
       		<li>Click Designer tab.</li>
            <li>Using the Search filter, locate the workflow you want to delete.</li>
            <li>Right-click on the workflow name, and then click <strong>Delete</strong></li>
       </ol>                   
</div>
								
									
									
									
								
	</div>
</div>
</div>
<?php

// site head js include here 
include('includes/footer.php');
?>  

</body>
</html>

    
  
