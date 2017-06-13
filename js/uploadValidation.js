// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+"+".";
// Minimum no of digits in an international phone no.
var minDigitsInIPhoneNumber = 10;
var maxDigitsInIPhoneNumber = 11;

function isInteger(s)
{   var i;
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag)
{   var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function checkInternationalPhone(field,message){
	strPhone=field.value;
	s=stripCharsInBag(strPhone,validWorldPhoneChars);
	bol= (isInteger(s) && s.length >= minDigitsInIPhoneNumber && s.length <= maxDigitsInIPhoneNumber);
	if(bol==false)
	{ 
	   return true;
	}
	else
	{
	 return false;
	}
}


function isVUrl(s)
	{
		var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
		return regexp.test(s);
	}

function validCMS(s)
	{
		str=s.replace(/(&nbsp;)/ig,""); 
		return str;
	}


/****** To check email format ******/
function ValidateEmail(mail){  
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {  
    return (true)  
  }      
    return (false)  
}

/****** To check valid file extension  ******/
	function ValidateExtension() {       
        var filename = document.getElementById("zip_upload").value;		
		var fileExtensions = filename.substring(filename.lastIndexOf('.') + 1);		
		return ((fileExtensions == "zip") ?  true : false);    
    }
	
	function previewValidateExtension() {         
        var filename = document.getElementById("preview_file").value;		
		var fileExtensions = filename.substring(filename.lastIndexOf('.') + 1);		
		return ((fileExtensions.toLowerCase() == "jpg" || fileExtensions.toLowerCase() == "jpeg" || fileExtensions.toLowerCase() == "png") ?  true : false);    
    }
	

/****** To check valid wfa file extension  ******/
function wfaHelpDocValidateExtension() {        
	var filename = document.getElementById("wfa_help_doc_html").value;		
	var fileExtensions = filename.substring(filename.lastIndexOf('.') + 1);		
	return ((fileExtensions == "html" || fileExtensions == "htm") ?  true : false);    
}
/****** To trim spaces from left  ******/
	function LTrim( value ) {
	value = new String(value);
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "$1");
	}
/****** To trim spaces from right  ******/
	function RTrim( value )
	{
	value = new String(value);
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "$1");
	}
/****** To trim spaces from left and right both ******/
	function trim( value )
	{
	return LTrim(RTrim(value));
	}	 
	
/***** Upload OCI form validations *****/

function check_upload(upload_field)
{    
	var re_text = /\.jpg|\.png|\.jpeg/i;    
	var filename = upload_field.value;
	// Checking file type
	if (filename.search(re_text.toLowerCase()) == -1)    
	{ 
	$("#dvPreview").hide();
	$("#removefile").hide();       
	document.getElementById("previewFileError").innerHTML = "File should be only in .jpg .jpeg or .png format. ";	
	upload_field.value = '';
	return false;    
	}
document.getElementById("previewFileError").innerHTML = "";
	
	return true;
}	
 
