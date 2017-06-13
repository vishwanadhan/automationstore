<div id="headerContent">

    <!-- GSA Query Suggestions Resources -->

    <!-- Start: Header -->
    <div class="saheader">
        <div class="sahrdContent">
            <div class="safloatleft">
                <a href=<?php echo SITEPATH; ?>><img src="images/resources/trans.png" class="salogo" alt="NetApp" title="NetApp"></a>
            </div>

            <!--   start -->
            <div class="safloatleft saht80">
                <div class="safloatleft satxtSupport" style="color:white; font-size:3em;">WFS</div> 
                <div class="safloatleft">
                    <div id="searchBox" >
                        <form action="search-packs.shtml" id="searchSolr" method="post">

                            <div class="sasrchBox">
                                <input type="text" onfocus="if (this.value == 'Enter Search Text...') {
                                            this.value = '';
                                        }" onblur="if (this.value == '') {
                                                    this.value = 'Enter Search Text...';
                                                }" autocomplete="off" maxlength="150" value="Enter Search Text..." name="search" class="safloatleft" style="color: black;">
                                <a class="searchCloseIcon">
                                    <img title="Clear" alt=" " src="images/resources/close-icon.png" style="display: none;" id="close_icon">
                                </a>
                                <div onclick="document.getElementById('searchSolr').submit();" class="sasrchBtn safloatright"></div>
                            </div>

                        </form>
                    </div>	
                </div>

            </div>
            <!--   end -->
            
            <div id="main">				


                <div class="safloatright sauserProfile">
                    <div class="sabgLeft safloatleft"></div>
                    <div class="sabgMid safloatleft">

                        <?php
                        $current_page = basename($_SERVER['SCRIPT_FILENAME']);
                        $headerArray = array();
                        $headerArray = getallheaders();
                        $loginObj->checkHeader();

                        if (strtolower($_SESSION['uid']) != 'oblixanonymous' && !empty($_SESSION['uid'])) {
                            echo '<div class="sabgMid safloatleft">
                    					 	<span style="cursor:pointer" class="sashowDd">
                            		        	<span class="nameToAppend sausername sabold saFont11" id="nameToAppend">' . ucfirst($_SESSION["firstName"]) . '_' . ucfirst($_SESSION["lastName"]) . '</span>                            			 	
                            				<img src="images/resources/trans.png" class="sasepIcon">
                        					</span>
                        					<span class="sabold"> <a href="/logout.shtml" id="logOutLink" style="color:#FFFFFF;" class="saa saFont11">Sign Out</a></span>
 							</div>';

                           
                        } else {
                            session_destroy();
                            echo "<span class='sabold'> <a href='/login.shtml' id='logInLink' style='color:#FFFFFF;' class='saa saFont11'>Login</a></span>";
                        }
                        ?>

                    </div>
                    <div class="sabgRight safloatleft"></div>
                    <div class="saclear"></div>					
                </div>

                <div style="saclear:right"></div>

                <div class="oe_wrapper">

                    <ul id="oe_menu" class="oe_menu">
                        <li><a href="" style="background-image: url('images/resources/notifIcon.png');"></a>
                            <div style="left:0px;">
                                <ul>
                                    <li class="oe_heading">iNotifications</li>
                                    <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/portal?_nfpb=true&amp;_pageLabel=alertsummary_page">Support Alerts</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="https://kb.netapp.com/support/index?page=content&amp;channel=SUPPORT_BULLETINS&amp;access=s">Support Bulletins</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/info/communications/">Product Communiques</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/info/headlines/index.html">Headline Archives</a></li>

                                </ul>

                            </div>
                        </li>
                        <li><a href="" style="background-image: url('images/resources/commIco.png');" ></a>
                            <div style="left:-49px;">
                                <ul>
                                    <li class="oe_heading">Communities</li>
                                    <li><img src="images/arrow.jpg" /><a href="http://www.netapp.com/us/communities/communities-blogs.aspx">Explore our Blogs</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="https://communities.netapp.com/welcome">Community Forums</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="https://communities.netapp.com/community/tech-ontap">Tech OnTap</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="https://forums.netapp.com/index.jspa">Ask Support Community</a></li>

                                </ul>						
                            </div>
                        </li>
                        <li><a href="" style="background-image: url('images/resources/kbIco.png');"></a>
                            <div style="left:-98px;"><!-- -112px -->
                                <ul>
                                    <li class="oe_heading">Knowledge Base </li>
                                    <li><img src="images/arrow.jpg" /><a href="https://kb.netapp.com/support/index?page=home&amp;access=s">KB Articles</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="https://kb.netapp.com/support/index?page=content&amp;cat=TRIAGE&amp;channel=HOW_TO&amp;access=s">Technical Triage Templates</a></li>

                                </ul>

                            </div>
                        </li>
                        <li><a href="" style="background-image: url('images/resources/trainingIco.png');"></a>
                            <div style="left:-147px;">
                                <ul class="oe_full">
                                    <li class="oe_heading">Services &amp; Training </li>
                                    <li><img src="images/arrow.jpg" /><a href="http://www.netapp.com/us/services-support/university/index.aspx">Training</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="http://www.netapp.com/us/services-support/university/certification.aspx">Certification</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="http://www.netapp.com/us/services-support/professional/index.aspx">Services Marketing</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/documentation/web/ECMP1306130.html">Services Technical Collateral</a></li>
                                    <ul class="saFont11">
                                        <li><img src="images/arrow.jpg" /><a href="#">Support Services</a></li>
                                        <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/NOW/products/support/" class="samarginLt10">Support Offerings</a></li>
                                        <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/info/web/ECMP1132185.html" class="samarginLt10">Support Policies</a></li>
                                        <li><img src="images/arrow.jpg" /><a href="https://learningcenter.netapp.com" class="samarginLt10">Learning Center</a></li>
                                    </ul>
                                </ul>
                            </div>
                        </li>

                        <li><a href="" style="background-image: url('images/resources/ContactIco.png');"> </a>
                            <div style="left:-195px;">
                                <ul>
                                    <li class="oe_heading">Contact Us</li>
                                    <li><img src="images/arrow.jpg" /><a href="http://www.netapp.com/us/services-support/ngs-contacts.aspx">Contact Us</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/eservice/assistant">Report an Issue</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/eservice/assistant">Provide Feedback</a></li>
                                    <li><img src="images/arrow.jpg" /><a href="http://support.netapp.com/NOW/misc/survey.shtml">Take Survey</a></li>
                                </ul>						
                            </div>
                        </li>
                    </ul>		
                </div>	

            </div>



            <div style="clear:right"></div>
            <div class="sautilityPanel safloatright">

            </div>
            <div class="safloatleft sahdrNav">
                <? 	
                include_once('sidemenu.php');
                ?>	

            </div>

            <div class="saclear"></div>
        </div>
    </div>

    <!-- breadcum -->

    <div class="breadcrum" style="clear:both;">
        <?php
        $breadcrumb = new BreadCrum;
        $breadcrumb->homepage = 'Store Packs'; // sets the home directory name
        $breadcrumb->dirformat = 'ucfirst'; // Show the directory in this style
        $breadcrumb->symbol = ' &nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp; '; // set the separator between directories 
        $breadcrumb->showfile = TRUE; // shows the file name in the path
        $breadcrumb->special = ''; // special directory formatting elmer
        $breadcrumb->changeName = array('dirname1' => 'Directory Name 1',
            'dirname2' => 'Directory Name 2',
            'dirname3' => 'Directory Name 3',
            'dirname4' => 'Directory Name 4');
        
        $breadcrumb->fileName = 'pack-list.shtml';
       
        $filebroken = explode('.', $_SERVER['PHP_SELF']);

        $extension = array_pop($filebroken);
        $filename = implode('.', $filebroken);

        if (basename($filename) == 'pack-list') {
            $breadcrumb->changeFileName = array($_SERVER['PHP_SELF'] => basename($filename),
                '/pack-list' => 'Store Packs');
        } else if (basename($filename) == 'pack-upload') {
            $breadcrumb->changeFileName = array($_SERVER['PHP_SELF'] => 'Upload Pack',
                '/pack-list' => 'Store Packs');
        } else if (basename($filename) == 'pack-detail') {
            $breadcrumb->changeFileName = array($_SERVER['PHP_SELF'] => 'Pack Details',
                '/pack-list' => 'Store Packs');
        } else if (basename($filename) == 'search-packs') {
            $breadcrumb->changeFileName = array($_SERVER['PHP_SELF'] => 'Search Results',
                '/pack-list' => 'Store Packs');
        } else if (basename($filename) == 'download-page') {
            $breadcrumb->changeFileName = array($_SERVER['PHP_SELF'] => 'Download Pack',
                '/pack-list' => 'Store Packs');
        } else {
            $breadcrumb->changeFileName = array($_SERVER['PHP_SELF'] => basename($filename),
                '/pack-list' => 'Store Packs');
        }

        $breadcrumb->fileExists = array('index.shtml', 'index.php', 'default.htm');
        $breadcrumb->cssClass = 'crumb1'; // css class to use
        $breadcrumb->target = '_top'; // frame target
        $breadcrumb->linkFile = TRUE; // Link the file to itself
        $breadcrumb->_toSpace = TRUE; // converts underscores to spaces 
        echo "<p>" . $breadcrumb->show_breadcrumb() . "</p>";
        ?>
    </div>
    <!-- breadcum end -->

</div>





