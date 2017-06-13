<?php
/**
 * uploadWfaEula.php
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
                            window.parent.location.href = "pack-list.shtml";
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

       <!-- <p align="CENTER"><img src="images/head_ntap_logo.jpg" height="87" width="151"></p>
        <p align="center"><font face="Arial" size="2"><b>Base Customer Software License </b><br><br>
            <strong>UPLOAD WORKFLOW END USER LICENSE  AGREEMENT </strong></font></p><font face="Arial" size="2">

        <p>This end user license  agreement ("Agreement") is a contractual 
            agreement between you ("You" or  "Your") and NetApp ("NetApp"), and 
            provides the terms under which NetApp  licenses its i) software, 
            including where relevant, backup and recovery,  disaster recovery, 
            storage efficiency and management software, operating  systems, 
            protocols, updates and upgrades ("Software"), and ii) technical  
            documentation describing the Software ("Documentation") to You, whether 
            supplied  by NetApp, Your NetApp distributor, reseller or partner. 
            &nbsp;Any support is provided under a separate  agreement.</p>
        <p><strong>1. Acceptance.</strong> By downloading, installing, copying, 
            accessing  or using the Software and Documentation, or by clicking the 
            "I ACCEPT" button below,  You agree that this Agreement will exclusively
            govern NetApp's license and Your  use of the Software and Documentation
            unless You have a separate applicable  written agreement with NetApp 
            which governs NetApp's license and Your use of  the Software and 
            Documentation.<strong></strong></p>
        <p><strong>If You are accepting this Agreement on behalf of another  
                person, company or other legal entity (whether as an employee, 
                contractor, distributor,  reseller, partner, agent or otherwise), You 
                represent and warrant that You have  full authority to bind them.&nbsp; 
                If You do  not agree to the above, do not download, install, copy, 
                access, or use the  Software and Documentation, or click the "I ACCEPT" 
                button, and promptly return  the Software and Documentation to the party
                from whom it was obtained.</strong></p>
        <p><strong>2. License Grant.</strong> Subject to the terms of this 
            Agreement,  NetApp grants to You a personal, non-exclusive, worldwide, 
            limited and  terminable license, without the right to sublicense, to i) 
            install and use the  Software (in object code form) for Your internal 
            business purposes, and ii) use  the Documentation in support of Your use
            of the Software. &nbsp;The Software may either be licensed for use  
            with i) a specific storage controller identified by a unique serial 
            number ("Controller-based  licenses"), or ii) independent of a storage 
            controller ("Standalone licenses").&nbsp; Software is licensed in 
            accordance with one  of the following license types:&nbsp; </p>
        <ol type="a">
            <li> "Life-of-controller licenses": Controller-based licenses  granted
                for the period of time during which Your controller is operable; </li>
            <li> "Perpetual licenses": &nbsp;Standalone licenses granted in perpetuity; </li>
            <li> "Term licenses": &nbsp;Controller-based  licenses or Standalone licenses granted for a fixed period of time;</li>
            <li> "Capacity licenses": &nbsp;Controller-based  licenses or 
                Standalone licenses granted for a specified amount of storage  capacity 
                or usage; and </li>
            <li> "Subscription licenses": &nbsp;Term licenses or Capacity licenses which may  be purchased on a periodic basis.&nbsp; </li>
        </ol>
        <p>Certain license types  may require the installation and use of AutoSupport<strong>TM</strong>.</p>
        <p>Each storage controller  deployed in a cluster or a high-availability
            pair or group must have the same  Controller-based licenses as the 
            other storage controllers in that cluster,  high-availability pair or 
            group. &nbsp;Subject  to NetApp's prior written agreement, and in the 
            context of non-disruptive  operations within a cluster, You may deploy 
            storage controllers with different  Controller-based licenses and 
            failover from one storage controller to another for  the time required 
            to remedy a failure, provided that all storage controllers in  the 
            cluster have the same hardware and software support offerings in effect 
            at  all times. &nbsp;</p>
        <p>Use of the Software outside  of the scope of this Agreement 
            constitutes a material breach and You agree to  promptly pay to NetApp 
            any additional license fees notified by NetApp, calculated  in 
            accordance with NetApp's price list.</p>
        <p><strong>3. License Restrictions.</strong> Except as otherwise expressly licensed  to You, You shall not, nor shall You allow any third party to: </p>
        <ol type="a">
            <li>reverse-engineer, decompile or disassemble the Software or  
                otherwise reduce it to human-readable form except to the extent required
                for  interoperability purposes under applicable laws or as expressly 
                permitted in open-source  licenses; </li>
            <li>remove or conceal any product identification, copyright,  
                proprietary, patent or other notices in the Software and Documentation; </li>
            <li>assign or otherwise transfer, in whole or in part, the  Software 
                and Documentation licenses to another party or Controller-based  
                licenses to a different storage controller; </li>
            <li>use the Software and Documentation to perform services for  third 
                parties in a service bureau, managed services, commercial hosting  
                services or similar environment; </li>
            <li>modify, adapt or create a derivative work of the Software  and Documentation; and </li>
            <li>publish or provide any Software benchmark or comparison test  results.</li>
        </ol>
        <p><strong>4. </strong><strong>Intellectual Property Rights And Protection.</strong>
            The Software and  Documentation is licensed, not sold, to You.&nbsp;  
            It is protected by intellectual property laws and treaties worldwide,  
            and contains trade secrets, in which NetApp and its licensors reserve 
            and  retain all rights not expressly granted to You.&nbsp;  No right, 
            title or interest to any trademark, service mark, logo or  trade name, 
            of NetApp or its licensors is granted to You.&nbsp; </p>
        <p>NetApp will defend You  against claims brought by a third party 
            alleging that the Software infringes  any patent, trademark or copyright
            ("IP Claim") and indemnify You for a  settlement amount or the judgment
            amount finally awarded for such IP Claim  (collectively, "Damages") 
            provided that You have: </p>
        <ol>
            <ol type="a">
                <li>promptly notified NetApp in writing of the IP Claim; </li>
                <li>provided information and assistance to NetApp; and </li>
                <li>given NetApp sole control of the defense and settlement  negotiations.&nbsp; &nbsp;&nbsp;</li>
            </ol>
        </ol>
        <p>NetApp has no  liability for claims that arise from: </p>
        <ol>
            <ol type="a">
                <li> unauthorized modification or use of the Software by You or a  third party; </li>
                <li> combination, operation, or use of the Software with any  products not provided by NetApp; </li>
                <li> services offered by You or revenues earned by You for such  services; </li>
                <li> NetApp's compliance with or use of Your designs,  
                    specifications, instructions or technical information provided by You or
                    a third party on Your behalf; or </li>
                <li>Your failure to install an upgrade, new version, change or  modification made available or requested by NetApp.&nbsp; </li>
            </ol>
        </ol>
        <p>NetApp may, at its discretion: </p>
        <ol>
            <ol type="a">
                <li> substitute the Software with a functional equivalent in all  material respects; </li>
                <li> modify the Software so long as it remains a functional  equivalent in all material respects; </li>
                <li> obtain for You, at NetApp's expense, the right to continue  use of the Software; or </li>
                <li> take back such Software and refund to You the purchase price  
                    paid for it, less depreciation amortized on a straight line basis over a
                    three  (3) year period from date of first delivery of the Software to 
                    You. &nbsp;</li>
            </ol>
        </ol>
        <p>NetApp's cumulative  aggregate liability arising under this section 4
            will not exceed USD$500,000.&nbsp; To the extent permitted by 
            applicable laws,  this section 4 sets out Your exclusive remedies for 
            any IP Claim.&nbsp; </p>
        <p><strong>5. Direct Warranty. </strong>NetApp warrants to You that for a
            period of ninety (90) days from the date of first delivery of the 
            Software to You,  Your NetApp distributor, reseller or partner, 
            whichever is the earlier, or such  other minimum periods under 
            applicable laws, (the "Software Warranty Period"),  for the 
            initially-shipped version of such Software, that i) the Software will  
            materially conform to the then-current Documentation in effect on the 
            date of  Software delivery, and ii) the media containing the Software 
            will be free from  physical defects. &nbsp;NetApp does not  warrant that
            Your use of the Software will be error-free or uninterrupted. &nbsp;In 
            the event of any material defect in the  Software during the Software 
            Warranty Period, NetApp will, at its sole  discretion and expense, 
            repair or replace the Software, or refund the purchase  price paid by 
            You for the non-conforming Software. This warranty covers only  material
            defects in the Software that are reproducible and verifiable and does  
            not cover software, other items, or any services provided by persons 
            other than  NetApp or a NetApp distributor, reseller or partner. 
            &nbsp;The Software warranty will be void if You or  any third parties 
            misuse, neglect, improperly install or test, attempt to  repair or 
            modify (except as authorized by NetApp in writing), or use the  Software
            beyond the range of the intended use. &nbsp;TO THE EXTENT PERMITTED BY 
            APPLICABLE LAWS,  THE FOREGOING WARRANTIES AND REMEDIES ARE EXCLUSIVE 
            AND NO OTHER WARRANTY OR  REMEDY, WHETHER WRITTEN OR ORAL, IS EXPRESSED 
            OR IMPLIED. NETAPP SPECIFICALLY  DISCLAIMS THE IMPLIED WARRANTIES OF 
            MERCHANTABILITY, ACCEPTABLE QUALITY,  FITNESS FOR A PARTICULAR PURPOSE 
            AND NON-INFRINGEMENT.</p>
        <p><strong>6. Limitation of Liability.</strong> Except for claims  
            arising under section 4 above, and regardless of the basis of the claim 
            (e.g.  contract, tort or statute), the total liability of NetApp and its
            licensors, under  or in connection with this Agreement, shall not 
            exceed the amount actually received  by NetApp for the Software or the 
            minimum amounts permitted by applicable laws.&nbsp; NetApp and its 
            licensors are not liable for:</p>
        <ol>
            <ol type="a">
                <li> any indirect, incidental, exemplary, special or  consequential damages; </li>
                <li> loss or corruption of data; </li>
                <li> loss of revenues, profits, goodwill or anticipated savings; </li>
                <li> procurement of substitute goods and/or services; or </li>
                <li> interruption to business; even if it has been advised of the  possibility of such claims or damages.&nbsp; </li>
            </ol>
        </ol>
        <p>The limitations and  exclusions above shall not apply to liability 
            for death or bodily injury caused  by negligence, gross negligence, 
            willful misconduct, fraud or any other  liability that cannot be 
            excluded under applicable laws.</p>
        <p><strong>7. Audit.</strong> You grant NetApp and its independent 
            accountants the right  to examine Your Software usage once annually 
            during regular business hours upon  reasonable notice to verify 
            compliance with this Agreement.&nbsp; If the audit discloses material  
            non-compliance, You shall promptly pay to NetApp any additional license 
            fees  notified by NetApp, calculated in accordance with NetApp's price 
            list, and the  reasonable costs of conducting such audit, if any.&nbsp; 
            Following any non-compliance, you may be  subjected to more frequent 
            audits. </p>
        <p><strong>8. Termination.</strong> This Agreement is effective until  
            terminated.&nbsp; You may terminate the  Agreement at any time on 
            written notice to NetApp.&nbsp; NetApp may terminate this Agreement  
            immediately on written notice to You if You commit a material breach of 
            the  Agreement, including remitting payments  when due (whether payable 
            to NetApp or its authorized third party  financing partners in 
            connection with  an Approved Financing Agreement, described in section 
            13 below) and, in  the event that the breach is remediable, fail to 
            remedy it within thirty (30)  days of NetApp's written notice requiring 
            You to do so.&nbsp; Upon termination of this Agreement, all  rights to 
            use the Software and Documentation cease and You shall, at NetApp's  
            request, promptly return or destroy all copies of the Software and  
            Documentation, including any license enablement keys, in Your possession
            or  under Your control.&nbsp; Sections 1, 3, 4, 5,  6, 8, 10, 11, 12, 
            13, 14 and 15 shall survive termination of this Agreement.</p>
        <p><strong>9. Software  Copyright Information And Notices.</strong> 
            Software  copyright information and other related details are included 
            as part of notices  in the Documentation or other documentation 
            published by NetApp (e.g.  NOTICES.TXT or NOTICES. PDF). </p>
        <p><strong>10. U.S. Government Regulations.</strong> Software and 
            Documentation  license rights granted to governments and other public 
            sector entities include  only those rights customarily provided to 
            commercial end-user customers. &nbsp;In particular, NetApp provides the 
            licenses  for Software and Documentation in this Agreement to the U.S. 
            federal government  pursuant to FAR 12.211 (Technical Data) and 12.212 
            (Computer Software) and for  the Department of Defense pursuant to DFARS
            252.227-7015 (Technical Data -  Commercial Items) and DFARS 227.7202-3 
            (Rights in Commercial Computer Software  or Computer Software 
            Documentation).&nbsp; </p>
        <p>Some Software  components have been designed to enable compliance 
            with 'non-rewriteable and  non-erasable' US government regulations. 
            &nbsp;When enabled, they i)  will&nbsp;preserve data in a 
            non-rewriteable and non-erasable format, ii)&nbsp;may  disable other 
            Software restoration functionality, and iii) could&nbsp;limit  support 
            and recovery procedures, if relevant. &nbsp;You will ensure that Your  
            internal processes and systems, including but not limited to the use of 
            the Software,  comply with all applicable&nbsp;"non-rewriteable and 
            non-erasable"&nbsp;US  government regulations.&nbsp; NetApp fully 
            disclaims any associated liability.</p>
        <p><strong>11. Export Control Laws And Regulations.</strong> The 
            Software and  Documentation is subject to applicable export control laws
            and regulations of  the United States and other countries and You agree
            to comply with them. You  represent and warrant that You: </p>
        <ol>
            <ol type="a">
                <li>will not, directly or indirectly, export or re-export the  
                    Software and Documentation to, or use the Software and Documentation in,
                    countries subject to U.S. embargoes or trade sanctions programs, 
                    unless  authorized by U.S. export licenses or other government 
                    authorizations (as of  December 2012, these countries are: Cuba, North 
                    Korea, Iran, Sudan and Syria); </li>
                <li>will comply with any updates and revisions that the U.S.  
                    Government makes to the sanctions, embargoes and the list of countries  
                    specified in section 11(a) above; </li>
                <li>are not a party, nor will you export or re-export to a  party, 
                    identified on any government export exclusion lists, including but not  
                    limited to the U.S. Denied Persons, Entity, and Specially Designated 
                    Nationals  Lists; and </li>
                <li>will not use the Software and Documentation for any purposes  
                    prohibited by United States law, including but without limitation, the  
                    development, design, manufacture or production of nuclear, missile, 
                    chemical,  biological weaponry or other weapons of mass destruction.</li>
            </ol>
        </ol>
        <p><strong>12. Data Privacy.</strong> You have sole responsibility  for 
            personal data managed or stored using the Software and agree to comply 
            with  all applicable data privacy laws.</p>
        <p><strong>13. Financed Software.</strong> This Agreement also applies 
            to "Financed Software," which means Software  and Documentation licensed
            to You for a limited period of use pursuant to the  terms of a 
            financing agreement between You and NetApp or its authorized third  
            party financing partner (an "Approved Financing Agreement"), subject to 
            the  following: </p>
        <ol>
            <ol type="a">
                <li>The particular Financed Software, period of use,  installation 
                    site and other transaction-specific conditions shall be as agreed  in 
                    the applicable Approved Financing Agreement; and </li>
                <li>Notwithstanding any contrary terms in this Agreement, all  
                    licenses for Financed Software terminate at the expiration of the term 
                    of the  Approved Financing Agreement or when sooner terminated by NetApp
                    (whether in  accordance with this Agreement or the Approved Financing 
                    Agreement).&nbsp; </li>
            </ol>
        </ol>
        <p>You agree that the  license granted under section 2 above and 
            NetApp's termination rights under  section 8 above may be affected by an
            authorized third party financing  partner's rights under the applicable
            Approved Financing Agreement, even if  such partner has paid to NetApp 
            all or any portion of the license fees for the  Financed Software.</p>
        <p><strong>14. Evaluation Software.</strong> Subject to the terms of 
            this  Agreement, as amended by this section 14, NetApp may grant to You 
            evaluation  licenses for the Software and Documentation at no cost for a
            ninety (90) days  period from the initial delivery of the Software to 
            You, Your NetApp  distributor, reseller or partner, whichever is the 
            earlier, or such other  period as agreed by NetApp in writing.&nbsp;  
            Such licenses may only be used in a non-production environment to assess
            the suitability of the Software and Documentation for Your 
            needs.&nbsp; Notwithstanding section 5 above, the  evaluation Software 
            and Documentation is licensed to You on an "AS IS" basis  and all 
            warranties, whether express, implied, statutory or otherwise are  
            excluded to the maximum extent permitted by applicable laws. <strong></strong></p>
        <p><strong>15. General. </strong>This Agreement shall be construed  
            pursuant to the laws of i) the State of California, United States, 
            excluding  its conflicts of law provisions, if You are located in the 
            United States or in  a country in which NetApp has no local sales 
            subsidiary, or ii) the country in  which You are located if NetApp has a
            local sales subsidiary in that country.&nbsp; NetApp reserves the right
            to control all  aspects of any lawsuit or claim that arises from Your 
            use of the Software and  Documentation. &nbsp;If required by NetApp's  
            agreement with a third party licensor, NetApp's licensor shall be a 
            direct and  intended third party beneficiary of this Agreement and may 
            enforce it directly  against You.&nbsp; NetApp does not waive any  of 
            its rights under this Agreement by failing to or delaying the exercise 
            of  its rights or partially exercising its rights at any time.&nbsp; To 
            the extent that any provision of this  Agreement is determined to be 
            invalid or unenforceable, the remainder of this  Agreement shall remain 
            in full force and effect.&nbsp; This Agreement may not be changed except
            by  an amendment accepted by an authorized representative of each 
            party.&nbsp; In the event of a dispute between the English  and 
            non-English version of the Agreement (where translated for local  
            requirements), the English version of this Agreement shall govern, to 
            the  extent permitted by applicable laws.&nbsp; This  Agreement 
            represents the entire agreement and understanding between NetApp and  
            You with respect to the Software and Documentation.&nbsp; It supersedes 
            any previous communications,  representations or agreements between 
            NetApp and You and prevails over any  conflicting or additional terms in
            any quote, purchase order, acknowledgment,  or similar communication 
            between the parties.<strong></strong></p>
        <p align="justify">&nbsp;</p>
        <p align="justify">Version: 5 February 2013</p> -->
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

        <!-- download.php?packPath=<?php echo $newPath; ?> -->
        <form method="POST" name="uploadWfaEulaForm" id="uploadWfaEulaForm" action="pack-upload.shtml">
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