function submitformoci(formObj)
{		 
	divs = document.getElementsByClassName( 'form-error' );  
		[].slice.call( divs ).forEach(function ( div ) { 
		div.innerHTML = '';
	});  

	var flag0, flag1, flag2, flag3, flag4, flag5, flag6, flag7, flag8, flag9;    
	
	var ociTypeId			= document.forms["upload_oci"]["ociTypeId"].value;
	var filename			= document.forms["upload_oci"]["zip_file"].value;	
	var preview_file		= document.forms["upload_oci"]["preview_file"].value;
	var oci_name			= trim(document.forms["upload_oci"]["oci_name"].value);
	var oci_desc 			= trim(document.forms["upload_oci"]["oci_desc"].value);		
	var oci_pack_version	= trim(document.forms["upload_oci"]["oci_pack_version"].value);
	var oci_version			= trim(document.forms["upload_oci"]["oci_version"].value);
	var author_name			= trim(document.forms["upload_oci"]["author_name"].value);	
	var author_email		= trim(document.forms["upload_oci"]["author_email"].value);
//	var author_phone		= trim(document.forms["upload_oci"]["author_phone"].value);	
	
	if (ociTypeId == null || ociTypeId == "0") {	
		document.getElementById("ociTypeError").innerHTML = "No ociType selected to upload";		
		flag0 = 'true'; 
    }else{document.getElementById("ociTypeError").innerHTML = "";}
	
	
	if (filename == null || filename == "") {	
		document.getElementById("fileError").innerHTML = "No file selected to upload";		
		flag1 = 'true'; 
    }else if(ValidateExtension() == false){	
		document.getElementById("fileError").innerHTML = "Please select only .zip files to upload";	
		flag1 = 'true'; 
	}else{document.getElementById("fileError").innerHTML = "";}
	
	
	if(preview_file != '') 
	{
		 if(previewValidateExtension() == false){	
			document.getElementById("previewFileError").innerHTML = "Please select only .jpg .jpeg or .png files to upload";	
			flag2 = 'true'; 
		}
		else{
			document.getElementById("previewFileError").innerHTML = "";
		}
	}
	
    if (oci_name == null || oci_name == "") {	
		document.getElementById("ociNameError").innerHTML = "Name must not be empty";		
		flag3 = 'true'; 
    }else if(!oci_name.match(/^[ 0-9a-zA-Z-_.]+$/)){ 		
		document.getElementById("ociNameError").innerHTML = "Pack Name must not contain any special characters";		
		flag3 = 'true'; 
    }else{document.getElementById("ociNameError").innerHTML = "";}
	
	if (oci_desc == null || oci_desc == "") {	
		document.getElementById("ociDescError").innerHTML = "Description must not be empty";		
		flag4 = 'true'; 
    }else{document.getElementById("ociDescError").innerHTML = "";}
	
	if (oci_pack_version == null || oci_pack_version == "") {		
		document.getElementById("versionError").innerHTML = "Version must not be empty";		
		flag5 = 'true'; 
    }else if(!oci_pack_version.match(/^[0-9a-zA-Z.]+$/)){ 		
		document.getElementById("versionError").innerHTML = "Version must not contain any special characters except dot(.)";		
		flag5 = 'true'; 
    }else{document.getElementById("versionError").innerHTML = "";}	
	
	if (oci_version == null || oci_version == "") {		
		document.getElementById("ociVerError").innerHTML = "OCI Version must not be empty";		
		flag6 = 'true'; 
    }else if(!oci_version.match(/^[0-9a-zA-Z.]+$/)){ 		
		document.getElementById("ociVerError").innerHTML = "OCI Version must not contain any special characters except dot(.)";		
		flag6 = 'true'; 
    }else{document.getElementById("ociVerError").innerHTML = "";}
		
/* 	if (min_version == null || min_version == "") {	
		document.getElementById("minOntapError").innerHTML = "Min ONTAP Version must not be empty";		
		document.upload_oci.min_version.focus() ;		
        return false;
    }else if(!min_version.match(/^[0-9a-zA-Z.]+$/)){ 		
		document.getElementById("minOntapError").innerHTML = "Min ONTAP version must not contain any special characters except dot(.)";		
		document.upload_oci.min_version.focus() ;		
        return false;
    }else{document.getElementById("minOntapError").innerHTML = "";}	 */

	
	if (author_name == null || author_name == "") {	
		document.getElementById("authorNameError").innerHTML = "Author name must not be empty";		
		flag7 = 'true'; 
    }else if(!author_name.match(/^[ a-zA-Z-_]+$/)){			
		document.getElementById("authorNameError").innerHTML = "Author Name must contain only characters";		
		flag7 = 'true'; 
    }else{
	document.getElementById("authorNameError").innerHTML = "";}		
	
	if(author_email != '') 
	{	
		if(ValidateEmail(author_email) == false){
			document.getElementById("authorEmailError").innerHTML = "Invalid format of email";
			flag8 = 'true'; 
		}else{
		document.getElementById("authorEmailError").innerHTML = "";}
	}
	
/* 	if(author_phone != '')  
	{	
   
		if(!author_phone.match(/^[0-9]{10,14}$/)){		
			document.getElementById("authorPhoneError").innerHTML = "Phone number is invalid (10-14 digits)";		
			flag9 = 'true'; 
		}
		else
		{
			document.getElementById("authorPhoneError").innerHTML = "";
		}
	} */

	
	if(flag0 == 'true' || flag1 == 'true' || flag2 == 'true' || flag3 == 'true' || flag4 == 'true' || flag5 == 'true' || flag6 == 'true' || flag7 == 'true' || flag8 == 'true' || flag9 == 'true'){
		formObj.ociTypeId.focus() ;
		return false;
	}else{
		return true;	
	}
	
}	
	
