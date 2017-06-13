<div id="menu" style="display:inline-block;">
        
			<ul class="sahdrTabs">
				     <li class="satabPublic" id="download">
					<a title="Home" href="home.shtml" <?php $class=(($current_page == "NetAppConfidentialityAgreement.php" ||$current_page == "home.php" || $current_page == "how-to-use-packs.php" || $current_page == "how-to-create-a-pack.php"|| $current_page == "search-packs.php" || $current_page == "support-faq.php" ) ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62">Home</a>
				</li>      
				
				<li class="satabPublic" id="download">
					<a title="Workflows" href="pack-list.shtml" <?php $class=(($current_page == "pack-listNC.php" || $current_page == "pack-list.php" || $current_page == "pack-detail.php" || ($current_page == "edit-caution.php" && (empty($_GET['type']))) || $current_page == "workflowHelp.php" || ($current_page == "download-page.php" && $_GET['packType'] == "workflow")) ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62">Workflows</a>
				</li>   
				<li class="satabPublic" id="documentation">
					<a title="Reports" href="reports.shtml" <?php $class=(($current_page == "reports.php" || $current_page == "reports-detail.php" || ($current_page == "download-page.php" && $_GET['packType'] == "report")) ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62">Reports</a>
				</li>
				<li class="satabPublic" id="documentation">
					<a title="Performance" href="performance.shtml" <?php $class=(($current_page == "performance.php" || $current_page == "performance-detail.php" || ($current_page == "download-page.php" && $_GET['packType'] == "performance")) ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62">Performance</a>
				</li>
				
				<li class="satabPublic" id="onCommandInsight">
					<a title="onCommandInsight" href="onCommandInsight.shtml" <?php $class=(($current_page == "onCommandInsight.php" || $current_page == "ocipack-detail.php" ||  $current_page == "how-to-create-oci-pack.php" || ($current_page == "download-page.php" && $_GET['packType'] == "ocipack")) ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62">OnCommand Insight</a>
				</li>
				<li class="satabPublic" id="SnapCenter">
					<a title="Data Protection" href="snap-list.shtml" <?php $class=(($current_page == "snap-list.php" || ($current_page == "edit-caution.php" && $_GET['type'] == "snapcenter") || $current_page == "snappack-listNC.php" ||  $current_page == "snap-upload.php" ||  $current_page == "snap-detail.php" ||  $current_page == "how-to-create-a-snap-pack.php" ||  $current_page == "snapcenterHelp.php" || ($current_page == "download-page.php" && $_GET['packType'] == "snapcenter")) ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62"><?php echo CONSTANT_UCWORDS;?></a>
				</li>
				
				<?php
				$userType = $loginObj->fetchUserType();


				if(!empty($_SESSION['uid']) && $userType == 1)
				{
			
			?>
				<li class="satabPublic" id="documentation">
					<a title="Admin Profile" href="admin_profile.shtml" <?php $class=(($current_page == "reportReports.php" || $current_page == "performanceReports.php" || $current_page == "report.php" || $current_page == "admin_profile.php" || $current_page == "ocireport.php"  || $current_page == "ociPackApproval.php" || $current_page == "flaggedData.php" || $current_page == "sendMail.php" || $current_page == "userSettings.php") ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62">Profile</a>
				</li>
				
				<li class="satabPublic" id="documentation">
					<a title="Alert" href="alert.shtml" <?php $class=(($current_page == "alert.php") ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62">Alert</a>   
				</li>
				
				
			<?php
				}
				else if(!empty($_SESSION['uid']) && $userType != 1)
				{
					
			?>
				<li class="satabPublic" id="documentation">
					<a title="User Profile" href="user_profile.shtml" <?php $class=(($current_page == "user_profile.php" || $current_page == "user_profile.php"  || $current_page == "user_ociprofile.php" || $current_page == "flaggedData.php" || $current_page == "userSettings.php" || $current_page == "user_snapprofile.php" ) ? " active":" ") ?> class="satabLinkPublic<?php echo $class;?>" id="62">Profile</a>
				</li>
			<?php
				}
				else
				{
				
			?>
					<li id="blankLi">
					<a title="" href="javscript:void(null);" class="satabLinkPublic" id="blankAnchor"></a>
					</li>
			<?php
				}
			?>
			</ul>	
      

</div>

					