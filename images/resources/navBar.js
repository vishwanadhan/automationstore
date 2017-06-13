/**
*	File Name		: navBar.js
*
*	Start Date		: 17th March 2010
*	End Date		:
*	Description		:  This deals with the functionality of Navigation Bar.
*
*/

var xmlhttp;
var url;
var pageId="1010";
var pageName=""; // Used for breadCrumb
var currentNavTitle="My Home";
var homeURL="";
var temphomeURL="";
var pageType="";
var isAMT=-1;
var absoluteURL="";
var absoluteURLBk="";
var role;
var noUser ="";
var intervalPeriod;
var curUserRoles = "";
var menuString="";

function getCookie(cname)
{
var name = cname + "=";
var ca = document.cookie.split(';');
for(var i=0; i<ca.length; i++)
  {
var c = ca[i];
  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
return "";
}

function disableHeaderObjects(data) {


	if (curUserRoles=="shtml") {
		curUserRoles= role;
	}
	// alert(data);
	var rolesArray = curUserRoles.split(",");
	$.each(data, function(idx, obj) {
		var tobeDisabled = true;
		for ( var i = 0; i < rolesArray.length; i++) {
			var curRole = rolesArray[i];
			var curExp = "(^" + curRole + ",|," + curRole + ",|" + curRole
					+ "$)";
			// alert(curExp);
			var pattern = new RegExp(curExp, "i");
			if (pattern.test(obj)) {
				tobeDisabled = false;
				break;
			}

			// Do something
		}

		if (tobeDisabled) {
			$("#" + idx).remove();
		}

	});
    $('.sautilBtnHolder').show();
    
	var isloggedcookie = getCookie("isloggedin");
	var isLoggedcookie = getCookie("IsLoggedin");

	var firstname =getCookie("firstname");
	var lastname =getCookie("lastname");
	var companyname =getCookie("companyname");
	//alert("user name"+fullname);
	if ((isloggedcookie != null && isloggedcookie !="") || (isLoggedcookie != null && isLoggedcookie !="")) {
		$(".nameToAppend").html(firstname+" "+lastname);
		$(".companyToAppend").html(companyname);
	}
    
    //Adjust the utility bar dropdown width based on the number of icons.
    var total_utility_panel = $('.sautilBtn').length;
    var utility_width=total_utility_panel*50-32;
    $('.sautilDropDwn').css('width',utility_width+'px');

}

function renderHeader(navTitle,type,ppURL,navrole) {

	curUserRoles = navrole;

	var isloggedcookie = getCookie("isloggedin");
	var isLoggedcookie = getCookie("IsLoggedin");
	if ((isloggedcookie != null && isloggedcookie !="") || (isLoggedcookie != null && isLoggedcookie !="")) {
		$.ajax({
			url : '/NOW/css/sa/headerSecure.html',
			type : 'GET',
			success : function(data) {
				// alert(data);
				$("#headerContent").html(data);
                
				$.ajax({
					dataType : 'json',
					url : '/NOW/css/sa/rolesMapping.json',
					type : 'GET',
					success : function(jsonRespData) {

						disableHeaderObjects(jsonRespData);
						displayNavbar();

					}

				});

			}, 
           error : function(){
				$.ajax({
					url : '/NOW/css/sa/headerSecure.html',
					type : 'GET',
					success : function(data) {
						$("#headerContent").html(data);
                        
                        $.ajax({
                            dataType : 'json',
                            url : '/NOW/css/sa/rolesMapping.json',
                            type : 'GET',
                            success : function(jsonRespData) {
        
                                disableHeaderObjects(jsonRespData);
                                displayNavbar();
        
                            }
        
                        });
					}
				});

		  }
		});

	}else {
		$.ajax({
			url : '/NOW/css/sa/headerPublic.html',
			type : 'GET',
			success : function(data) {
				$("#headerContent").html(data);
		   }, 
           error : function(){
				$.ajax({
					url : '/NOW/css/sa/headerPublic.html',
					type : 'GET',
					success : function(data) {
						$("#headerContent").html(data);
					}
				});

		  }
		});
	}




	var loc = document.location.href;
	if (loc.indexOf("page=print") > 0) {
		document.body.style.background = "none";
		document.getElementById("headerContent").style.display = "none";
	}

}


function showNavBar(navTitle,type,ppURL,navrole)
{
	// Call function to render Header
	loadHeaderData(navTitle,type,ppURL,navrole);

	// End call to header
	//alert(navrole);
}

/**
* Called from Header.jsp.
* Loads the XML file and calls stateChanged() when it's completely initialized.
*/
function showNavBarSA(navTitle,type,ppURL,navrole)
{


	navTitle = setWebContentPage(navTitle);
	ppURL = null;

	if(ppURL!=null){
	absoluteURL=ppURL;
	absoluteURLBk=ppURL;
	}
	currentNavTitle=navTitle;
        if(navrole==undefined ||navrole==""){
        if(navTitle=="null"){
          navrole="";
        }else{
                navrole="guest";
         }
       }

	/**#if(navrole==undefined ||navrole=="")
	*{
	*#	navrole="guest";
	*#}*/
	if(navrole=="prelogin"){
		navrole="";
	}

	/* To handle guest, netapp_guest role from eCM or eSearch */
	if(type=="eCM" ) {
			if(navrole.indexOf("guest,") > -1) {
				navrole = navrole.replace("guest,", "");
			} else if(navrole.indexOf(",guest") > -1) {
				navrole = navrole.replace(",guest", "");
			} else if(navrole.indexOf("guest") > -1) {
				navrole = navrole.replace("guest", "");
			}
			if(navrole.indexOf("netapp_guest") > -1) {
				navrole = navrole.replace("netapp_guest", "guest");
			}
	}


	pageType=type;
        if(navrole!="shtml"){
             role = navrole;
        }
        getHttpObj();


}
function getHttpObj()
{
	url="/NOW/css/newui/css/Navbar.xml";
	var loc = document.location.href;
	if(loc.indexOf("page=print")>0){
		document.getElementById("navBar").style.display="none";
	}else{
		xmlhttp=getXmlHttpObject();
		if (xmlhttp==null)
		  {
		  alert ("Your browser does not support XMLHTTP!");
		  displayStaticNavBar();
		  }
		if(window.ActiveXObject)
			{
			xmlhttp.async = false;
			xmlhttp.load(url);
			stateChanged();
			}
		else{

			xmlhttp.onreadystatechange=stateChanged;
			xmlhttp.open("GET",url,true);
			xmlhttp.send(null);
			}
    }
}

/**
* Sets the Level 1 and Level 2 class names.
*/
function setPage() {
 try
 {
	$('#' + pageId).removeClass('tab');
	$('#' + pageId).addClass('tab db');
 }catch(e)
    {
	displayStaticNavBar();
    }
}

/**
* Reads the Navigation XML file, iterates it and builds a string which is passed as innerHTML to Navbar.jsp
*/
function stateChanged()
{
	//populateHeader();
	//document.getElementById("navBar").className="mainMenuBox";
	if(null != document.getElementById("navBar"))
	{
		document.getElementById("navBar").style.display="none";
	}
	try{
	var xmlDoc;
	menuString="";
	var navRolePattern=new RegExp(role);
	if(!navRolePattern.test(noUser)){
		menuString="<ul class=\"sahdrTabs\">";

	}else{
		menuString="<ul class=\"sahdrTabs\">";

	}
	var popUpNav="";
	var secondLevel="";

	 // 0 Object is not initialized
	 // 1 Loading object is loading data
	 // 2 Loaded object has loaded data
	 // 3 Data from object can be worked with
	 // 4 Object completely initialized
	 if (xmlhttp.readyState!=4)
	{
	return;
	}
	if(window.ActiveXObject)
	{
	 xmlDoc=xmlhttp.documentElement;
	}
	else
	{
		xmlDoc=xmlhttp.responseXML.documentElement;
	}
	var xmlDom=xmlDoc.getElementsByTagName("ROW");
	var isLegacyMatchFound=false;
	var pattern;

	// Parent Level
	for (var i=0;i<xmlDom.length;i++)
		{

		var navParentNode= xmlDom[i].getElementsByTagName("NAVPARENT");
		// Level 1
		for (var j=0;j<navParentNode.length;j++)
			{

				var navDispName= navParentNode[j].getElementsByTagName("PDISPNAME")[0].childNodes[0].nodeValue;
				var navURL= navParentNode[j].getElementsByTagName("PURL")[0].childNodes[0].nodeValue;
				var navId= navParentNode[j].getElementsByTagName("PPAGEID")[0].childNodes[0].nodeValue;
				var navPageName= navParentNode[j].getElementsByTagName("PPAGENAME")[0].childNodes[0].nodeValue;
				var navRole=navParentNode[j].getElementsByTagName("ROLE")[0].childNodes[0].nodeValue;
				var navPRegExp;
				if(typeof(navParentNode[j].getElementsByTagName("PREGEXP")[0])!="undefined"){
					if(navParentNode[j].getElementsByTagName("PREGEXP")[0]!=null){
						navPRegExp= navParentNode[j].getElementsByTagName("PREGEXP")[0].childNodes[0].nodeValue;
					}
				}
				var nowNavTmpChilds = navParentNode[j].getElementsByTagName("NAVCHILD");
				var nowNavChild = new Array();
				var nowChildCount = 0;
				for(var childIndex=0; childIndex<nowNavTmpChilds.length;childIndex++){
					if(navRoleCheck(role,nowNavTmpChilds[childIndex].getElementsByTagName("ROLE")[0].childNodes[0].nodeValue)){
						nowNavChild[nowChildCount] = nowNavTmpChilds[childIndex];
						nowChildCount++;
					}
				}



				if(!navRoleCheck(role,navRole)){
					continue;
				}
				if(navURL.startsWith("http"))
				{

					absoluteURL="";

				}
				else{

				absoluteURL=absoluteURLBk;
				}


				if(navPageName=='My Home' )
				{ //For Breadcrumb

					temphomeURL="<li><a href=\'"+absoluteURL+navURL+"\' style=\"font-size:1.1em\">"+navDispName+"</a></li>";
					homeURL="<li><a href=\'"+absoluteURL+navURL+"\' style=\"font-size:1.1em\">"+navDispName+"</a></li>";
				}

				if(pageType=="legacyCGIStuff" && !isLegacyMatchFound && navPRegExp!="none" )
				{
					pattern = new RegExp(navPRegExp,"i");
					if(typeof navPRegExp != "undefined" && pattern.test(currentNavTitle)){
						isLegacyMatchFound=true;
						pageId=navId;
						pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"</b></li>";
					}
				}
				else if(currentNavTitle==navPageName )
				{
					pageId=navId;
					pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"</b></li>";

				}

				//adding level1 items. No more landing page. Might need check for public user to add landing pages.
				if(navPageName=='myhome' ){
					menuString+="<li class=\"satab\" id=\'"+navId+"\'><a class=\"satabLink\" href=\'"+absoluteURL+navURL+"\'>"+navDispName+"</a>";
				}else{
					menuString+="<li class=\"satab\" id=\'"+navId+"\'><a class=\"satabLink\">"+navDispName+"</a>";
				}

				//nowNavChild.length = 0;
				if(nowNavChild.length>0 )
				{
					if(!navRolePattern.test(noUser)){
						popUpNav+="<ul class=\"sanavSubMenu\">";
					}
				}

			// Level 2
			for (var k=0;k<nowNavChild.length;k++)
			{
				if(nowNavChild.length == 4){
					if(k%2==0){
						if(k!=0)
						{
							popUpNav+="</ul></li>";
						}
						popUpNav+="<li class=\"column\"><ul class=\"salinks\">";
					}
				}
				else if(k%3==0)
				{
					if(k!=0)
					{
						popUpNav+="</ul></li>";
					}
					 popUpNav+="<li class=\"column\"><ul class=\"salinks\">";
				}

				var navCDispName= nowNavChild[k].getElementsByTagName("CDISPNAME")[0].childNodes[0].nodeValue;
				var navCTitle= nowNavChild[k].getElementsByTagName("CTITLE")[0].childNodes[0].nodeValue;
				navCURL= nowNavChild[k].getElementsByTagName("CURL")[0].childNodes[0].nodeValue;
				var navCPageName= nowNavChild[k].getElementsByTagName("CPAGENAME")[0].childNodes[0].nodeValue;
				var navCRole=nowNavChild[k].getElementsByTagName("ROLE")[0].childNodes[0].nodeValue;
			    	var navCRegExp;
				if(typeof(nowNavChild[k].getElementsByTagName("CREGEXP")[0])!="undefined"){
					if(nowNavChild[k].getElementsByTagName("CREGEXP")[0]!=null){
			     		navCRegExp= nowNavChild[k].getElementsByTagName("CREGEXP")[0].childNodes[0].nodeValue;
					}
				}

				var nowNavTmpSubChilds = nowNavChild[k].getElementsByTagName("NAVSUBCHILD");
				var nowNavSChild = new Array();
				var nowSubChildCount = 0;
				if(nowNavTmpSubChilds.length>0){
					for(var subChildIndex=0; subChildIndex<nowNavTmpSubChilds.length;subChildIndex++){

						if(navRoleCheck(role,nowNavTmpSubChilds[subChildIndex].getElementsByTagName("ROLE")[0].childNodes[0].nodeValue)){
							nowNavSChild[nowSubChildCount] = nowNavTmpSubChilds[subChildIndex];
							nowSubChildCount++;
						}
					}

				 }
				else{
						nowNavSChild=nowNavTmpSubChilds;
					}
				 if(!navRoleCheck(role,navCRole)){
					continue;
				}



				if(navCURL.startsWith("http"))
				{
					absoluteURL="";
				}
				else{

				absoluteURL=absoluteURLBk;
				}

				if(pageType=="legacyCGIStuff" && !isLegacyMatchFound && navCRegExp!="none" )
				{

				pattern = new RegExp(navCRegExp,"i");
				if(typeof navCRegExp != "undefined" && pattern.test(currentNavTitle))
					{
						isLegacyMatchFound=true;
						pageId=navId;
						if(navDispName=='My Home'){
							pageName="<li></b> <a href=\'"+absoluteURL+navCURL+"\' style=\"font-size:1.1em\">"+navCDispName+"</a></li>";

						}else{
							if (navCURL == "#"){
								pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"</b><b style=\"font-size:1.1em\">&nbsp; >> &nbsp;</b> <b style=\"font-size:1.1em\">"+navCDispName+"</b></li>";
							}
							else{
								pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"</b><b style=\"font-size:1.1em\">&nbsp; >> &nbsp;</b> <a href=\'"+absoluteURL+navCURL+"\' style=\"font-size:1.1em;color:#0067C5\">"+navCDispName+"</a></li>";
							}
						}

					}
				}else if(currentNavTitle==navCPageName)
				{
							pageId=navId;
							if(navDispName=='My Home'){
								pageName="<li></b> <a href=\'"+absoluteURL+navCURL+"\' style=\"font-size:1.1em\">"+navCDispName+"</a></li>";

							}else{
								if (navCURL == "#"){
									 pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"&nbsp; >> &nbsp;</b> <b style=\"font-size:1.1em\">"+navCDispName+"</b></li>";
								}
								else{
									 pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"&nbsp; >> &nbsp;</b> <a href=\'"+absoluteURL+navCURL+"\' style=\"font-size:1.1em;color:#0067C5\">"+navCDispName+"</a></li>";
								}
							}
				}
				if (navCURL == "#"){
					popUpNav+="<li class=\"samainLink\"><span class=\"saarrow\"></span><span>"+navCTitle+"</span></li>";
				}
				else{
					popUpNav+="<li class=\"samainLink\"><a href=\'"+absoluteURL+navCURL+"\'><span class=\"saarrow\"></span><span>"+navCTitle+"</span></a></li>";
				}
				// Level 3

				for (var l=0;l<nowNavSChild.length;l++)
				{
						var navSCDispName= nowNavSChild[l].getElementsByTagName("SCDISPNAME")[0].childNodes[0].nodeValue;
						var navSCTitle= nowNavSChild[l].getElementsByTagName("SCTITLE")[0].childNodes[0].nodeValue;
						var navSCURL= nowNavSChild[l].getElementsByTagName("SCURL")[0].childNodes[0].nodeValue;
						var navSCPageName= nowNavSChild[l].getElementsByTagName("SCPAGENAME")[0].childNodes[0].nodeValue;
						if(typeof(nowNavSChild[l].getElementsByTagName("ROLE")[0])!="undefined"){
							if(nowNavSChild[l].getElementsByTagName("ROLE")[0]!=null){

						var navSCRole=nowNavSChild[l].getElementsByTagName("ROLE")[0].childNodes[0].nodeValue;
							}
						}

						var navSCRegExp;

						if(typeof(nowNavSChild[l].getElementsByTagName("SCREGEXP")[0])!="undefined"){
							if(nowNavSChild[l].getElementsByTagName("SCREGEXP")[0]!=null){
								navSCRegExp= nowNavSChild[l].getElementsByTagName("SCREGEXP")[0].childNodes[0].nodeValue;
							}
						}

						var navSCNaNetapp='';
						if(typeof(nowNavSChild[l].getElementsByTagName("SCNANETAPP")[0])!="undefined"){
							if(navSCNaNetapp= nowNavSChild[l].getElementsByTagName("SCNANETAPP")[0]!=null){
								navSCNaNetapp= nowNavSChild[l].getElementsByTagName("SCNANETAPP")[0].childNodes[0].nodeValue;
							}
						}
						if(!navRoleCheck(role,navSCRole)){
							continue;
						}

				if(navSCURL.startsWith("http"))
				{
					absoluteURL="";
				}
				else{
				absoluteURL=absoluteURLBk;
				}


				if(pageType=="legacyCGIStuff" && !isLegacyMatchFound && navSCRegExp!="none")
				{
					pattern = new RegExp(navSCRegExp,"i");
					if(typeof navSCRegExp != "undefined" && pattern.test(currentNavTitle))
					{
						isLegacyMatchFound=true;
						pageId=navId;
						if(navDispName=='My Home'){
							pageName="<li> <a href=\'"+absoluteURL+navCURL+"\' style=\"font-size:1.1em\">"+navCDispName+"</a><b>&nbsp; >> &nbsp; </b><a href=\'"+absoluteURL+navSCURL+"\' style=\"font-size:1.1em\">"+navSCDispName+"</a></li>";
						}else{
							if (navCURL == "#"){
								pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"&nbsp; >> &nbsp;</b> <b style=\"font-size:1.1em\">"+navCDispName+"</b><b style=\"font-size:1.1em\">&nbsp; >> &nbsp; </b><a href=\'"+absoluteURL+navSCURL+"\' style=\"font-size:1.1em;color:#0067C5\">"+navSCDispName+"</a></li>";
							}
							else{
								pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"&nbsp; >> &nbsp;</b> <a href=\'"+absoluteURL+navCURL+"\' style=\"font-size:1.1em;color:#0067C5\">"+navCDispName+"</a><b style=\"font-size:1.1em\">&nbsp; >> &nbsp; </b><a href=\'"+absoluteURL+navSCURL+"\' style=\"font-size:1.1em;color:#0067C5\">"+navSCDispName+"</a></li>";
							}
						}
					}
				} else if(currentNavTitle==navSCPageName)
				{
						pageId=navId;
						if(navDispName=='My Home'){
							pageName="<li> <a href=\'"+absoluteURL+navCURL+"\' style=\"font-size:1.1em\">"+navCDispName+"</a> <b style=\"font-size:1.1em\">&nbsp; >> &nbsp; </b><a href=\'"+absoluteURL+navSCURL+"\' style=\"font-size:1.1em\">"+navSCDispName+"</a></li>";
						}else{
						if (navCURL == "#"){
							pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"&nbsp; >> &nbsp;</b> <b style=\"font-size:1.1em\">"+navCDispName+"</b><b style=\"font-size:1.1em\">&nbsp; >> &nbsp; </b><a href=\'"+absoluteURL+navSCURL+"\' style=\"font-size:1.1em;color:#0067C5\">"+navSCDispName+"</a></li>";
						}
						else{
							pageName="<li><b style=\"font-size:1.1em\">"+navDispName+"&nbsp; >> &nbsp;</b> <a href=\'"+absoluteURL+navCURL+"\' style=\"font-size:1.1em;color:#0067C5\">"+navCDispName+"</a><b style=\"font-size:1.1em\">&nbsp; >> &nbsp; </b><a href=\'"+absoluteURL+navSCURL+"\' style=\"font-size:1.1em;color:#0067C5\">"+navSCDispName+"</a></li>";
						}
					}
				}


					if((pageType=="audit" || pageType=="portal" || pageType=="eService" || pageType=="legacyCGIStuff") && navCPageName=='auditmgmt'){
						if(parseInt(isAMT)>=parseInt(navSCNaNetapp))
						{
							popUpNav+="<li class=\"sasubLink\"><a href=\'"+absoluteURL+navSCURL+"\'><span class=\'saarrow\'></span><span>"+navSCTitle+"</span></a></li>";
						}

					}
					else if((pageType=="eService" && pageType=="legacyCGIStuff"  ) && navCPageName=="auditmgmt"){
						// Do Nothing for AMT -- eSrevice and legacyCGIStuff

					}
					else{
						popUpNav+="<li class=\"sasubLink\"><a href=\'"+absoluteURL+navSCURL+"\'><span class=\'saarrow\'></span><span>"+navSCTitle+"</span></a></li>";
					}


			}//level 3 for closing

				if(nowNavSChild.length>0)
				{
					if(!navRolePattern.test(noUser)){
					}
				}
				/*
				if(!navRolePattern.test(noUser)){
			secondLevel+="</li>";
				}
				*/
		}//level 2 for  closing
		if(nowNavChild.length>0)
		{
			if(!navRolePattern.test(noUser)){
			 //secondLevel+="</ul>";
				popUpNav+="</ul></li></ul>"; /*Closing tags for links, columns and navSubMenu*/
			// menuString+=secondLevel;
			 menuString+=popUpNav;
			 //secondLevel="";
			 popUpNav="";
			}
		 }
	menuString+="</li>"; /* Closing tag for tab*/

		}//level 1 closing
	 }//parent
	//alert(menuString);
	//menuString+="</ul>";

  }catch(e)
    {
	displayStaticNavBar();
    }
}

function breadCrumb() {
 try
 {
 var loc = document.location.href;
	if(loc.indexOf("page=print")>0){
			document.getElementById("breadCrumbsHome").style.display="none";
			document.getElementById("breadCrumbs").style.display="none";
	}else{
		  if(pageName==temphomeURL) {
			document.getElementById("breadCrumbsHome").style.display='block';
			document.getElementById("breadCrumbs").style.display='none';

		  }
		  else {
			document.getElementById("breadCrumbsHome").style.display='none';
			document.getElementById("breadCrumbs").style.display='block';
			document.getElementById("breadCrumbs").innerHTML="<div class=\"breadcontLeft\"><ul>"+homeURL+pageName+"</ul></div><div style=\"clear:both\"><!---comment--></div>";

		  }
	}
 }catch(e)
    {
	displayStaticNavBar();
    }
}

/**
* Called from navbar.jsp - AMT
*/
function amtAuthorization(isAMT)
{
	// this value is used to validate whether the user is eligible to view AMT links if so what is the role.
	this.isAMT=isAMT;
}

/**
* Static Navbar will be displayed incase of any issues with the xml/other issues.
*
*/
function displayStaticNavBar()
{
 //document.getElementById("breadCrumbsHome").style.display='block';
 //document.getElementById("breadCrumbs").style.display='none';
 //document.getElementById("navBar").innerHTML="<img class=\"popUpArrow\"  src=\"/NOW/images/popup_arrow.gif\" /> <ul class=\"firstLevel\"><li class=\"line\"><a href=\"/eservice/SupportHome.jsp\" class=\"mainLinks current currentPage\">Home</a><br></li><li class=\"line\"><a href=\"/portal/mysupport\" class=\"mainLinks\">My Home</a><br></li><li class=\"line\"><a href=\"/portal/troubleshooting\" class=\"mainLinks\">TroubleShooting Tools</a><br></li><li class=\"line\"><a href=\"/portal/documentation\" class=\"mainLinks\">Documentation</a><br></li><li class=\"line\"><a href=\"/portal/download\" class=\"mainLinks\">Downloads</a><br></li><li class=\"line\"><a href=\"/portal/supportassistancep\" class=\"mainLinks\">Technical Assistance</a><br></li><li class=\"line\"><a href=\"/portal/offerings\" class=\"mainLinks\">Support Offerings</a><br></li><li class=\"line\"><a href=\"/portal/consultingtraining\" class=\"mainLinks\">Consulting Training</a><br></li><li class=\"lineLast\"><a href=\"/portal/partners\" class=\"mainLinks\">Partners</a><br></li></ul>";
 //document.getElementById("navBar").className='mainMenuBox';
 // document.getElementById("navBar").innerHTML="<ul class=\"firstLevel\"><li class=\"line\"><a href=\"/portal/mysupport\" class=\"mainLinks current currentPage\">My Home</a><br></li><li class=\"line\"><a href=\"/portal/troubleshooting\" class=\"mainLinks\">TroubleShooting Tools</a><br></li><li class=\"line\"><a href=\"/portal/documentation\" class=\"mainLinks\">Documentation</a><br></li><li class=\"line\"><a href=\"/portal/download\" class=\"mainLinks\">Downloads</a><br></li><li class=\"line\"><a href=\"/portal/supportassistance\" class=\"mainLinks\">Technical Assistance</a><br></li><li class=\"line\"><a href=\"/portal/offerings\" class=\"mainLinks\">Support Offerings</a><br></li><li class=\"line\"><a href=\"/portal/consultingtraining\" class=\"mainLinks\">Consulting Training</a><br></li><li class=\"lineLast\"><a href=\"/portal/partners\" class=\"mainLinks\">Partners</a><br></li></ul>";
}

function navRoleCheck(role,navRole)
{
	var roleStatus=false;
	if(role.length==0)
	{
		return true;
	}
	var roleArray = new Array();
	var navRoleArray= new Array();
	roleArray = role.split(",");
	navRoleArray= navRole.split(",");
	for(var q=0;q<navRoleArray.length;q++){
				for(var p=0;p<roleArray.length;p++){
				if(navRoleArray[q]==roleArray[p])
				{
							roleStatus=true;
							break;
				}
			}
	}
	return roleStatus;
}

/**
* Returns the XmlHttpObject for all browsers.
*/
function getXmlHttpObject()
{
	myAppName = window.navigator.appName;

	if(myAppName !="Microsoft Internet Explorer"){
		if (window.XMLHttpRequest)
		  {
		  // code for Firefox, Chrome, Opera, Safari
		  return new XMLHttpRequest();
		  }
	}
	else if (window.ActiveXObject)
		  {
		  // code for IE6, IE5
		  return new ActiveXObject("Microsoft.XMLDOM");
		  }
	return null;
}

function populateHeader(){
	intervalPeriod = setInterval(populatHeadereDetails, 10);
}

function populatHeadereDetails(){
	if(document.getElementById("headerBlock")){
		clearInterval(intervalPeriod);

	}
}

String.prototype.startsWith = function(str)
{return (this.match("^"+str)==str)}

/**
* Get the nav role
*/

function loadHeaderData(navTitle,type,ppURL,navrole)
{
	if(navrole!="shtml")
	{
		role=navrole;
		showNavBarSA(navTitle,type,ppURL,navrole);
		renderHeader(navTitle,type,ppURL,role);
	}
	else
	{
		$.ajax({
	 	type: 'POST',
	 	url: '/eservice/StartupServlet',
	 	async: 'false',
		success: function(data)
		{
		role=data;
		var roleArray= role.split("|");
		getHeader(roleArray[1]);
		amtAuthorization(roleArray[2]);
		role=roleArray[0];
			if(role==undefined ||role=="" || role.search("Unauthorized Access") > -1)
			{
				role="guest";
			}
			showNavBarSA(navTitle,type,ppURL,navrole);
			renderHeader(navTitle,type,ppURL,role);
	  	}
		});
	}
}

function displayNavbar()
{
	 document.getElementById("headerMenu").innerHTML=menuString+"</ul>";
	 setPage();
 	 breadCrumb();
}

function setWebContentPage(navTitle)
{
	var pageURL = document.location.href;
	if(navTitle != null && navTitle == "webcontent")
	{
		//software version support ECMP1147223 ECMS1148138
		if(pageURL.indexOf("/info/web/ECMP1147223.html") != -1){
			navTitle = "softwareversionsupport";
		} else if(pageURL.indexOf("/info/web/ECMP1131362.html") != -1){
			//smart solve ECMP1131362 ECMS1148136
			navTitle = "ntstp";
		} else if(pageURL.indexOf("/info/web/ECMP1147404.html") != -1){
			//Latx ECMP1147404 ECMS1148136
			navTitle = "LatX";
		} else if(pageURL.indexOf("/info/web/ECMP1110975.html") != -1){
			//Latx ECMP1110975 ECMP1110975
			navTitle = "endofavailability";
		}
	}
	return navTitle;

}