/***** Upload OCUM form validations *****/	
function submitformocum()
{	
	divs = document.getElementsByClassName( 'form-error' );
	[].slice.call( divs ).forEach(function ( div ) {
		div.innerHTML = '';
	});
	var flag0, flag1, flag2, flag3, flag4, flag5, flag6, flag7;
	
	var reportname	= trim(document.forms["upload_ocum"]["ocum_name"].value);
	var desc 	  	= trim(document.forms["upload_ocum"]["ocum_desc"].value);
	var filename	= document.forms["upload_ocum"]["zip_file"].value;
	var version		= trim(document.forms["upload_ocum"]["report_version"].value);
	var ocumVersion	= trim(document.forms["upload_ocum"]["ocum_version"].value);
	var minversion	= trim(document.forms["upload_ocum"]["min_version"].value);
	var authorName	= trim(document.forms["upload_ocum"]["author_name"].value);
	var authorEmail	= trim(document.forms["upload_ocum"]["author_email"].value);
		
	if (filename == null || filename == "") {	
		document.getElementById("fileError").innerHTML = "No file selected to upload";				
			flag0 = 'true';     
    }else if(ValidateExtension() == false){	
		document.getElementById("fileError").innerHTML = "Please select only .zip files to upload";					
        flag0 = 'true';		
	}else{document.getElementById("fileError").innerHTML = "";}
	
    if (reportname == null || reportname == "") {	
		document.getElementById("ocumNameError").innerHTML = "Name must not be empty";					
        flag1 = 'true';		
	}else if(!reportname.match(/^[ 0-9a-zA-Z-_.]+$/)){		
		document.getElementById("ocumNameError").innerHTML = "Report Name must not contain any special characters";
        flag1 = 'true';		
    }else{document.getElementById("ocumNameError").innerHTML = "";}
	
	if (desc == null || desc == "") {	
		document.getElementById("ocumDescError").innerHTML = "Description must not be empty";					
        flag2 = 'true';		
    }else{document.getElementById("ocumDescError").innerHTML = "";}
		
	if (version == null || version == "") {	
		document.getElementById("versionError").innerHTML = "Version must not be empty";				
		flag3 = 'true';	     
    }else if(!version.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("versionError").innerHTML = "Version must not contain any special characters except dot(.)";		
        flag3 = 'true';		
    }else{document.getElementById("versionError").innerHTML = "";}
		
	if (ocumVersion == null || ocumVersion == "") {	
		document.getElementById("ocumVerError").innerHTML = "OCUM Version must not be empty";				
        flag4 = 'true';		
    }else if(!ocumVersion.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("ocumVerError").innerHTML = "OCUM Version must not contain any special characters except dot(.)";			
        flag4 = 'true';		
    }else{document.getElementById("ocumVerError").innerHTML = "";}
	
	if (minversion == null || minversion == "") {	
		document.getElementById("minOntapError").innerHTML = "Min ONTAP Version must not be empty";				
        flag5 = 'true';		
    }else if(!minversion.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("minOntapError").innerHTML = "Min ONTAP Version must not contain any special characters except dot(.)";				
        flag5 = 'true';		
    }else{document.getElementById("minOntapError").innerHTML = "";}
	
	if (authorName == null || authorName == "") {	
		document.getElementById("authorNameError").innerHTML = "Author name must not be empty";				
        flag6 = 'true';	
    }else if(!authorName.match(/^[ a-zA-Z-_]+$/)){		
		document.getElementById("authorNameError").innerHTML = "Author Name must contain only characters";				
        flag6 = 'true';		
    }else{document.getElementById("authorNameError").innerHTML = "";}
	   
	if(authorEmail != '') 
	{
		if(ValidateEmail(authorEmail) == false){
			document.getElementById("authorEmailError").innerHTML = "Invalid format of email";		
			flag7 = 'true';		
		}else{document.getElementById("authorEmailError").innerHTML = "";}
	}
	  
	if(flag0 == 'true' || flag1 == 'true' || flag2 == 'true' || flag3 == 'true' || flag4 == 'true' || flag5 == 'true' || flag6 == 'true' || flag7 == 'true'){
		document.upload_ocum.zip_file.focus();
		return false;
	}else{
		return true;	
	}
	
}

