<!DOCTYPE html>
<?php
session_start();
ob_start();

require_once('config/configure.php');
require_once('includes/function/autoload.php');


$loginObj = new Login();
$pageName = getPageName();

$packObj = new SnapPack();

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
    </div>

  <div id="body_content">
	<section class="back-link">
			<a href="javascript:history.back()"> &lt; Back</a>
	</section>
  <div id="notaccordion">
									<h3 id="silver-partners">
										<div class="accordion-bar-wrapper">
											<a name="silver-partners" class="accordion-bar">Creating a <?php echo CONSTANT_UCWORDS;?> Automation pack</a>
											
										</div>
									</h3>
									
									<div class="inner-ul">
                                     
      <p>You can use workflow packs with OnCommand Workflow Automation (WFA) for your storage automation and integration requirements. You can create a pack when you want to share WFA content with the Storage Automation Store. <br>
        <strong>Before you begin</strong><br>
      You must have access to the WFA content that you want to export.
      </p>
      <p><br>
        <b>About this task</b>
      </p>
      <p>You must have access to the WFA content that you want to export. 
	</p>
	<ul >
        <li>You can create a pack by creating the pack information file and merging it with the WFA content that<br>
        is saved as a .dar file.</li>
        	<li>WFA entities to be shared can be exported as a .dar file. When you add the metadata (name,<br>
        version, author name, description) to the .dar file, it is called a pack. </li>
        </ul><br>
        <strong>Steps</strong>
       <ol> 
       <li> Log in to WFA through a web browser.</li>
		<li> Export the content to be shared as a .dar file.</li>
	<li>Create the pack information file:</li>

	<ul>
	<li> Navigate to the WFA_Install_base/bin/ directory.</li>
	<li> At the command prompt, enter the following command:
			<ul>
				<li>wfa-pack info create –output=pack_info_file</li>
				<li>pack_info_file is the pack information file.</li>
			</ul>
	</li>
	<li> Enter values for the following mandatory options in the pack information file:
			<ul>
				<li>Pack name</li>
				<li>Pack version, which is in the nnn.nnn.nnn format</li>
				<li>Pack author</li>
				<li>Pack description</li>
			</ul>
	</li>
	</ul>
	<li> <strong>Verify that the pack information file is created successfully:</strong>
	wfa-pack info show - info=pack_info_file</li>
	<li><strong>Create the pack:</strong>
	wfa-pack pack create -info=pack_info_file -dar=dar_file_path -
	output=new_pack_file_path</li>
	<li> <strong>Verify that the pack file is created successfully:</strong>
	wfa-pack pack show - pack=new_pack_file_path</li>
	  </ol>
	                                    
	</div>
										<h3 id="silver-partners">
										<div class="accordion-bar-wrapper">
											<a name="silver-partners" class="accordion-bar">Exporting <?php echo CONSTANT_UCWORDS;?> Automation content</a>
											
										</div>
									</h3>
									
									<div class="inner-ul">
                                     
      <p>You can save user-created OnCommand Workflow Automation (WFA) content as a .dar file and share the content with other users. The WFA content might include the entire user-created content or specific items such as workflows, finders, commands, and dictionary terms. <br>
        <strong>Before you begin</strong><br>
     You must have access to the WFA content that you want to export.<br>
     If content that is to be exported contains references to NetApp-certified content, the
	corresponding NetApp-certified content packs must be available on the system when the content
	is imported. These packs can be downloaded from the Storage Automation Store.

      </p>
      <p><br>
        <b>About this task</b>
      </p>
      <p>You cannot export the following types of certified content:
	</p>
	<ul >
        <li><img src="images/netapp-certified-icon.png"> &nbsp;&nbsp;NetApp certified content that is downloaded from the Storage Automation Store</li>
        	<li><img src="images/netapp-tested.png" align="absmiddle"> &nbsp;&nbsp;Content developed and tested by NetApp</li>
            <li><img src="images/sample.png" align="absmiddle"> &nbsp;&nbsp;Sample content</li>
            <li><img src="images/ps.png" align="absmiddle"> Content developed by Professional Services (PS), which is available only on custom
	installations made by PS
	</li>
	<li>When you export an object, the dependent objects for that object are also exported. For example, exporting a workflow also exports the dependent commands, filters, and finders for the workflow.
	</li>
	<li>You can export locked objects, and the objects remain in the locked state when imported.</li>
        </ul><br>
        <strong>Steps</strong>
       <ol> 
       <li> Log in to WFA through a web browser.</li>
		<li> Export the necessary content:</li> 
		
	</ol>
		<table border="0" cellpadding="0" cellspacing="0" align="left" class="support-table">
		<tr>
		<td class="supporttop supportleft"><strong>If you want to...</strong></td>
		<td class="supporttop"><strong>Do this...</strong></td>
		</tr>
		<tr><td class="supportleft">Export all user-created content as a single .dar file</td>
		<td>
                   a. Click <strong>Administration &gt; Export All.</strong><br />
                   b. In the Export As dialog box, specify a file name for the .dar file, and then click <strong>Export All.</strong>
        		</td>
					</tr>
		<tr>
		<td class="supportleft">Export specific content</td>
		<td>a. Navigate to the required window from where you want to export content.<br />
            b. Select one or more content from the window and click   <br />
            c. In the Export As dialog box, specify a file name for the .dar file, and then click <strong>Export.</strong>
         </td>
		</tr>
		</table>
		
</div>
</div>
</div>
<?php
// site head js include here 
include('includes/footer.php');
?>  
</body>
</html>