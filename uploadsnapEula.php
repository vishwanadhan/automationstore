<?php
/**
 * uploadsnapEula.php
 * Page specifying the End User License Agreement for end user before downloading.
 * 
 */

require_once('config/configure.php');
require_once('includes/function/autoload.php');

$rootPath = $docRoot;
$packPath = $_REQUEST['packPath'];

$newPath = $rootPath . (base64_decode($packPath));

if (!isset($_REQUEST['searchText'])) {
    $_REQUEST['searchText'] = NULL;
}
$searchText = $_REQUEST['searchText'];
?>

<html>
    <head>

        <link rel="stylesheet" type="text/css" href="css/style1.css">
        <link rel="stylesheet" type="text/css" href="css/eula.css" />
        <script type="text/javascript" src="js/jquery-1.9.1.js"></script>
        <script type="text/javascript">

            $(function()
            {
                var searchTxt = null;
                var submitActor = null;
                var $form = $('#uploadWfaEulaForm');
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
                            $('#uploadWfaEulaForm').prop('action', 'search-packs.shtml');
                            $('#uploadWfaEulaForm').submit();
                        }
                        else {
                            url = '<?php echo basename($_SERVER['HTTP_REFERER']); ?>';
                            window.parent.location.href = "snap-list.shtml";
                            return false;
                        }
                    }
                    if (submitActor.name == 'acceptBtn')
                    {
                        $('#uploadWfaEulaForm').submit();
                    }

                    return false;
                });

                $submitActors.click(function(event)
                {
                    submitActor = this;
                });

            });


        </script>
    </head>

    <body bgcolor="#FFFFFF">
<div id="workflowEula">
        <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
        <p align="center"><font face="Arial" size="2"><br>
            <strong>NETAPP CONTRIBUTOR AGREEMENT </strong></font></p><font face="Arial" size="2">
   <div id="" class="eula-n">  
    This Contributor Agreement ("CA") is between you ("Contributor," "You," "you," "Your," or "your") and NetApp, Inc. and (as applicable) its subsidiaries and affiliates ("NetApp").  This CA sets forth the terms under which You license your Contribution to NetApp.  
    <br><br>
      In this CA:<br>

        a) "Contribution" means any source code, object code, patch, tool, sample, graphic, specification, manual, documentation, or any other material posted or submitted by you to NetApp Storage Automation Store.<br><br>

    <strong>By clicking the "I ACCEPT" button when, uploading or providing Your Contribution to NetApp Storage Automation Store, You agree that this CA will exclusively govern NetApp's license of Your Contribution.  If You are accepting this CA on behalf of another person, company or other legal entity, whether as an employee, contractor, distributor, reseller, partner, agent or otherwise, You represent and warrant that You have full authority to bind them.  If You do not agree to the terms of this CA, do not upload, provide, or click the "I ACCEPT" button. </strong>
      <ol><li>
     With respect to any worldwide copyrights, or copyright applications and registrations, in Your Contribution:
          <ul><li>  You hereby grant to us a perpetual, irrevocable, non-exclusive, assignable, worldwide, fully paid up, royalty-free, unrestricted license to exercise all rights under those copyrights. This includes, at NetApp's option, the right to sublicense these same rights to third parties through multiple levels of sublicensees or other licensing arrangements;</li>
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
        </div>        
       <p> </p>
        <a name="bottom"><hr size="1"></a>
        <p>
        </p>
        <form method="POST" name="uploadWfaEulaForm" id="uploadWfaEulaForm" action="snap-upload.shtml">
            <input name="reenter" value="true" type="HIDDEN">
            <input name="accept" id="acceptHidden" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" type="HIDDEN">

            <input name="decline" value="/home" type="HIDDEN">

            <input type="hidden" name="packId" value="<?php echo $_REQUEST['packId']; ?>" />

            <input name="search" id= "searchText" value="<?php echo $searchText; ?>" type="HIDDEN">
            <center>
                <table cellpadding="8" cellspacing="1" border="0" width="200">
                    <tbody><tr>
                        </tr><tr>
                            <td class="dark1" align="center" valign="top">
                                <input name="acceptBtn" value="I Accept" id="btn_accept" type="submit">
                            </td>
                        </tr>
                        <tr>
                            <td class="dark2" align="center" valign="top"><b>
                                    <input name="declineBtn" id="declineBtn" value="Decline" type="submit" class="back">        
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