/***** Upload OPM form validations *****/
function submitformopm()
{			
	divs = document.getElementsByClassName( 'form-error' );
	[].slice.call( divs ).forEach(function ( div ) {
		div.innerHTML = '';
	});	
	 
	var flag0, flag1, flag2, flag3, flag4, flag5, flag6;
	
	var filename	= document.forms["upload_opm"]["zip_file"].value;	
	var reportname	= trim(document.forms["upload_opm"]["opm_name"].value);
	var desc 	  	= trim(document.forms["upload_opm"]["opm_desc"].value);		
	var version		= trim(document.forms["upload_opm"]["pack_version"].value);
	var opmVersion	= trim(document.forms["upload_opm"]["opm_version"].value);
	var minversion	= trim(document.forms["upload_opm"]["min_version"].value);
	var authorName	= trim(document.forms["upload_opm"]["author_name"].value);	
		
	if (filename == null || filename == "") {	
		document.getElementById("fileError").innerHTML = "No file selected to upload";				
        flag0 = 'true'; 
    }else if(ValidateExtension() == false){	
		document.getElementById("fileError").innerHTML = "Please select only .zip files to upload";			
        flag0 = 'true'; 
	}else{document.getElementById("fileError").innerHTML = "";}
	
    if (reportname == null || reportname == "") {	
		document.getElementById("opmNameError").innerHTML = "Name must not be empty";			
        flag1 = 'true';	
    }else if(!reportname.match(/^[ 0-9a-zA-Z-_.]+$/)){ 		
		document.getElementById("opmNameError").innerHTML = "Pack Name must not contain any special characters";
         flag1 = 'true';	
    }else{document.getElementById("opmNameError").innerHTML = "";}
	
	if (desc == null || desc == "") {	
		document.getElementById("opmDescError").innerHTML = "Description must not be empty";				
         flag2 = 'true';	
    }else{document.getElementById("opmDescError").innerHTML = "";}
		
	if (version == null || version == "") {		
		document.getElementById("versionError").innerHTML = "Version must not be empty";				
         flag3 = 'true';	
    }else if(!version.match(/^[0-9a-zA-Z.]+$/)){ 		
		document.getElementById("versionError").innerHTML = "Version must not contain any special characters except dot(.)";				
         flag3 = 'true';	
    }else{document.getElementById("versionError").innerHTML = "";}
	
	if (opmVersion == null || opmVersion == "") {		
		document.getElementById("opmVerError").innerHTML = "OPM Version must not be empty";				
         flag4 = 'true';	
    }else if(!opmVersion.match(/^[0-9a-zA-Z.]+$/)){ 		
		document.getElementById("opmVerError").innerHTML = "OPM Version must not contain any special characters except dot(.)";				
         flag4 = 'true';	
    }else{document.getElementById("opmVerError").innerHTML = "";}
	
	if (minversion == null || minversion == "") {	
		document.getElementById("minOntapError").innerHTML = "Min ONTAP Version must not be empty";				
         flag5 = 'true';	
    }else if(!minversion.match(/^[0-9a-zA-Z.]+$/)){ 		
		document.getElementById("minOntapError").innerHTML = "Min ONTAP version must not contain any special characters except dot(.)";				
         flag5 = 'true';	
    }else{document.getElementById("minOntapError").innerHTML = "";}
	
	if (authorName == null || authorName == "") {	
		document.getElementById("authorNameError").innerHTML = "Author name must not be empty";				
         flag6 = 'true';	
    }else if(!authorName.match(/^[ a-zA-Z-_]+$/)){		
		document.getElementById("authorNameError").innerHTML = "Author Name must contain only characters";		
         flag6 = 'true';	
    }else{document.getElementById("authorNameError").innerHTML = "";}		
	
	if(flag0 == 'true' || flag1 == 'true' || flag2 == 'true' || flag3 == 'true' || flag4 == 'true' || flag5 == 'true' || flag6 == 'true'){		
	document.upload_opm.zip_file.focus();
		return false;
	}else{
		return true;	
	}		
}


function submitUserProfile()
{
	var phone = document.forms["user_profile_confirm"]["phone"].value;
    if (phone == null || phone == "") {	
		document.getElementById("phoneError").innerHTML = "Phone number is required";		
		document.upload_ocum.ocum_name.focus() ;		
        return false;
	}else if(!phone.match(/^[0-9]{10,14}$/)){		
		document.getElementById("phoneError").innerHTML = "Phone number is invalid (10-14 digits)";		
		document.user_profile_confirm.phone.focus() ;		
        return false;
    }
    else
    {
    	return true;
    }
	//return true;
}


/* grievance reporting */
function submitFlagForm(){
	var desc = trim(document.forms["grievanceReport"]["flagComment"].value);	
	if (desc == null || desc == "") {	
		document.getElementById("comment_error").innerHTML = "Comment must not be empty";		
		document.grievanceReport.flagComment.focus() ;		
        return false;
    }else{document.getElementById("comment_error").innerHTML = "";}
	return true;
}

