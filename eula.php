<?php
/**
 * eula.php
 * Page specifying the End User License Agreement for end user before downloading.
 * 
 */

require_once('config/configure.php');
require_once('includes/function/autoload.php');

/*  $rootPath = $docRoot;
 //echo $packType;
$newPath = $rootPath . (base64_decode($packPath));
//echo $newPath; 
$packPath = $_REQUEST['packPath']; */
$packType = $_REQUEST['packType'];
$certi =$_REQUEST['certi'];


if (!isset($_REQUEST['searchText'])) {
    $_REQUEST['searchText'] = NULL;
}
$searchText = $_REQUEST['searchText'];

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="x-ua-compatible" content="IE=10" >
        <link rel="stylesheet" type="text/css" href="css/style1.css">
        <link rel="stylesheet" type="text/css" href="css/eula.css" />
        <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
        <script type="text/javascript">

            $(function()
            {
                var searchTxt = null;
                var submitActor = null;
                var $form = $('#eulaForm');
                var $submitActors = $form.find('input[type=submit]');

                $form.submit(function(event)
                {
                    if (null === submitActor)
                    {
                        // If no actor is explicitly clicked, the browser will
                        // automatically choose the first in source-order
                        // so we do the same here
                        submitActor = $submitActors[0];
                    }

                    if (submitActor.name == 'declineBtn')
                    {
                        searchTxt = $('#searchText').val();

                        if (searchTxt.length != 0)
                        {
                            $('#eulaForm').prop('action', 'search-packs.php');
                            $('#eulaForm').submit();
                        }
                        else {
                            //url = '<?php echo basename($_SERVER['HTTP_REFERER']); ?>';
<?php if ($packType == 'report') { ?>
                                url = 'reports.shtml';
<?php } else if ($packType == 'performance') { ?>
                                url = 'performance.shtml';
<?php } else if ($packType == 'workflow') { ?>
                                url = 'pack-list.shtml';
<?php } else if ($packType == 'snapcenter') { ?>
                                url = 'snap-list.shtml';   
<?php } else if ($packType == 'ocipack') { ?>
                                url = '<?php echo basename($_SERVER['HTTP_REFERER']); ?>';
<?php } else { ?> url = 'home.shtml';
<?php } ?>
                            window.parent.location.href = url;
                            return false;
                        }
                    }
                    if (submitActor.name == 'acceptBtn')
                    {
                        $('#eulaForm').submit();
                    }

                    return false;
                });

                $submitActors.click(function(event)
                {
                    submitActor = this;
                });

            });


        </script>
        <!--[if IE 5]>
                <style>
                        #body_content {
            min-height: 319px;
            position: relative;
            clear: both;
            padding-top: 35px;
            padding-left: 20px;
            padding-right: 20px;
           margin-left:30px;
            width:auto;
            font-size: 9pt;
        }
        
                </style>
        <![endif]-->
    </head>

    <body  id="body_content" bgcolor="#FFFFFF" >
        <?php if ($packType == 'report') { ?>
            <div id="reportEula" style="text-align: justify;">
                <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
                <p align="center"><font face="Arial" size="2"><br>
                    <strong>NETAPP CONTRIBUTOR AGREEMENT</strong></font></p><font face="Arial" size="2">
                <div id="body_content" class="eula-n">  


                    This Contributor Agreement ("CA") is between you ("Contributor," "You," "you," "Your," or "your") and NetApp, Inc. and (as applicable) its subsidiaries and affiliates ("NetApp").  This CA sets forth the terms under which You license your Contribution to NetApp.  
                    <br><br>
                    <strong>In this CA:</strong><br>

                    "Contribution" means any source code, object code, patch, tool, sample, graphic, specification, manual, documentation, or any other material posted or submitted by you to NetApp Storage Automation Store;

                    By clicking the "I ACCEPT" button when, uploading or providing Your Contribution to NetApp Storage Automation Store, You agree that this CA will exclusively govern NetApp's license of Your Contribution.  If You are accepting this CA on behalf of another person, company or other legal entity, whether as an employee, contractor, distributor, reseller, partner, agent or otherwise, You represent and warrant that You have full authority to bind them.  If You do not agree to the terms of this CA, do not upload, provide, or click the "I ACCEPT" button. 
                    <ol><li>
                            With respect to any worldwide copyrights, or copyright applications and registrations, in Your Contribution:
                            <ul><li>	You hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid up, royalty-free, unrestricted license to exercise all rights under those copyrights. This includes, at NetApp's option, the right to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements;</li>
                                <li>You agree that You will not assert any moral rights in your Contribution against us, our licensees or transferees; and</li>
                                <li>You agree that neither of us has any duty to consult with, obtain the consent of, pay or render an accounting to the other for any use or distribution of your Contribution.</li>
                            </ul>

                        <li> With respect to any patents you own, or that you can license without payment to any third party, you hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid-up, royalty-free license to:
                            <ul><li>
                                    make, have made, use, sell, offer to sell, import, export and otherwise dispose of your contribution in whole or in part, alone or in combination with or included in any product, work or materials arising out of the project to which your contribution was submitted, and
                                </li>
                                <li>At our option, to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements.</li>
                            </ul>
                        </li>



                        <li>  You acknowledge that each Contributor is free to develop competing technologies and standards, and that each party is free to license its patent rights to third parties, including for the purpose of enabling competing technologies and standards. </li>

                        <li> Solely for purposes of Section 365(n) of Title 11, United States Bankruptcy Code and any equivalent law in any foreign jurisdiction, we may continue to exercise the license rights granted to us hereunder, and reserve all rights in your contribution to protect our interests therein pursuant to Section 365(n) of the U.S. Bankruptcy Code, or equivalent legislation in other applicable jurisdictions.</li>

                        <li>Except as set out above, you keep all right, title, and interest in your Contribution.  The rights that you grant to NetApp under these terms are effective on the date you first submitted a Contribution to NetApp, even if your submission took place before the date you sign these terms. </li>

                        <li> With respect to your Contribution, you represent that:
                            <ul>
                                <li>	it is an original work and that you can legally grant the rights set out in these terms;</li>
                                <li>	it does not to the best of your knowledge violate any third party's copyrights, trademarks, patents, or other intellectual property rights; and</li>
                                <li>you are authorized to accept this contract on behalf of your company.</li>
                            </ul>

                        </li>

                        <li> Indemnification. You agree to indemnify, hold harmless and (if requested by NetApp) defend   NetApp, at your expense, against any and all third party claims, actions, proceedings and suits asserted against NetApp or any of its officers, directors, employees, agents or affiliates, and all related liabilities, damages, settlements, penalties, fines, costs or expenses (including, without limitation, reasonable attorneys' fees) incurred by NetApp or any of its officers, directors, employees, agents or affiliates, arising out of or relating to: (i) your breach of any term or condition of this Contribution Agreement, and (ii) your use, distribution or reposting of another party's Contribution(s) posted or submitted to NetApp Storage Automation Store. In such instances, NetApp will provide you with written notice of such third party claim, action, proceeding or suit to the last email and/or mailing address furnished to NetApp with a cc to your internal legal department (if applicable). Failure to provide such notice promptly shall not release you of these indemnity obligations except and only to the extent prejudiced by such delay. You shall cooperate as fully as reasonably required in the defense of any claim. NetApp reserves the right, at its own expense, to assume the exclusive defense and control of any matter subject to indemnification by you. 
                        </li>
                        <li>  Export. You shall provide information and assistance as may reasonably be required in connection with NetApp executing import, export, sales and trade programs, including but not limited to tariff schedules, export control classification numbers, information re: origin for qualification. Subsequent updates, if any, shall be made promptly to NetApp or as soon as possible following NetApp's written request.</li>



                        <li> These terms will be governed by the laws of the State of California and applicable U.S. Federal law.  Any choice of law rules will not apply.</li>

                </div>
            </div>
        <?php } else if ($packType == 'performance') {
            ?>
            <div id="prfrmncEula" style="text-align: justify;">
                <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
                <p align="center"><font face="Arial" size="2"><br>
                    <strong>NETAPP CONTRIBUTOR AGREEMENT</strong></font></p><font face="Arial" size="2">
                <div id="body_content" class="eula-n">  


                    This Contributor Agreement ("CA") is between you ("Contributor," "You," "you," "Your," or "your") and NetApp, Inc. and (as applicable) its subsidiaries and affiliates ("NetApp").  This CA sets forth the terms under which You license your Contribution to NetApp.  
                    <br><br>
                    <strong>In this CA:</strong><br>

                    "Contribution" means any source code, object code, patch, tool, sample, graphic, specification, manual, documentation, or any other material posted or submitted by you to NetApp Storage Automation Store;

                    By clicking the "I ACCEPT" button when, uploading or providing Your Contribution to NetApp Storage Automation Store, You agree that this CA will exclusively govern NetApp's license of Your Contribution.  If You are accepting this CA on behalf of another person, company or other legal entity, whether as an employee, contractor, distributor, reseller, partner, agent or otherwise, You represent and warrant that You have full authority to bind them.  If You do not agree to the terms of this CA, do not upload, provide, or click the "I ACCEPT" button. 
                    <ol><li>
                            With respect to any worldwide copyrights, or copyright applications and registrations, in Your Contribution:
                            <ul><li>	You hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid up, royalty-free, unrestricted license to exercise all rights under those copyrights. This includes, at NetApp's option, the right to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements;</li>
                                <li>You agree that You will not assert any moral rights in your Contribution against us, our licensees or transferees; and</li>
                                <li>You agree that neither of us has any duty to consult with, obtain the consent of, pay or render an accounting to the other for any use or distribution of your Contribution.</li>
                            </ul>

                        <li> With respect to any patents you own, or that you can license without payment to any third party, you hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid-up, royalty-free license to:
                            <ul><li>
                                    make, have made, use, sell, offer to sell, import, export and otherwise dispose of your contribution in whole or in part, alone or in combination with or included in any product, work or materials arising out of the project to which your contribution was submitted, and
                                </li>
                                <li>At our option, to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements.</li>
                            </ul>
                        </li>



                        <li>  You acknowledge that each Contributor is free to develop competing technologies and standards, and that each party is free to license its patent rights to third parties, including for the purpose of enabling competing technologies and standards. </li>

                        <li> Solely for purposes of Section 365(n) of Title 11, United States Bankruptcy Code and any equivalent law in any foreign jurisdiction, we may continue to exercise the license rights granted to us hereunder, and reserve all rights in your contribution to protect our interests therein pursuant to Section 365(n) of the U.S. Bankruptcy Code, or equivalent legislation in other applicable jurisdictions.</li>

                        <li>Except as set out above, you keep all right, title, and interest in your Contribution.  The rights that you grant to NetApp under these terms are effective on the date you first submitted a Contribution to NetApp, even if your submission took place before the date you sign these terms. </li>

                        <li> With respect to your Contribution, you represent that:
                            <ul>
                                <li>	it is an original work and that you can legally grant the rights set out in these terms;</li>
                                <li>	it does not to the best of your knowledge violate any third party's copyrights, trademarks, patents, or other intellectual property rights; and</li>
                                <li>you are authorized to accept this contract on behalf of your company.</li>
                            </ul>

                        </li>

                        <li> Indemnification. You agree to indemnify, hold harmless and (if requested by NetApp) defend   NetApp, at your expense, against any and all third party claims, actions, proceedings and suits asserted against NetApp or any of its officers, directors, employees, agents or affiliates, and all related liabilities, damages, settlements, penalties, fines, costs or expenses (including, without limitation, reasonable attorneys' fees) incurred by NetApp or any of its officers, directors, employees, agents or affiliates, arising out of or relating to: (i) your breach of any term or condition of this Contribution Agreement, and (ii) your use, distribution or reposting of another party's Contribution(s) posted or submitted to NetApp Storage Automation Store. In such instances, NetApp will provide you with written notice of such third party claim, action, proceeding or suit to the last email and/or mailing address furnished to NetApp with a cc to your internal legal department (if applicable). Failure to provide such notice promptly shall not release you of these indemnity obligations except and only to the extent prejudiced by such delay. You shall cooperate as fully as reasonably required in the defense of any claim. NetApp reserves the right, at its own expense, to assume the exclusive defense and control of any matter subject to indemnification by you. 
                        </li>
                        <li>  Export. You shall provide information and assistance as may reasonably be required in connection with NetApp executing import, export, sales and trade programs, including but not limited to tariff schedules, export control classification numbers, information re: origin for qualification. Subsequent updates, if any, shall be made promptly to NetApp or as soon as possible following NetApp's written request.</li>



                        <li> These terms will be governed by the laws of the State of California and applicable U.S. Federal law.  Any choice of law rules will not apply.</li>

                </div>
            </div>
        <?php
        } else if ($packType == 'workflow') {
            if ($certi == 'NETAPP') {
                ?>
                <div id="workflowEula" style="text-align: justify;">
                    <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
                    <p align="center"><font face="Arial" size="2"><br>
                        <strong>NETAPP CONTRIBUTOR AGREEMENT</strong></font></p><font face="Arial" size="2">


                    <div id="body_content" class="eula-n">  


                        This Contributor Agreement ("CA") is between you ("Contributor," "You," "you," "Your," or "your") and NetApp, Inc. and (as applicable) its subsidiaries and affiliates ("NetApp").  This CA sets forth the terms under which You license your Contribution to NetApp.  
                        <br><br>
                        <strong>In this CA:</strong><br>

                        "Contribution" means any source code, object code, patch, tool, sample, graphic, specification, manual, documentation, or any other material posted or submitted by you to NetApp Storage Automation Store;

                        By clicking the "I ACCEPT" button when, uploading or providing Your Contribution to NetApp Storage Automation Store, You agree that this CA will exclusively govern NetApp's license of Your Contribution.  If You are accepting this CA on behalf of another person, company or other legal entity, whether as an employee, contractor, distributor, reseller, partner, agent or otherwise, You represent and warrant that You have full authority to bind them.  If You do not agree to the terms of this CA, do not upload, provide, or click the "I ACCEPT" button. 
                        <ol><li>
                                With respect to any worldwide copyrights, or copyright applications and registrations, in Your Contribution:
                                <ul><li>	You hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid up, royalty-free, unrestricted license to exercise all rights under those copyrights. This includes, at NetApp's option, the right to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements;</li>
                                    <li>You agree that You will not assert any moral rights in your Contribution against us, our licensees or transferees; and</li>
                                    <li>You agree that neither of us has any duty to consult with, obtain the consent of, pay or render an accounting to the other for any use or distribution of your Contribution.</li>
                                </ul>

                            <li> With respect to any patents you own, or that you can license without payment to any third party, you hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid-up, royalty-free license to:
                                <ul><li>
                                        make, have made, use, sell, offer to sell, import, export and otherwise dispose of your contribution in whole or in part, alone or in combination with or included in any product, work or materials arising out of the project to which your contribution was submitted, and
                                    </li>
                                    <li>At our option, to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements.</li>
                                </ul>
                            </li>



                            <li>  You acknowledge that each Contributor is free to develop competing technologies and standards, and that each party is free to license its patent rights to third parties, including for the purpose of enabling competing technologies and standards. </li>

                            <li> Solely for purposes of Section 365(n) of Title 11, United States Bankruptcy Code and any equivalent law in any foreign jurisdiction, we may continue to exercise the license rights granted to us hereunder, and reserve all rights in your contribution to protect our interests therein pursuant to Section 365(n) of the U.S. Bankruptcy Code, or equivalent legislation in other applicable jurisdictions.</li>

                            <li>Except as set out above, you keep all right, title, and interest in your Contribution.  The rights that you grant to NetApp under these terms are effective on the date you first submitted a Contribution to NetApp, even if your submission took place before the date you sign these terms. </li>

                            <li> With respect to your Contribution, you represent that:
                                <ul>
                                    <li>	it is an original work and that you can legally grant the rights set out in these terms;</li>
                                    <li>	it does not to the best of your knowledge violate any third party's copyrights, trademarks, patents, or other intellectual property rights; and</li>
                                    <li>you are authorized to accept this contract on behalf of your company.</li>
                                </ul>

                            </li>

                            <li> Indemnification. You agree to indemnify, hold harmless and (if requested by NetApp) defend   NetApp, at your expense, against any and all third party claims, actions, proceedings and suits asserted against NetApp or any of its officers, directors, employees, agents or affiliates, and all related liabilities, damages, settlements, penalties, fines, costs or expenses (including, without limitation, reasonable attorneys' fees) incurred by NetApp or any of its officers, directors, employees, agents or affiliates, arising out of or relating to: (i) your breach of any term or condition of this Contribution Agreement, and (ii) your use, distribution or reposting of another party's Contribution(s) posted or submitted to NetApp Storage Automation Store. In such instances, NetApp will provide you with written notice of such third party claim, action, proceeding or suit to the last email and/or mailing address furnished to NetApp with a cc to your internal legal department (if applicable). Failure to provide such notice promptly shall not release you of these indemnity obligations except and only to the extent prejudiced by such delay. You shall cooperate as fully as reasonably required in the defense of any claim. NetApp reserves the right, at its own expense, to assume the exclusive defense and control of any matter subject to indemnification by you. 
                            </li>
                            <li>  Export. You shall provide information and assistance as may reasonably be required in connection with NetApp executing import, export, sales and trade programs, including but not limited to tariff schedules, export control classification numbers, information re: origin for qualification. Subsequent updates, if any, shall be made promptly to NetApp or as soon as possible following NetApp's written request.</li>



                            <li> These terms will be governed by the laws of the State of California and applicable U.S. Federal law.  Any choice of law rules will not apply.</li>

                    </div>
                </div>

    <?php } else if ($certi == 'NONE') { ?> <div id="workflowEula">
                    <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
                    <p align="center"><font face="Arial" size="2"><br>
                        <strong>CONTENT LICENSE AGREEMENT </strong></font></p><font face="Arial" size="2">
                    <div id="body_content" class="eula-n">  			
                        <ol>		
                            <li> Scope. This content license agreement ("CLA") is between you ("You" or "Your") and NetApp, Inc. and (as applicable) its subsidiaries and affiliates ("NetApp").  This CLA sets forth the terms under which NetApp licenses the Content to You.  
                                In this CLA:<br><br>
                                a)	"Content" means Reportdesign file available in the NetApp Storage Automation Store for use with the Software;<br><br>
                                b)	 "Software" means NetApp software in object code format including (as applicable) operating systems, protocols, backup and recovery, disaster recovery, storage efficiency and management software.
                            <li>
                                Acceptance. By clicking the "I ACCEPT" button when, accessing or using the Content, You agree that this CLA will exclusively govern NetApp's license and Your use of the Content..  If You are accepting this CLA on behalf of another person, company or other legal entity, whether as an employee, contractor, distributor, reseller, partner, agent or otherwise, You represent and warrant that You have full authority to bind them.  If You do not agree to the terms of this CLA, do not download, install, copy, access, or use the Content or click the "I ACCEPT" button, and promptly return the Content to the party from whom it was obtained.</li>
                            <li> License Grant. NetApp grants to You a personal, non-exclusive, non-transferrable, worldwide, limited and revocable license, without the right to sublicense, to install and use the Content for Your internal business purposes only.</li>
                            <li>License Restrictions. You shall not, nor shall You allow any third party to: 
                                a)	reverse-engineer, decompile or disassemble the Content or otherwise reduce it to human-readable format except to the extent required for interoperability purposes under applicable laws or as expressly permitted in open-source licenses; <br><br>
                                b)	remove or conceal any product identification, proprietary, intellectual property or other notices in the Content<br><br>
                                c)	use the Content to perform services for third parties in a service bureau, managed services, commercial hosting services or similar environment; <br><br>
                                d)	publish or provide any Content benchmark or comparison test results.
                            </li>
                            <li> Intellectual Property Rights. The Content is licensed, not sold, to You.  It is protected by intellectual property laws and treaties worldwide, and contains trade secrets, in which NetApp and its licensors reserve and retain all rights not expressly granted to You.  No right, title or interest to any trademark, service mark, logo or trade name, of NetApp or its licensors is granted to You. </li> 
                            <li>Disclaimer of Warranty. In consideration for the Content being provided without charge, You agree that the Content is provided "AS IS" and without warranty of any kind. All express or implied conditions, representations, and warranties, including without limitation any implied warranty of merchantability, fitness for a particular purpose, or non-infringement, are disclaimed, except to the extent that such disclaimers are held to be legally invalid by applicable law. </li>
                            <li> Limitation of Liability. In no event will NetApp be liable for any direct, indirect, incidental, consequential or special damages of any kind or for loss or corruption of data, or loss of revenue or financial loss arising out of or related to this agreement or the use of the Content even if NetApp has been advised of or otherwise has reason to know of the possibility of such damages. Further liability for such damage will be excluded, even if the exclusive remedies provided for in this Agreement fail of their essential purpose.  The foregoing sets forth NetApp's entire liability and Your entire remedies available under this Agreement. The limitations and exclusions above shall not apply to liability for death or personal injury caused by negligence, gross negligence, willful misconduct, fraud or any other liability that cannot be excluded under applicable laws.</li>
                            <li> Termination. This CLA is effective until expiration or termination.  You may terminate the CLA at any time on written notice to NetApp.  NetApp may terminate the CLA immediately on written notice for material breach of the CLA. CLAUpon expiration or termination of this CLA, You will promptly return or destroy all copies of the Content.  Sections 1, 4, 5, 6, 9, 10, 11, and 12 shall survive expiration or termination of this CLA. </li>
                            <li>Software Copyright Information And Notices. Software copyright information and other related details are included as part of notices in the Documentation or other documentation published by NetApp (e.g. NOTICES.TXT or NOTICES. PDF).</li>
                            <li> Export Controls. You acknowledge that the Content supplied by NetApp under this CLA is subject to export controls under the laws and regulations of the United States, the European Union and other countries as applicable, and the Content may include export controlled technologies, including without limitation encryption technology.  You agree to comply with such laws and regulations and, in particular, represent and warrant that You: <br><br>
                                a)	shall not, unless authorized by U.S. export licenses or other government authorizations, directly or indirectly export or re-export the Software and Documentation to or use the Content in countries subject to U.S. embargoes or trade sanctions programs;<br><br>
                                b)	are not a party, nor will You export or re-export to a party, identified on any government export exclusion lists, including but not limited to the U.S. Denied Persons, Entity, and Specially Designated Nationals Lists; and <br><br>
                                c)	will not use the Content for any purposes prohibited by U.S. law, including but without limitation, the development, design, manufacture or production of nuclear, missile, chemical biological weaponry or other weapons of mass destruction.  <br><br>

                                You agree to provide NetApp end use and end user information upon NetApp's request.  You shall obtain all required authorizations, permits, or licenses to export, re-export or import, as required.  You agree to obligate, by contract or other similar assurances, the parties to whom You re-export or otherwise transfer the Software to comply with all obligations set forth in this Section 
                            </li> 
                            <li>U.S. Federal Government End Users. This Section 11 applies to You only if You are a U.S. Federal Government end user.  The Content is "commercial" computer software and documentation and is licensed to You in accordance with the rights articulated in applicable U.S. government acquisition regulations (e.g. FAR, DFARs) pertaining to commercial computer software and documentation..  Any dispute between You and NetApp will be subject to resolution pursuant to the Contract Disputes Act of 1978.  Nothing contained in this CLA is meant to derogate the rights of the U.S. Department of Justice as identified in 28 U.S.C. <CODE>516</CODE>.  All other provisions of this CLA remain in effect as written.</li>
                            <li> General. This CLA will be construed pursuant to the laws of i) the State of California, United States, excluding its conflicts of law provisions, if You are located in the United States or in a country in which NetApp has no local sales subsidiary, or ii) the country in which You are located if NetApp has a local sales subsidiary in that country.  NetApp reserves the right to control all aspects of any lawsuit or claim that arises from Your use of the Content.  If required by NetApp's agreement with a third party licensor, NetApp's licensor will be a direct and intended third party beneficiary of this CLA and may enforce it directly against You.  NetApp does not waive any of its rights under this CLA by failing to or delaying the exercise of its rights or partially exercising its rights at any time.  To the extent that any Section of this CLA, or part thereof, is determined to be invalid or unenforceable, the remainder of this CLA shall remain in full force and effect.  This CLA may not be changed except by a written amendment executed by an authorized representative of each party.  In the event of a dispute between the English and non-English version of the CLA (where translated for local requirements), the English version of this CLA shall govern, to the extent permitted by applicable laws.  This CLA represents the entire agreement and understanding between NetApp and You with respect to the Content.  It supersedes any previous communications, representations or agreements between NetApp and You and prevails over any conflicting or additional terms in any quote, purchase order, acknowledgment, or similar communication between the parties.</li></ol>



                    </div>

                </div></div> <?php
        } else {
            header('Location: home.shtml');
        }
    } else if ($packType == 'snapcenter') {
            if ($certi == 'NETAPP') {
                ?>
                <div id="workflowEula" style="text-align: justify;">
                    <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
                    <p align="center"><font face="Arial" size="2"><br>
                        <strong>NETAPP CONTRIBUTOR AGREEMENT</strong></font></p><font face="Arial" size="2">


                    <div id="body_content" class="eula-n">  


                        This Contributor Agreement ("CA") is between you ("Contributor," "You," "you," "Your," or "your") and NetApp, Inc. and (as applicable) its subsidiaries and affiliates ("NetApp").  This CA sets forth the terms under which You license your Contribution to NetApp.  
                        <br><br>
                        <strong>In this CA:</strong><br>

                        "Contribution" means any source code, object code, patch, tool, sample, graphic, specification, manual, documentation, or any other material posted or submitted by you to NetApp Storage Automation Store;

                        By clicking the "I ACCEPT" button when, uploading or providing Your Contribution to NetApp Storage Automation Store, You agree that this CA will exclusively govern NetApp's license of Your Contribution.  If You are accepting this CA on behalf of another person, company or other legal entity, whether as an employee, contractor, distributor, reseller, partner, agent or otherwise, You represent and warrant that You have full authority to bind them.  If You do not agree to the terms of this CA, do not upload, provide, or click the "I ACCEPT" button. 
                        <ol><li>
                                With respect to any worldwide copyrights, or copyright applications and registrations, in Your Contribution:
                                <ul><li>    You hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid up, royalty-free, unrestricted license to exercise all rights under those copyrights. This includes, at NetApp's option, the right to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements;</li>
                                    <li>You agree that You will not assert any moral rights in your Contribution against us, our licensees or transferees; and</li>
                                    <li>You agree that neither of us has any duty to consult with, obtain the consent of, pay or render an accounting to the other for any use or distribution of your Contribution.</li>
                                </ul>

                            <li> With respect to any patents you own, or that you can license without payment to any third party, you hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid-up, royalty-free license to:
                                <ul><li>
                                        make, have made, use, sell, offer to sell, import, export and otherwise dispose of your contribution in whole or in part, alone or in combination with or included in any product, work or materials arising out of the project to which your contribution was submitted, and
                                    </li>
                                    <li>At our option, to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements.</li>
                                </ul>
                            </li>



                            <li>  You acknowledge that each Contributor is free to develop competing technologies and standards, and that each party is free to license its patent rights to third parties, including for the purpose of enabling competing technologies and standards. </li>

                            <li> Solely for purposes of Section 365(n) of Title 11, United States Bankruptcy Code and any equivalent law in any foreign jurisdiction, we may continue to exercise the license rights granted to us hereunder, and reserve all rights in your contribution to protect our interests therein pursuant to Section 365(n) of the U.S. Bankruptcy Code, or equivalent legislation in other applicable jurisdictions.</li>

                            <li>Except as set out above, you keep all right, title, and interest in your Contribution.  The rights that you grant to NetApp under these terms are effective on the date you first submitted a Contribution to NetApp, even if your submission took place before the date you sign these terms. </li>

                            <li> With respect to your Contribution, you represent that:
                                <ul>
                                    <li>    it is an original work and that you can legally grant the rights set out in these terms;</li>
                                    <li>    it does not to the best of your knowledge violate any third party's copyrights, trademarks, patents, or other intellectual property rights; and</li>
                                    <li>you are authorized to accept this contract on behalf of your company.</li>
                                </ul>

                            </li>

                            <li> Indemnification. You agree to indemnify, hold harmless and (if requested by NetApp) defend   NetApp, at your expense, against any and all third party claims, actions, proceedings and suits asserted against NetApp or any of its officers, directors, employees, agents or affiliates, and all related liabilities, damages, settlements, penalties, fines, costs or expenses (including, without limitation, reasonable attorneys' fees) incurred by NetApp or any of its officers, directors, employees, agents or affiliates, arising out of or relating to: (i) your breach of any term or condition of this Contribution Agreement, and (ii) your use, distribution or reposting of another party's Contribution(s) posted or submitted to NetApp Storage Automation Store. In such instances, NetApp will provide you with written notice of such third party claim, action, proceeding or suit to the last email and/or mailing address furnished to NetApp with a cc to your internal legal department (if applicable). Failure to provide such notice promptly shall not release you of these indemnity obligations except and only to the extent prejudiced by such delay. You shall cooperate as fully as reasonably required in the defense of any claim. NetApp reserves the right, at its own expense, to assume the exclusive defense and control of any matter subject to indemnification by you. 
                            </li>
                            <li>  Export. You shall provide information and assistance as may reasonably be required in connection with NetApp executing import, export, sales and trade programs, including but not limited to tariff schedules, export control classification numbers, information re: origin for qualification. Subsequent updates, if any, shall be made promptly to NetApp or as soon as possible following NetApp's written request.</li>



                            <li> These terms will be governed by the laws of the State of California and applicable U.S. Federal law.  Any choice of law rules will not apply.</li>

                    </div>
                </div>

    <?php } else if ($certi == 'NONE') { ?> <div id="workflowEula">
                    <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
                    <p align="center"><font face="Arial" size="2"><br>
                        <strong>CONTENT LICENSE AGREEMENT </strong></font></p><font face="Arial" size="2">
                    <div id="body_content" class="eula-n">              
                        <ol>        
                            <li> Scope. This content license agreement ("CLA") is between you ("You" or "Your") and NetApp, Inc. and (as applicable) its subsidiaries and affiliates ("NetApp").  This CLA sets forth the terms under which NetApp licenses the Content to You.  
                                In this CLA:<br><br>
                                a)  "Content" means Reportdesign file available in the NetApp Storage Automation Store for use with the Software;<br><br>
                                b)   "Software" means NetApp software in object code format including (as applicable) operating systems, protocols, backup and recovery, disaster recovery, storage efficiency and management software.
                            <li>
                                Acceptance. By clicking the "I ACCEPT" button when, accessing or using the Content, You agree that this CLA will exclusively govern NetApp's license and Your use of the Content..  If You are accepting this CLA on behalf of another person, company or other legal entity, whether as an employee, contractor, distributor, reseller, partner, agent or otherwise, You represent and warrant that You have full authority to bind them.  If You do not agree to the terms of this CLA, do not download, install, copy, access, or use the Content or click the "I ACCEPT" button, and promptly return the Content to the party from whom it was obtained.</li>
                            <li> License Grant. NetApp grants to You a personal, non-exclusive, non-transferrable, worldwide, limited and revocable license, without the right to sublicense, to install and use the Content for Your internal business purposes only.</li>
                            <li>License Restrictions. You shall not, nor shall You allow any third party to: 
                                a)  reverse-engineer, decompile or disassemble the Content or otherwise reduce it to human-readable format except to the extent required for interoperability purposes under applicable laws or as expressly permitted in open-source licenses; <br><br>
                                b)  remove or conceal any product identification, proprietary, intellectual property or other notices in the Content<br><br>
                                c)  use the Content to perform services for third parties in a service bureau, managed services, commercial hosting services or similar environment; <br><br>
                                d)  publish or provide any Content benchmark or comparison test results.
                            </li>
                            <li> Intellectual Property Rights. The Content is licensed, not sold, to You.  It is protected by intellectual property laws and treaties worldwide, and contains trade secrets, in which NetApp and its licensors reserve and retain all rights not expressly granted to You.  No right, title or interest to any trademark, service mark, logo or trade name, of NetApp or its licensors is granted to You. </li> 
                            <li>Disclaimer of Warranty. In consideration for the Content being provided without charge, You agree that the Content is provided "AS IS" and without warranty of any kind. All express or implied conditions, representations, and warranties, including without limitation any implied warranty of merchantability, fitness for a particular purpose, or non-infringement, are disclaimed, except to the extent that such disclaimers are held to be legally invalid by applicable law. </li>
                            <li> Limitation of Liability. In no event will NetApp be liable for any direct, indirect, incidental, consequential or special damages of any kind or for loss or corruption of data, or loss of revenue or financial loss arising out of or related to this agreement or the use of the Content even if NetApp has been advised of or otherwise has reason to know of the possibility of such damages. Further liability for such damage will be excluded, even if the exclusive remedies provided for in this Agreement fail of their essential purpose.  The foregoing sets forth NetApp's entire liability and Your entire remedies available under this Agreement. The limitations and exclusions above shall not apply to liability for death or personal injury caused by negligence, gross negligence, willful misconduct, fraud or any other liability that cannot be excluded under applicable laws.</li>
                            <li> Termination. This CLA is effective until expiration or termination.  You may terminate the CLA at any time on written notice to NetApp.  NetApp may terminate the CLA immediately on written notice for material breach of the CLA. CLAUpon expiration or termination of this CLA, You will promptly return or destroy all copies of the Content.  Sections 1, 4, 5, 6, 9, 10, 11, and 12 shall survive expiration or termination of this CLA. </li>
                            <li>Software Copyright Information And Notices. Software copyright information and other related details are included as part of notices in the Documentation or other documentation published by NetApp (e.g. NOTICES.TXT or NOTICES. PDF).</li>
                            <li> Export Controls. You acknowledge that the Content supplied by NetApp under this CLA is subject to export controls under the laws and regulations of the United States, the European Union and other countries as applicable, and the Content may include export controlled technologies, including without limitation encryption technology.  You agree to comply with such laws and regulations and, in particular, represent and warrant that You: <br><br>
                                a)  shall not, unless authorized by U.S. export licenses or other government authorizations, directly or indirectly export or re-export the Software and Documentation to or use the Content in countries subject to U.S. embargoes or trade sanctions programs;<br><br>
                                b)  are not a party, nor will You export or re-export to a party, identified on any government export exclusion lists, including but not limited to the U.S. Denied Persons, Entity, and Specially Designated Nationals Lists; and <br><br>
                                c)  will not use the Content for any purposes prohibited by U.S. law, including but without limitation, the development, design, manufacture or production of nuclear, missile, chemical biological weaponry or other weapons of mass destruction.  <br><br>

                                You agree to provide NetApp end use and end user information upon NetApp's request.  You shall obtain all required authorizations, permits, or licenses to export, re-export or import, as required.  You agree to obligate, by contract or other similar assurances, the parties to whom You re-export or otherwise transfer the Software to comply with all obligations set forth in this Section 
                            </li> 
                            <li>U.S. Federal Government End Users. This Section 11 applies to You only if You are a U.S. Federal Government end user.  The Content is "commercial" computer software and documentation and is licensed to You in accordance with the rights articulated in applicable U.S. government acquisition regulations (e.g. FAR, DFARs) pertaining to commercial computer software and documentation..  Any dispute between You and NetApp will be subject to resolution pursuant to the Contract Disputes Act of 1978.  Nothing contained in this CLA is meant to derogate the rights of the U.S. Department of Justice as identified in 28 U.S.C. <CODE>516</CODE>.  All other provisions of this CLA remain in effect as written.</li>
                            <li> General. This CLA will be construed pursuant to the laws of i) the State of California, United States, excluding its conflicts of law provisions, if You are located in the United States or in a country in which NetApp has no local sales subsidiary, or ii) the country in which You are located if NetApp has a local sales subsidiary in that country.  NetApp reserves the right to control all aspects of any lawsuit or claim that arises from Your use of the Content.  If required by NetApp's agreement with a third party licensor, NetApp's licensor will be a direct and intended third party beneficiary of this CLA and may enforce it directly against You.  NetApp does not waive any of its rights under this CLA by failing to or delaying the exercise of its rights or partially exercising its rights at any time.  To the extent that any Section of this CLA, or part thereof, is determined to be invalid or unenforceable, the remainder of this CLA shall remain in full force and effect.  This CLA may not be changed except by a written amendment executed by an authorized representative of each party.  In the event of a dispute between the English and non-English version of the CLA (where translated for local requirements), the English version of this CLA shall govern, to the extent permitted by applicable laws.  This CLA represents the entire agreement and understanding between NetApp and You with respect to the Content.  It supersedes any previous communications, representations or agreements between NetApp and You and prevails over any conflicting or additional terms in any quote, purchase order, acknowledgment, or similar communication between the parties.</li></ol>



                    </div>

                </div></div> <?php
        } else {
            header('Location: home.shtml');
        }
    } else if ($packType == 'ocipack') {
        ?>
        <div id="prfrmncEula" style="text-align: justify;">
            <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
            <p align="center"><font face="Arial" size="2"><br>
                <strong>CONTENT LICENSE AGREEMENT </strong></font></p><font face="Arial" size="2">
            <div id="body_content" class="eula-n">  

                <ol start="1">
                    <li>Scope. This content license agreement ("CLA") is between you ("You" or "Your") and NetApp, Inc. and (as applicable) its subsidiaries and affiliates ("NetApp"). This CLA sets forth the terms under which NetApp licenses the Content to You. In this CLA:<br /> <br /> a) "Content" means any OnCommand Insight download offered in the NetApp Storage Automation Store for use with the software;&nbsp; <br /> <br /> b) "Software" means NetApp software in object code format including (as applicable) operating systems, protocols, backup and recovery, disaster recovery, storage efficiency and management software.</li>
                    <li>Acceptance. By clicking the "I ACCEPT" button when, accessing or using the Content, You agree that this CLA will exclusively govern NetApp's license and Your use of the Content.. If You are accepting this CLA on behalf of another person, company or other legal entity, whether as an employee, contractor, distributor, reseller, partner, agent or otherwise, You represent and warrant that You have full authority to bind them. If You do not agree to the terms of this CLA, do not download, install, copy, access, or use the Content or click the "I ACCEPT" button, and promptly return the Content to the party from whom it was obtained.</li>
                    <li>License Grant. NetApp grants to You a personal, non-exclusive, non-transferrable, worldwide, limited and revocable license, without the right to sublicense, to install and use the Content for Your internal business purposes only.</li>
                    <li>License Restrictions. You shall not, nor shall You allow any third party to: a) reverse-engineer, decompile or disassemble the Content or otherwise reduce it to human-readable format except to the extent required for interoperability purposes under applicable laws or as expressly permitted in open-source licenses; <br /> <br /> b) remove or conceal any product identification, proprietary, intellectual property or other notices in the Content<br /> <br /> c) use the Content to perform services for third parties in a service bureau, managed services, commercial hosting services or similar environment; <br /> <br /> d) publish or provide any Content benchmark or comparison test results.</li>
                    <li>Intellectual Property Rights. The Content is licensed, not sold, to You. It is protected by intellectual property laws and treaties worldwide, and contains trade secrets, in which NetApp and its licensors reserve and retain all rights not expressly granted to You. No right, title or interest to any trademark, service mark, logo or trade name, of NetApp or its licensors is granted to You.</li>
                    <li>Disclaimer of Warranty. In consideration for the Content being provided without charge, You agree that the Content is provided "AS IS" and without warranty of any kind. All express or implied conditions, representations, and warranties, including without limitation any implied warranty of merchantability, fitness for a particular purpose, or non-infringement, are disclaimed, except to the extent that such disclaimers are held to be legally invalid by applicable law.</li>
                    <li>Limitation of Liability. In no event will NetApp be liable for any direct, indirect, incidental, consequential or special damages of any kind or for loss or corruption of data, or loss of revenue or financial loss arising out of or related to this agreement or the use of the Content even if NetApp has been advised of or otherwise has reason to know of the possibility of such damages. Further liability for such damage will be excluded, even if the exclusive remedies provided for in this Agreement fail of their essential purpose. The foregoing sets forth NetApp's entire liability and Your entire remedies available under this Agreement. The limitations and exclusions above shall not apply to liability for death or personal injury caused by negligence, gross negligence, willful misconduct, fraud or any other liability that cannot be excluded under applicable laws.</li>
                    <li>Termination. This CLA is effective until expiration or termination. You may terminate the CLA at any time on written notice to NetApp. NetApp may terminate the CLA immediately on written notice for material breach of the CLA. Upon expiration or termination of this CLA, You will promptly return or destroy all copies of the Content. Sections 1, 4, 5, 6, 9, 10, 11, and 12 shall survive expiration or termination of this CLA.</li>
                    <li>Software Copyright Information And Notices. Software copyright information and other related details are included as part of notices in the Documentation or other documentation published by NetApp (e.g. NOTICES.TXT or NOTICES. PDF).</li>
                    <li>Export Controls. You acknowledge that the Content supplied by NetApp under this CLA is subject to export controls under the laws and regulations of the United States, the European Union and other countries as applicable, and the Content may include export controlled technologies, including without limitation encryption technology. You agree to comply with such laws and regulations and, in particular, represent and warrant that You: <br /> <br /> a) shall not, unless authorized by U.S. export licenses or other government authorizations, directly or indirectly export or re-export the Software and Documentation to or use the Content in countries subject to U.S. embargoes or trade sanctions programs;<br /> <br /> b) are not a party, nor will You export or re-export to a party, identified on any government export exclusion lists, including but not limited to the U.S. Denied Persons, Entity, and Specially Designated Nationals Lists; and <br /> <br /> c) will not use the Content for any purposes prohibited by U.S. law, including but without limitation, the development, design, manufacture or production of nuclear, missile, chemical biological weaponry or other weapons of mass destruction. <br /> <br /> You agree to provide NetApp end use and end user information upon NetApp's request. You shall obtain all required authorizations, permits, or licenses to export, re-export or import, as required. You agree to obligate, by contract or other similar assurances, the parties to whom You re-export or otherwise transfer the Software to comply with all obligations set forth in this Section</li>
                    <li>U.S. Federal Government End Users. This Section 11 applies to You only if You are a U.S. Federal Government end user. The Content is "commercial" computer software and documentation and is licensed to You in accordance with the rights articulated in applicable U.S. government acquisition regulations (e.g. FAR, DFARs) pertaining to commercial computer software and documentation.. Any dispute between You and NetApp will be subject to resolution pursuant to the Contract Disputes Act of 1978. Nothing contained in this CLA is meant to derogate the rights of the U.S. Department of Justice as identified in 28 U.S.C. 516. All other provisions of this CLA remain in effect as written.</li>
                    <li>General. This CLA will be construed pursuant to the laws of i) the State of California, United States, excluding its conflicts of law provisions, if You are located in the United States or in a country in which NetApp has no local sales subsidiary, or ii) the country in which You are located if NetApp has a local sales subsidiary in that country. NetApp reserves the right to control all aspects of any lawsuit or claim that arises from Your use of the Content. If required by NetApp's agreement with a third party licensor, NetApp's licensor will be a direct and intended third party beneficiary of this CLA and may enforce it directly against You. NetApp does not waive any of its rights under this CLA by failing to or delaying the exercise of its rights or partially exercising its rights at any time. To the extent that any Section of this CLA, or part thereof, is determined to be invalid or unenforceable, the remainder of this CLA shall remain in full force and effect. This CLA may not be changed except by a written amendment executed by an authorized representative of each party. In the event of a dispute between the English and non-English version of the CLA (where translated for local requirements), the English version of this CLA shall govern, to the extent permitted by applicable laws. This CLA represents the entire agreement and understanding between NetApp and You with respect to the Content. It supersedes any previous communications, representations or agreements between NetApp and You and prevails over any conflicting or additional terms in any quote, purchase order, acknowledgment, or similar communication between the parties.</li>
                </ol>

            </div>
        </div>
        <?php } else {/* header( 'Location: home.php' ); */
        }
        ?>
            <?php if ($packType == 'report') { ?>
        <form method="POST" name="eulaForm" id="eulaForm" action="download-page.shtml?reportName=<?php echo $_REQUEST['reportName']; ?>&reportVersion=<?php echo $_REQUEST['reportVersion']; ?>&packType=<?php echo $_REQUEST['packType']; ?>" >
                <?php } else if ($packType == 'performance') { ?>
            <form method="POST" name="eulaForm" id="eulaForm" action="download-page.shtml?packName=<?php echo $_REQUEST['packName']; ?>&packVersion=<?php echo $_REQUEST['packVersion']; ?>&packType=<?php echo $_REQUEST['packType']; ?>">
                    <?php } else if ($packType == 'workflow') { ?>
                <form method="POST" name="eulaForm" id="eulaForm" action="download-page.shtml?packUuid=<?php echo $_REQUEST['packUuid']; ?>&packVersion=<?php echo $_REQUEST['packVersion']; ?>&packType=<?php echo $_REQUEST['packType']; ?> ">
                <?php } else if ($packType == 'snapcenter') { ?>
                <form method="POST" name="eulaForm" id="eulaForm" action="download-page.shtml?packUuid=<?php echo $_REQUEST['packUuid']; ?>&packVersion=<?php echo $_REQUEST['packVersion']; ?>&packType=<?php echo $_REQUEST['packType']; ?> ">
                    <?php } else if ($packType == 'ocipack') {
                        ?>		
                    <form method="POST" name="eulaForm" id="eulaForm" action="download-page.shtml?packName=<?php echo $_REQUEST['packName']; ?>&packVersion=<?php echo $_REQUEST['packVersion']; ?>&packType=<?php echo $_REQUEST['packType']; ?>">	   
                        <?php
                    } else {
                        header('Location: home.shtml');
                    }
                    ?>
                    <input name="reenter" value="true" type="HIDDEN">
                    <input name="accept" id="acceptHidden" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" type="HIDDEN">

                    <input name="decline" value="/home" type="HIDDEN">

            <!--<input type="hidden" name="packId" value="<?php echo $_REQUEST['packId']; ?>" />-->

                    <input name="search" id= "searchText" value="<?php echo $searchText; ?>" type="HIDDEN">
                    <center>
                        <table cellpadding="8" cellspacing="1" border="0" width="200">
                            <tbody><tr>
                                </tr><tr>
                                    <td class="dark1" align="center" valign="top">
                                        <input name="acceptBtn" value="I Accept" id="btn_accept"  style="cursor: pointer;"type="submit">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="dark2" align="center" valign="top"><b>
                                            <input name="declineBtn" id="declineBtn" value="Decline"  style="cursor: pointer;" type="submit" class="back">        
                                        </b></td>
                                </tr>
                            </tbody></table>
                    </center>
                </form>
                <p>

                </p></font>

                <iframe id="secretIFrame" src="" style="display:none; visibility:hidden;"></iframe>


                </body>
                </html>