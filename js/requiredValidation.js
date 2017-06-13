
//For Selecting/ deselecting check boxed
	var marked_row = new Array;
	
	function clearerror()
	{
	return true;
	}
	window.onerror=clearerror;
	
	function numbersonly(e) {
		var unicode=e.charCode? e.charCode : e.keyCode
		if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
			if (unicode<48||unicode>57) //if not a number
				return false //disable key press
		}
	}

	function nameonly(e) {
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
			if ((unicode >= 48 && unicode <= 57) || (unicode >= 65 && unicode <= 90) || (unicode >= 97 && unicode <= 122) || unicode == 32) //if not a number
				return true 
			else				
				return false 
		}
	}

	function resetJS()
	{
		if(document.getElementById('sp_err')) { 
			var t = document.getElementById('sp_err');
			t.parentNode.removeChild(t);
		}
	}
	
	function selectDeselect(field, isCheck)
	 {
		var boxes = document.getElementsByName(field);
		var boxes_checked = anyChecked();
		if(isCheck)
		{
		   if(document.getElementsByName(isCheck)[0].checked) setChecks(true);
			else setChecks(false);
		}
		else
		{
			if(!boxes_checked) setChecks(true);
			else setChecks(false);
		}	

		function setChecks( setting ) 
		{
			for( var j=0; j < boxes.length; j++ ) 
			{
			   boxes[ j ].checked = setting;
			  theObjects = document.getElementsByTagName("tr");
			   if(setting==true)
			  		 {
						for (var i = 0; i < theObjects.length; i++)
						{
							if(theObjects[i].id.indexOf('_') != -1) 
							{
							theObjects[i].className = 'over';
							}							  
						} 
					     
			    	 }
			    	 else
			    	 {
						for (var i = 0; i < theObjects.length; i++)
						{
							if(theObjects[i].id.indexOf('0_') != -1) 
							{
							theObjects[i].className = 'evenTr';
							}
							else if(theObjects[i].id.indexOf('1_') != -1) 
							{
							  theObjects[i].className = 'oddTr';
							}
							  
						} 
			   		 }
			}
		}
		
		function anyChecked()
		 {
			for( var i=0; i < boxes.length; i++ ) 
			{
				if( boxes[i].checked == true) 
				{
					return (true);
				} 
			}
			return (false);
		}
	}
	
	function checkany(field, message)
	{
		var boxes = document.getElementsByName(field);
		var bol = anyChecked(boxes);
		if(bol == false) {
			//alert(message);
			return true;
		}
		else
			return false;
	}

	//To check wheather user have selected box or not
	function anyChecked(boxes) 
	{ 
		for( var i=0; i < boxes.length; i++ ) {
			if( boxes[i].checked == true) {
				return (true);
			} 
		}
		return (false);
	}

	//For checking Null values
	function isNull(aStr)
	{
		var index;
		for (index=0; index < aStr.length; index++)
			if (aStr.charAt(index) != ' ')
				return false;
		return true;
	}	

	//For checking invalid E-Mail address
	function isEmail(aStr)
	{
		var reEmail=/^[0-9a-zA-Z_\.-]+\@[0-9a-zA-Z_\.-]+\.[0-9a-zA-Z_\.-]+$/;
		if(!reEmail.test(aStr)) {
			return false;
		}
		return true;
	}

	//Removing the newline character
	function countChars(str)
	{
		var reg = new RegExp("[\f\n\r\v]*","g");
		str = str.replace(reg,"");
		return str.length;   
	}
    function isNum12(aStr)
	{
		//alert(aStr);
	   
		var reNum=/^[0-9.]+$/;
		if(!reNum.test(aStr)) {
			document.getElementById('m__Product_Price').value = '';
			document.getElementById('price').innerHTML = 'Please Enter Numeric Value.';
			return false;
		}else{
		    document.getElementById('price').innerHTML = '';
			return true;
		}
		
	}
	
	
	//For checking invalid Numaric
	function isNum(aStr)
	{
		//alert(aStr);
	   
		var reNum=/^[0-9.]+$/;
		if(!reNum.test(aStr)) {
			return false;
		}
		return true;
	}

	function chknewslatter()
	{
		if(!isEmail(document.subscription.email_add.value)) {
			alert("Please enter valid Email Address.");
			document.subscription.email_add.focus();
			return (false);
		}
		return (true);
	}

	//For checking invalid AlphaNumaric
	function isAlphaNumaric(aStr){
		var reNum=/^[0-9.a-zA-Z_-]+$/;
		if(!reNum.test(aStr)) {
			return false;
		}
		return true;
	}

	//	Start: is valid uszip code
	function isZip(str)	{
		if (str.indexOf("-",0) > 0)  var t = /^\d{5}-\d{4}$/
		else var t = /^\d{5}$/
		return t.test(str)
	}

	//	Start: is valid URL
	function isURL(argvalue) {
	  if (argvalue.indexOf(" ") != -1)
		return false;
	  else if (argvalue.indexOf("http://") == -1 || argvalue.indexOf("https://") == -1)
		return false;
	  else if (argvalue == "http://")
		return false;
	  else if (argvalue.indexOf("http://") > 0 || argvalue.indexOf("https://") > 0 )
		return false;
	  argvalue = argvalue.substring(7, argvalue.length);
	  if (argvalue.indexOf(".") == -1)
		return false;
	  else if (argvalue.indexOf(".") == 0)
		return false;
	  else if (argvalue.charAt(argvalue.length - 1) == ".")
		return false;
	  if (argvalue.indexOf("/") != -1) {
		argvalue = argvalue.substring(0, argvalue.indexOf("/"));
		if (argvalue.charAt(argvalue.length - 1) == ".")
		  return false;
	  }	

	  if (argvalue.indexOf(":") != -1) {
		if (argvalue.indexOf(":") == (argvalue.length - 1))
		  return false;
		else if (argvalue.charAt(argvalue.indexOf(":") + 1) == ".")
		  return false;
		argvalue = argvalue.substring(0, argvalue.indexOf(":"));
		if (argvalue.charAt(argvalue.length - 1) == ".")
		  return false;
	  }
  return true;
}

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
function isValidDate(dateStr) {
// Checks for the following valid date formats:
// MM/DD/YY   MM/DD/YYYY   MM-DD-YY   MM-DD-YYYY
// Also separates date into month, day, and year variables
var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{2}|\d{4})$/;