/***** Upload WFA form validations *****/        
function submitWfa(formObj)
{  	 
	divs = document.getElementsByClassName( 'form-error' );
	[].slice.call( divs ).forEach(function ( div ) { 
		div.innerHTML = '';
	});  
	
	var flag0, flag1, flag2, flag3, flag4, flag5, flag6, flag7, flag8, flag9;  
	
//	var filename				= formObj.zip_file.value;
	var wfa_name				= trim(formObj.wfa_name.value); 
	var wfa_desc 	  			= trim(formObj.wfa_desc.value);
//	var wfa_help_doc			= formObj.wfa_help_doc.value;
	var wfa_pack_version		= trim(formObj.wfa_pack_version.value);
	var wfa_min_soft_version 	= trim(formObj.wfa_min_soft_version.value);
	var wfa_max_soft_version 	= trim(formObj.wfa_max_soft_version.value);
	var wfa_version_changes		= trim(formObj.wfa_version_changes.value);  
	var wfa_version				= trim(formObj.wfa_version.value);
	var wfa_contact_name		= trim(formObj.wfa_contact_name.value);
	var wfa_contact_email		= formObj.wfa_contact_email.value;
	var wfa_contact_phone		= trim(formObj.wfa_contact_phone.value);
	var wfa_cummunity_link		= trim(formObj.wfa_cummunity_link.value);	
	
/* 	if (filename == null || filename == "") {	
		document.getElementById("fileError").innerHTML = "No file selected to upload";		
		formObj.zip_file.focus() ;		
        return false;
    }else if(wfaValidateExtension() == false){	
		document.getElementById("fileError").innerHTML = "Please select only .zip or .dar files to upload";	
		formObj.zip_file.focus() ;		
        return false;
	}else{document.getElementById("fileError").innerHTML = "";} */		
	
	if (wfa_name == null || wfa_name == "") {	
		document.getElementById("wfaNameError").innerHTML = "Pack name must not be empty";		
		flag0 = 'true'; 
    }else{document.getElementById("wfaNameError").innerHTML = "";}
	
	if (wfa_desc == null || wfa_desc == "") {	
		document.getElementById("wfaDescError").innerHTML = "Pack description must not be empty";		
		flag1 = 'true'; 
    }else{document.getElementById("wfaDescError").innerHTML = "";}
	
/* 	
	if (wfa_help_doc == null || wfa_help_doc == "") { 	
		document.getElementById("wfaHelpDocError").innerHTML = "Please provide the help document text or upload the html file.";		
		formObj.wfa_help_doc.focus() ;	   	
        return false;
    }else{document.getElementById("wfaHelpDocError").innerHTML = "";} 
	
*/
	
	// upload help doc html 
	
/* 	if(wfaHelpDocValidateExtension() == false){	 
		document.getElementById("wfaHelpDocHtmlError").innerHTML = "Please select only .html or .htm files to upload";	
		formObj.wfa_help_doc_html.focus() ;		
        return false; 
	}else{document.getElementById("wfaHelpDocHtmlError").innerHTML = "";}  */
	
	// wfa pack version
	
/* 	if (wfa_pack_version == null || wfa_pack_version == "") {	
		document.getElementById("wfaPackVersionError").innerHTML = "Version must not be empty";		
		formObj.wfa_pack_version.focus() ;		
        return false;
    }else if(!wfa_pack_version.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("wfaPackVersionError").innerHTML = "Version must not contain any special characters except dot(.)";		
		formObj.wfa_pack_version.focus() ;		
        return false;
    }else{document.getElementById("wfaPackVersionError").innerHTML = "";} */
	
/* 	if (wfa_version_changes == null || wfa_version_changes == "") {	
		document.getElementById("wfaVersionChangesError").innerHTML = "Pack version changes must not be empty";		
		flag2 = 'true'; 
    }else{document.getElementById("wfaVersionChangesError").innerHTML = "";} */ 
	
	// wfa version	
	
	if (wfa_version == null || wfa_version == "") {	
		document.getElementById("wfaVersionError").innerHTML = "WFA version must not be empty";		
		flag3 = 'true'; 
    }else if(!wfa_version.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("wfaVersionError").innerHTML = "WFA version must contain numeric including dot(.)";		
		flag3 = 'true'; 
    }else{document.getElementById("wfaVersionError").innerHTML = "";}	

	
	if (wfa_min_soft_version == null || wfa_min_soft_version == "") {	
		document.getElementById("wfaMinSoftVersionError").innerHTML = "Please select a value for Windows Compatibility";
		flag4 = 'true';	
    }else{document.getElementById("wfaMinSoftVersionError").innerHTML = "";} 
	
	if (wfa_max_soft_version == null || wfa_max_soft_version == "") {	
		document.getElementById("wfaMaxSoftVersionError").innerHTML = "Please select a value for Linux Compatibility";				
		flag5 = 'true';	
    }else{document.getElementById("wfaMaxSoftVersionError").innerHTML = "";}  
	
	
	if (wfa_contact_name == null || wfa_contact_name == "") {	
		document.getElementById("wfaContactNameError").innerHTML = "Author name must not be empty";		
		flag6 = 'true';
    }else if(!wfa_contact_name.match(/^[a-zA-Z]+[ a-zA-Z-_]+$/)){
		document.getElementById("wfaContactNameError").innerHTML = "Author Name must contain only characters";		
		flag6 = 'true';
    }else{document.getElementById("wfaContactNameError").innerHTML = "";}
	
	/* if (wfa_contact_email == null || wfa_contact_email == "") {	
		document.getElementById("wfaContactEmailError").innerHTML = "Author email must not be empty";		
		flag7 = 'true';
    }else  */ 
	if(wfa_contact_email != '') 
	{	
		if(ValidateEmail(wfa_contact_email) == false){
			document.getElementById("wfaContactEmailError").innerHTML = "Invalid format of email";
			flag7 = 'true';
		}else{document.getElementById("wfaContactEmailError").innerHTML = "";}
	}
	
/* 	if(wfa_contact_phone != '')   
	{	
   
		if(!wfa_contact_phone.match(/^[0-9]{10,14}$/)){		
			document.getElementById("wfaContactPhoneError").innerHTML = "Phone number is invalid (10-14 digits)";		
			flag8 = 'true';
		}
		else
		{
			document.getElementById("wfaContactPhoneError").innerHTML = "";
		}
	} */
	
	if (wfa_contact_phone == null || wfa_contact_phone == "") {	
		document.getElementById("wfaContactPhoneError").innerHTML = "Author phone must not be empty";		
		flag8 = 'true';
	}else{document.getElementById("wfaContactPhoneError").innerHTML = "";}
	
	if (wfa_cummunity_link == null || wfa_cummunity_link == "") {	
		document.getElementById("wfaCommunityLinkError").innerHTML = "Community link must not be empty";		
		flag9 = 'true';
	}else{document.getElementById("wfaCommunityLinkError").innerHTML = "";}
	
	if(flag0 == 'true' || flag1 == 'true' || flag2 == 'true' || flag3 == 'true' || flag4 == 'true' || flag5 == 'true' || flag6 == 'true' || flag7 == 'true' || flag8 == 'true' || flag9 == 'true'){
		formObj.wfa_name.focus() ;
		return false;
	}else{
		return true;	
	}
	
}	


/***** Upload Snap form validations *****/        
function submitSnap(formObj)
{  	 
	divs = document.getElementsByClassName( 'form-error' );
	[].slice.call( divs ).forEach(function ( div ) { 
		div.innerHTML = '';
	});  
	
	var flag0, flag1, flag2, flag3, flag4, flag5, flag6, flag7, flag8, flag9;  
	
	var wfa_name				= trim(formObj.wfa_name.value); 
	var wfa_desc 	  			= trim(formObj.wfa_desc.value);
	var wfa_pack_version		= trim(formObj.wfa_pack_version.value);
	var wfa_min_soft_version 	= trim(formObj.wfa_min_soft_version.value);
	var wfa_max_soft_version 	= trim(formObj.wfa_max_soft_version.value);
	var wfa_version_changes		= trim(formObj.wfa_version_changes.value);  
	var wfa_version				= trim(formObj.wfa_version.value);
	var plugin_type				= trim(formObj.plugin_type.value);
	var wfa_contact_name		= trim(formObj.wfa_contact_name.value);
	var wfa_contact_email		= formObj.wfa_contact_email.value;
	var wfa_contact_phone		= trim(formObj.wfa_contact_phone.value);
	var wfa_cummunity_link		= trim(formObj.wfa_cummunity_link.value);	
	
	if (wfa_name == null || wfa_name == "") {	
		document.getElementById("wfaNameError").innerHTML = "Pack name must not be empty";		
		flag0 = 'true'; 
    }else{document.getElementById("wfaNameError").innerHTML = "";}
	
	if (wfa_desc == null || wfa_desc == "") {	
		document.getElementById("wfaDescError").innerHTML = "Pack description must not be empty";		
		flag1 = 'true'; 
    }else{document.getElementById("wfaDescError").innerHTML = "";}	
	// wfa version	
	
	if (wfa_version == null || wfa_version == "") {	
		document.getElementById("wfaVersionError").innerHTML = "Snap version must not be empty";		
		flag3 = 'true'; 
    }else if(!wfa_version.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("wfaVersionError").innerHTML = "Snap version must contain numeric including dot(.)";		
		flag3 = 'true'; 
    }else{document.getElementById("wfaVersionError").innerHTML = "";}	


	if (plugin_type == null || plugin_type == "") {	
		document.getElementById("plugin_typeError").innerHTML = "Plugin type must not be empty";		
		flag3 = 'true'; 
    }else if(!plugin_type.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("plugin_typeError").innerHTML = "Plugin type must contain numeric including dot(.)";		
		flag3 = 'true'; 
    }else{document.getElementById("plugin_typeError").innerHTML = "";}	
	
	if (wfa_min_soft_version == null || wfa_min_soft_version == "") {	
		document.getElementById("wfaMinSoftVersionError").innerHTML = "Please select a value for Windows Compatibility";
		flag4 = 'true';	
    }else{document.getElementById("wfaMinSoftVersionError").innerHTML = "";} 
	
	if (wfa_max_soft_version == null || wfa_max_soft_version == "") {	
		document.getElementById("wfaMaxSoftVersionError").innerHTML = "Please select a value for Linux Compatibility";				
		flag5 = 'true';	
    }else{document.getElementById("wfaMaxSoftVersionError").innerHTML = "";}  
	
	
	if (wfa_contact_name == null || wfa_contact_name == "") {	
		document.getElementById("wfaContactNameError").innerHTML = "Author name must not be empty";		
		flag6 = 'true';
    }else if(!wfa_contact_name.match(/^[a-zA-Z]+[ a-zA-Z-_]+$/)){
		document.getElementById("wfaContactNameError").innerHTML = "Author Name must contain only characters";		
		flag6 = 'true';
    }else{document.getElementById("wfaContactNameError").innerHTML = "";}
	
	if(wfa_contact_email != '') 
	{	
		if(ValidateEmail(wfa_contact_email) == false){
			document.getElementById("wfaContactEmailError").innerHTML = "Invalid format of email";
			flag7 = 'true';
		}else{document.getElementById("wfaContactEmailError").innerHTML = "";}
	}
	
	if (wfa_contact_phone == null || wfa_contact_phone == "") {	
		document.getElementById("wfaContactPhoneError").innerHTML = "Author phone must not be empty";		
		flag8 = 'true';
	}else{document.getElementById("wfaContactPhoneError").innerHTML = "";}
	
	if (wfa_cummunity_link == null || wfa_cummunity_link == "") {	
		document.getElementById("wfaCommunityLinkError").innerHTML = "Community link must not be empty";		
		flag9 = 'true';
	}else{document.getElementById("wfaCommunityLinkError").innerHTML = "";}
	
	if(flag0 == 'true' || flag1 == 'true' || flag2 == 'true' || flag3 == 'true' || flag4 == 'true' || flag5 == 'true' || flag6 == 'true' || flag7 == 'true' || flag8 == 'true' || flag9 == 'true'){
		formObj.wfa_name.focus() ;
		return false;
	}else{
		return true;	
	}
	
}