// To require a 4 digit year entry, use this line instead:
// var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/;

var matchArray = dateStr.match(datePat); // is the format ok?
if (matchArray == null) {
alert("Date is not in a valid format.")
return true;
}
month = matchArray[1]; // parse date into variables
day = matchArray[3];
year = matchArray[4];
if (month < 1 || month > 12) { // check month range
alert("Month must be between 1 and 12.");
return true;
}
if (day < 1 || day > 31) {
alert("Day must be between 1 and 31.");
return true;
}
if ((month==4 || month==6 || month==9 || month==11) && day==31) {
alert("Month "+month+" doesn't have 31 days!")
return true
}
if (month == 2) { // check for february 29th
var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
if (day>29 || (day==29 && !isleap)) {
alert("February " + year + " doesn't have " + day + " days!");
return true;
   }
}
return false;  // date is valid
}
//  End -->
/***********************************************************************************/
	function invalidLength(field, message, intMin, intMax)
	{
		if(countChars(field.value) < intMin || countChars(field.value) > intMax) {
			 addMessage(field, message+intMin+" to "+intMax);
			return true;
		}
		return false;
	 }
	function blankField(field, message)
	{   
	    var html = field.value;
	    var stripped = html.replace(/(<([^>]+)>)/ig,""); 
	    var stripped = stripped.replace(/[#$%?\\*\\&^!@|']/ig,""); 
		
		if(isNull(leftTrim(stripped)) || leftTrim(stripped)=="") 
		{
		   var inputId=field;
		   addMessage(field, message);
		   return true;
		}
		return false;
	 }

/***************************************************************/

	function delConfiram()
	{	
		if(checkany('delete[]', 'Please select atleast one record to delete.'))
			return false;
		else if(confirm('Are you sure you want to delete the selected record(s)?'))
			return true;			
		else
			return false;
	}
	
	function delConfiramVenue()
	{	
		if(checkany('delete[]', 'Please select atleast one record to delete.'))
			return false;
		else if(confirm('All events of selected venue(s) will be deleted permanently. \n\n Are you sure you want to delete the selected record(s)?'))
			return true;			
		else
			return false;
	}
	
	function selectall()
	{
		selectDeselect('delete[]', 'sel_del');
	
	}
	function validateFrm(tmpVar)
	{			
		with(tmpVar)
		{
			for(i = 0; i < elements.length; ++i)
			{ 
				field = elements[i];				
				var strMsg, strArgvalue;
				myString = new String(field.id);					
				if(myString.substring(0, 3) == 'm__') 
				{
					var strMessage = '';
					strArgvalue = myString.substring(3, myString.length);
					strMsg = strArgvalue.replace(/_/g, ' ');
					
									
					if(field.type == 'select-one') {
						strMessage = 'Please select '+strMsg+'.';
					}
					else if(field.type == 'file') {
						strMessage = 'Please browse the '+strMsg+'.';
					} if(field.name.indexOf("date") != -1) {
						strMessage = 'Please select the '+strMsg+'.';
					}
					else {
						strMessage = 'Please enter the '+strMsg+'.';
					}
					if(blankField(field, strMessage))
						return false;
						
					if(field.type == 'file' && field.name == "db_csvfile") 
					{ 
						if(isCSV(field, 'Please browse only CSV file.'))
						{
							return false;
						}
					}if(field.type == 'file' && field.name == "db_xlsfile") 
					{ 
						if(iSXLS(field, 'Please browse only XLS file.'))
						{
							return false;
						}
					}	
				} 
				
				if(field.type == 'file' && field.name != "upload" && field.name != "db_xlsfile" && field.name != "db_audiofile" && field.name != "db_csvfile" && field.value!='' && field.name != "SITE_FAVICON" && field.name != "vid_file") 
				{ 
					if(invalidFileFormat(field, 'Please upload only gif, png and jpg.'))
					{
						return false;
					}
				}
				if(field.type == 'file'  && field.name == "SITE_FAVICON") 
				{ 
					if(invalidFileFormatICO(field, 'Please upload only .ico file.'))
					{
						return false;
					}
				}
				if(field.type == 'file'  && field.name == "vid_file") 
				{ 
					if(invalidVideoFileFormat(field, 'Invalid File Type Please Try Again. You file must be of type .mpg, .wma, .mov, .flv, .mp4, .avi, .qt, .wmv, .rm'))
					{
						return false;
					}
				}
						
				if(field.name.indexOf("email") != -1 && field.value!='') 
				{
					if(invalidEmail(field, 'Please enter valid email format.'))
					{
						return false;
					}
				}
				
				if(field.name.indexOf("phoneNo") != -1 && field.value!="") 
				{
					if(checkInternationalPhone(field, 'Plaese enter valid phone number.'))
					{
						return false;
					}
				}
				
				if(field.name.indexOf("chk_terms")!=-1 && field.checked==false)
				{
					  addMessageCMS(field, 'Please check terms & conditions.');
					  return false;
				}
			}
		}		
		return true;;			
	}

	 function addMessage(field1, message)
	 {		
		var str = field1.parentNode.innerHTML;
		var field = field1;
		var pnode = field1.parentNode;
		if(document.getElementById('sp_err'))
		{ 
			var t = document.getElementById('sp_err');
			t.parentNode.removeChild(t);
		}		
		var div = document.createElement("div");
		div.setAttribute('id','sp_err');
		div.innerHTML = "<span style='color:#FF0000;font-size:12px;padding-left:150px;'>"+message+"</span>";
		field1.parentNode.appendChild(div);
		field.focus();
	 }

/***************************************************************/

	  function addMessageCMS(field1,message)
	 {
	     var str=field1.parentNode.innerHTML;
		  var pnode=field1.parentNode;
		    if(document.getElementById('sp_err'))
		   { 
		    var t = document.getElementById('sp_err');
			t.parentNode.removeChild(t);
			}
			
			var div=document.createElement("div");
			div.setAttribute('id','sp_err');
			div.innerHTML="<span style='color:#FF0000;font-family:verdana;font-size:11px;font-weight:bold;'>"+message+"</span>";
			//var txt = document.createElement('<div id="sp_err" style="font-family:verdana;font-size:11px;color:#FF0000;font-weight:bold;">'+message+'</div>');
			//field1.parentNode.appendChild('<div id="sp_err" style="font-family:verdana;font-size:11px;color:#FF0000;font-weight:bold;">'+message+'</div>');
		   field1.parentNode.appendChild(div);
		   //pnode.firstChild.focus();
	 }
	/*
	 Function to check CMS Value is Null
	*/
	function blankCMS(field, message)
	{
		var html=field.value;
		var stripped = html.replace(/(<([^>]+)>)/ig,""); 
		
		if(isNull(LTrim(stripped))) {
			addMessageCMS(field, message);
			return true;
		}
		string=validCMS(stripped);
		if(leftTrim(string) == "") {
			addMessageCMS(field, message);
			return true; 
		}
		return false;
	}
	 
	function invalidEmail(field, message)
	{
		if(isEmail1(field.value) == false) 
		{
		   addMessage(field, message);
			field.focus();
			return true;
		}
		else if(isEmail1(field.value) == 3) 
		{
			alert(VALID_CHAR_EMAIL);
			field.focus();
			return true;
		}
		return false;
	 }
	 
	function equalField(field1, field2, message)
	{
		if(field1.value != field2.value) {
			addMessage(field2, message);
			return true;
		}
		return false;
	}
	 
	function dateCompare(field1, field2, message)
	{		
		d1 = new Date(field1.value);
		d2 = new Date(field2.value);
		if (d1 > d2) {
			addMessage(field2, message);
			return true;
		}
		return false;
	}
	 
	function invalidDate(field1, field2, field3)
	{	
		if (isValidDate(field1.value+"/"+field2.value+"/"+field3.value) == false){
		 addMessage(field1, message);
			return true;
		}
	}
	 
	function invalidEmailList(field, message)
	{
		var b = field.value;
		var temp = new Array();
		temp = b.split(',');
		for(var i = 0; i < temp.length; i++)
		{
			if(!isEmail(temp[i])) {
				alert(message);
				field.focus();
				return true;
			}
		}
		return false;
	}
	
	function invalidAvailableUsername(field, message)
	{
		if(isNull(field.value)) {
			alert(message);
			field.focus();
			return true;
		}
		return false;
	 }
	 
	function invalidUrl(field, message)
	{	
		if (!isVUrl(field.value)) {
			addMessage(field, message);
			return true;
		}
	}
	 
	function invalidNumber(field, message)
	{	
		if (!isNum(field.value)) {
			addMessage(field, message);
			return true;
		}
	}
	 
	function invalidAlphaNumaric(field, message)
	{	
		if (!isAlphaNumaric(field.value)){
			addMessage(field, message);
			return true;
		}
	}
	 
	function invalidFileFormat(field, message)
	{
		if (field.value != "") {
			myString = new String(field.value);					
			start=myString.lastIndexOf(".");
			argvalue = myString.substring(start, myString.length);		
			if(argvalue.toLowerCase() != ".gif" && argvalue.toLowerCase() != ".png" && argvalue.toLowerCase() != ".jpg" && argvalue.toLowerCase() != ".mp3" && argvalue.toLowerCase() != ".jpeg" && argvalue.toLowerCase() != ".pdf" ) {
				addMessage(field, message);	
				return true;					
			}					
		}
	}
	
	function invalidFileFormatICO(field, message)
	{
		if (field.value != "") {
			myString = new String(field.value);					
			start=myString.lastIndexOf(".");
			argvalue = myString.substring(start, myString.length);		
			if(argvalue.toLowerCase() != ".ico" ) {
				addMessage(field, message);	
				return true;					
			}					
		}
	}
	
	function invalidVideoFileFormat(field, message)
	{
		if (field.value != "") {
			myString = new String(field.value);					
			start=myString.lastIndexOf(".");
			argvalue = myString.substring(start, myString.length);				
			if(argvalue.toLowerCase() != ".mpg" && argvalue.toLowerCase() != ".wma" && argvalue.toLowerCase() != ".mov" && argvalue.toLowerCase() != ".flv" && argvalue.toLowerCase() != ".mp4" && argvalue.toLowerCase() != ".avi" && argvalue.toLowerCase() != ".qt" && argvalue.toLowerCase() != ".wmv" && argvalue.toLowerCase() != ".rm"  ) {
				addMessage(field, message);	
				return true;					
			}					
		}
	}
	function isCSV(field, message)
	{
		if (field.value != "") {
			myString = new String(field.value);					
			start=myString.lastIndexOf(".");
			argvalue = myString.substring(start, myString.length);		
			if(argvalue.toLowerCase() != ".csv") {
				addMessage(field, message);	
				return true;					
			}					
		}
	}

	
	function ISPDF(field, message)
	{
		myString = new String(field.value);					
		start=myString.lastIndexOf(".");
		argvalue = myString.substring(start+1, myString.length);
		if (argvalue.toLowerCase()!= "pdf" ) {
			addMessage(field, message);
			return true;					
		}
	}
	
	function iSXLS(field, message)
	{
		myString = new String(field.value);					
		start=myString.lastIndexOf(".");
		argvalue = myString.substring(myString.length-3, myString.length);
		
		if (argvalue.toLowerCase() != "xls" ) {	
			addMessage(field, message);	
			return true;					
		}
	}
	
	
	function ISDOC(field, message)
	{
		if(field.value!= "") {
			 myString = new String(field.value);					
			 start=myString.lastIndexOf(".");
			 argvalue = myString.substring(start, myString.length);		
			if (argvalue.toLowerCase()!= "doc" ) {
				addMessage(field, message);
				return true;					
			}					
		}
	}
	
	function IsFile(field, message,extension)
	{
		
		if(field.value!= "") {
			myString = new String(field.value);					
			start=myString.lastIndexOf(".");
			argvalue = myString.substring(start, myString.length);

			if (argvalue.toLowerCase()!= "xml" ) {
				addMessage(field, message);		
				return true;					
			}					
		}
	}	
		

	function invalidFromToZip(field1, field2)
	{	
		if ((isNum(field1.value) & !isNum(field2.value)) | (!isNum(field1.value) & isNum(field2.value))) {
			alert(AI_FROMTOZIPCODE);
			if(isNum(field1.value))
				field2.focus();
			else
				field1.focus();			
			return true;
		}
	}
/***********************************************************************************/

	function isEmail1(field)//email checking
	{
		var atPosition, dotPosition, lastPosition;
		var c = field.charAt(0);  
		aPosition = field.indexOf("@");
		dotPosition = field.lastIndexOf(".");
		lastPosition = field.length-1;
		
		if (aPosition < 1 || dotPosition - aPosition < 2 || lastPosition - dotPosition > 6 || lastPosition - dotPosition < 2) {
			return(false);
		}
		return(true);
	}

	function compareDates (start_field, end_field,message) 
	{
		var start = new Date (start_field.value);
		var end = new Date (end_field.value);
		if(start > end) {
			addMessage(end_field, message);
			return true;
		}
		else {
			return false;
		}
	} 

	function comparePass(oldVal,newVal,message)
	{
		if(oldVal.value == newVal.value) {
			addMessage(newVal, message);
			return true;			  
		}
		return false;
	}

/**
 * DHTML phone number validation script. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 */

// Declaring required variables
var digits = "0123456789";
// non-digit characters which are allowed in phone numbers
var phoneNumberDelimiters = "()- ";
// characters which are allowed in international phone numbers
// (a leading + is OK)
var validWorldPhoneChars = phoneNumberDelimiters + "+"+".";
// Minimum no of digits in an international phone no.
var minDigitsInIPhoneNumber = 10;

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
bol= (isInteger(s) && s.length >= minDigitsInIPhoneNumber);
if(bol==false)
{ 
   addMessage(field, message); 
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



function frm_submit(tmp)
{
 tmp.submit();
}

function form_sub(tmp)
{ 
 document.headerfrm.page.value=tmp;
 document.headerfrm.submit();
}


function leftTrim(sString) 
{
  while (sString.substring(0,1) == ' ' || sString.substring(0,1) == "\n" || sString.substring(0,1) == "\r" || sString.substring(0,1) == "\t")
   {
     
     sString = sString.substring(1, sString.length);
   }
  return sString;
}

function LTrim( value ) {
 var re = /\s*((\S+\s*)*)/;
 return value.replace(re, "$1");
}


function RTrim( value )
{
 var re = /((\s*\S+)*)\s*/;
 return value.replace(re, "$1");
}


function trim( value )
{
  return LTrim(RTrim(value));
}


function check_fileSize(field,tmpW)
{
	var img = new Image();
	img.src = field.value;
	var wid=img.width;
	var hit=img.height;
	
	if(wid >tmpW ) {
		alert("Banner image width should not be greater than "+tmpW+"");
		return true;
	}
	return false;
}

function setCss(id,ch,css,pre)
{  
 if(ch.checked==true)
 {
  document.getElementById(pre+"_"+id).className='over';
 }
  else
  {   document.getElementsByName('sel_del').checked=false;
    document.getElementById(pre+"_"+id).className=css;
	
	//selectDeselect('sel_del',false);
  }
}

function check_chars(id,char,field)
{
   var len=field.value.length;
   var string=field.value;
   
   if(len<char || len==char)
     document.getElementById(id).innerHTML=eval(char-len);
   else
   {
     field.value=string.substring(0,250);
     id.innerHTML=0;
   }
   	 
}


<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->


function filesize(field)
{
	
var control = new ActiveXObject("Scripting.FileSystemObject");
var d = field;
var e = control.GetFile(d);
var f = e.size;
alert(f + " bytes");
}


 var ret = false;
function createRequest() 
{
try {
     request = new XMLHttpRequest();
    } catch (trymicrosoft) { 

     try 
	 {
          request = new ActiveXObject("Msxml2.XMLHTTP");
	} 
	catch (othermicrosoft) 
	{
	  try
	  {
		request = new ActiveXObject("Microsoft.XMLHTTP");
		} 
		catch (failed)
		{
           request = false;
          }
      }

    }

if (!request)
alert("Error initializing XMLHttpRequest!");
 }

function check_availability(tmpField, tmpDivId,tmpMessage,frm) 
{
    createRequest()
    var url = "check_availablility.php?"+tmpField;
    request.open("GET", url, true);
    request.onreadystatechange = function(){ updatePage(tmpDivId,tmpMessage,frm);};
    request.send(null);
    return ret;
 }

function updatePage(tmpDivId,tmpMessage,frm)
{	 		
   if (request.readyState == 4) 
	 {
       if (request.status == 200)
	   {
        var response = request.responseText;
		
          if(response==1 || response>1)
		  	{
				// tmpDivId.innerHTML='<div id="err" style="font-family:verdana;font-size:11px;color:#FF0000;font-weight:bold;">'+tmpMessage+'</div>';
				
				addMessage(tmpDivId,tmpMessage);
				 
				 //frm.md_username.focus();
					return false;
			 
			}
			else
			{
				  return ret = validateFrm(frm);
			}

        }
       

          }
 }             


function setFoucs()
{ 
  var strForm = document.forms[0];
	for (i=0;i<strForm.length;i++)
        {
			 var tempobj=strForm.elements[i];
            
			 if ((tempobj.type=="text" || tempobj.type=="select-one" || tempobj.type=="password"))
             {
                
                   var strElementName=tempobj.id;
                   var strEle =document.getElementById(strElementName);
				
				strEle.focus();
				break;
			 }
		}
			
			
}


function bookmark(url,title){
  if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4)) {
  window.external.AddFavorite(url,title);
  } else if (navigator.appName == "Netscape") {
    window.sidebar.addPanel(title,url,"");
  } else {
    alert("Press CTRL-D (Netscape) or CTRL-T (Opera) to bookmark");
  }
}


function check_email(){
	 
	if(document.systemconfig.E_mail.value == ''){
	  return ;
	}  else {
		   var str=document.systemconfig.E_mail.value;
	  	             var filter=/^.+@.+\..{2,3}$/;
		                   if(!filter.test(str))
						   { 
						       document.getElementById('email_check').innerHTML = 'Not a Valid Email Id. Please Enter a Valid Email Id.';
						      return false;
		                   }
	       }
	
}

setFoucs();


function hrefBack() {
	history.go(-1);	
}