function submitStoredWfa(formObj) 
{  	 
	divs = document.getElementsByClassName( 'form-error' );
	[].slice.call( divs ).forEach(function ( div ) { 
		div.innerHTML = '';
	});  
	
	var flag0, flag1, flag2, flag3, flag4, flag5, flag6, flag7, flag8, flag9;
	
//	var filename			= formObj.zip_file.value;
	var wfa_name			= trim(formObj.wfa_name.value);
	var wfa_desc 	  		= trim(formObj.wfa_desc.value);
//	var wfa_help_doc		= formObj.wfa_help_doc.value;	
//	var wfa_pack_version	= trim(formObj.wfa_pack_version.value);
	
	var wfa_min_soft_version 	= trim(formObj.wfa_min_soft_version.value);	
	var wfa_max_soft_version 	= trim(formObj.wfa_max_soft_version.value);
	
	var wfa_version_changes	= trim(formObj.wfa_version_changes.value);           
	var wfa_version			= trim(formObj.wfa_version.value);
	var wfa_contact_name	= trim(formObj.wfa_contact_name.value);
	var wfa_contact_email	= formObj.wfa_contact_email.value;
	var wfa_contact_phone	= trim(formObj.wfa_contact_phone.value);    
	var wfa_cummunity_link	= trim(formObj.wfa_cummunity_link.value); 
	
/* 	if (filename == null || filename == "") {	
		document.getElementById("fileError").innerHTML = "No file selected to upload";		
		formObj.zip_file.focus() ;		
        return false;
    }else if(wfaValidateExtension() == false){	
		document.getElementById("fileError").innerHTML = "Please select only .zip or .dar files to upload";	
		formObj.zip_file.focus() ;		
        return false;
	}else{document.getElementById("fileError").innerHTML = "";} */
	
/* 	
	if (wfa_help_doc == null || wfa_help_doc == "") { 	
		document.getElementById("wfaHelpDocError").innerHTML = "Please provide the help document text or upload the html file.";		
		formObj.wfa_help_doc.focus() ;	   	
        return false;
    }else{document.getElementById("wfaHelpDocError").innerHTML = "";} 
	
*/
	
	// upload help doc html 
	
/* 	if(wfaHelpDocValidateExtension() == false){	 
		document.getElementById("wfaHelpDocHtmlError").innerHTML = "Please select only .html or .htm files to upload";	
		formObj.wfa_help_doc_html.focus() ;		
        return false; 
	}else{document.getElementById("wfaHelpDocHtmlError").innerHTML = "";}  
	
	// wfa pack version
	
	if (wfa_pack_version == null || wfa_pack_version == "") {	
		document.getElementById("wfaPackVersionError").innerHTML = "Version must not be empty";		
		formObj.wfa_pack_version.focus() ;		
        return false;
    }else if(!wfa_pack_version.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("wfaPackVersionError").innerHTML = "version must contain numeric including dot(.)";		
		formObj.wfa_pack_version.focus() ;		
        return false;
    }else{document.getElementById("wfaPackVersionError").innerHTML = "";}
*/
	if (wfa_name == null || wfa_name == "") {	
		document.getElementById("wfaNameError").innerHTML = "Pack name must not be empty";				
		flag0 = 'true';	
    }else{document.getElementById("wfaNameError").innerHTML = "";}
	
	if (wfa_desc == null || wfa_desc == "") {	
		document.getElementById("wfaDescError").innerHTML = "Pack description must not be empty";		
		flag1 = 'true';	
    }else{document.getElementById("wfaDescError").innerHTML = "";}
	
	if (wfa_version_changes == null || wfa_version_changes == "") {	
		document.getElementById("wfaVersionChangesError").innerHTML = "Pack version changes must not be empty";		
		flag2 = 'true';	
    }else{document.getElementById("wfaVersionChangesError").innerHTML = "";}
	
	// wfa version	  
	
	if(!wfa_version.match(/^[0-9a-zA-Z.]+$/)){		
		document.getElementById("wfaVersionError").innerHTML = "WFA version must contain numeric including dot(.)";	 	
		flag3 = 'true';	
    }	

	if (wfa_min_soft_version == null || wfa_min_soft_version == "") {	
		document.getElementById("wfaMinSoftVersionError").innerHTML = "Please select a value for Windows Compatibility";
		flag4 = 'true';	
    }else{document.getElementById("wfaMinSoftVersionError").innerHTML = "";}  
	
	
	if (wfa_max_soft_version == null || wfa_max_soft_version == "") {	  
		document.getElementById("wfaMaxSoftVersionError").innerHTML = "Please select a value for Linux Compatibility";		
		flag5 = 'true';	
    }else{document.getElementById("wfaMaxSoftVersionError").innerHTML = "";}   
	
	
	if (wfa_contact_name == null || wfa_contact_name == "") {	
		document.getElementById("wfaContactNameError").innerHTML = "Author name must not be empty";		
		flag6 = 'true';
    }else if(!wfa_contact_name.match(/^[a-zA-Z]+[ a-zA-Z-_]+$/)){
		document.getElementById("wfaContactNameError").innerHTML = "Author Name must contain only characters";    		
		flag6 = 'true';
    }else{document.getElementById("wfaContactNameError").innerHTML = "";}
	
/* 	if (wfa_contact_email == null || wfa_contact_email == "") {	
		document.getElementById("wfaContactEmailError").innerHTML = "Author email must not be empty";		
		flag7 = 'true';
    }else  */
	if(wfa_contact_email != '') 
	{	
		if(ValidateEmail(wfa_contact_email) == false){
			document.getElementById("wfaContactEmailError").innerHTML = "Invalid format of email";
			flag7 = 'true';
		}else{document.getElementById("wfaContactEmailError").innerHTML = "";}
	}
	
	if (wfa_contact_phone == "") {	  
		document.getElementById("wfaContactPhoneError").innerHTML = "Author phone must not be empty";		
		flag8 = 'true';
	}else{document.getElementById("wfaContactPhoneError").innerHTML = "";}
	
	if (wfa_cummunity_link == null || wfa_cummunity_link == "") {	
		document.getElementById("wfaCommunityLinkError").innerHTML = "Community link must not be empty";		
		flag9 = 'true';
	}else{document.getElementById("wfaCommunityLinkError").innerHTML = "";}
	
	if(flag0 == 'true' || flag1 == 'true' || flag2 == 'true' || flag3 == 'true' || flag4 == 'true' || flag5 == 'true' || flag6 == 'true' || flag7 == 'true' || flag8 == 'true' || flag9 == 'true'){   
		formObj.wfa_name.focus() ;
		return false;
	}else{
		return true;	
	}	
	
}

/*** Star rating Start ***/
 
   function change(id) {
      var cname = document.getElementById(id).className;
      var ab = document.getElementById(id+"_hidden").value;			
      document.getElementById(cname+"rating").value = ab;	  
      for(var i=ab; i>=1; i--) {
         document.getElementById(cname+i).src = "images/star-fill-icon.png";
      }
      		if(parseInt(ab) == 1){
			var id = parseInt(ab);	
		}else{
			var id = parseInt(ab)+1;
		}
      for(var j=id; j<=5; j++){
         document.getElementById(cname+j).src = "images/star-blank-icon.png";
      }	  
   }

/*** Star rating End ***/
/*** deprecation of pack by admin START ***/
function submitDepForm(){
	var desc = trim(document.forms["packDeprecation"]["depComment"].value);	
	if (desc == null || desc == "") {	
		document.getElementById("depComment_error").innerHTML = "Comment must not be empty";		
		document.packDeprecation.depComment.focus() ;		
        return false;
    }else{document.getElementById("depComment_error").innerHTML = "";}	
	return true;
}
/*** deprecation of pack by admin END ***/