$(document).ready(function() {
         checkEnablePrinting();
    });

function getElementByClassForHiding(theClass)
{
        var allHTMLTags=document.getElementsByTagName("*");
        for (i=0; i<allHTMLTags.length; i++) {
        if (allHTMLTags[i].className==theClass) {
        allHTMLTags[i].style.display='none';
        }
        }
}

function divHide(divToHideOrShow)
{
     var div = document.getElementById(divToHideOrShow);
     div.style.display = "none";
}

function checkEnablePrinting()
{
        if(window.location.href.indexOf("popup=opened")>0)
        {
        if(window.opener.location.href.indexOf(".html")==-1)
        {
                if(window.opener.document.getElementById('htmlgrid').value.length>0)
                {
                var responsehtmlgrid=getPrintHtmlGrid("/NOW/css/newui/css/printhtmlgrid.html");
                }
                window.onbeforeunload = closePopupWindow;
        }
        }
}


function closePopupWindow()
{
   window.opener.document.getElementById('htmlgrid').value="";
   window.opener.document.getElementById('htmlgridid').value="";
}


function getPrintHtmlGrid(filerPath)
{
         try{
               httpReq = getXmlHttpObj();
               if (httpReq == null) {
                     alert("Your browser does not support XMLHTTP!");
               }
               httpReq.onreadystatechange = function()
               {
                     if (httpReq.readyState == 4 && httpReq.status==200 ){
                               try{
                        /* Make the Page WAIT  Until the total page along with EXTJS Grid is Loaded */
                        document.getElementById(window.opener.document.getElementById('htmlgridid').value).innerHTML ='';
                        var newdiv = document.createElement("div");
                        newdiv.innerHTML = window.opener.document.getElementById('htmlgrid').value;
                        var container = document.getElementById(window.opener.document.getElementById('htmlgridid').value);
                        container.appendChild(newdiv);

                        /* Hiding second Grid START */
                        var counter=0;
                        for (i=0;i<document.getElementsByTagName("div").length; i++)
                        {
                                if(document.getElementsByTagName("div").item(i).getAttribute('id') == window.opener.document.getElementById('htmlgridid').value)
                                {
                                                counter++;
                                                if(counter==2)
                                                {
                                                document.getElementsByTagName("div").item(i).style.display='none';
                                                }
                                }
                        }
                        /* Hiding second Grid END */

                                  }catch(error){}
                   }
                };
                httpReq.open("POST",filerPath,true);
                httpReq.send();
         }catch(e){
                alert("error occured :"+e);
                 errorMessage("Filer Path Not Found for PrintHtmlGrid");
         }
return "NULL FROM getPrintHtmlGrid() function";
}

function printWindow()
{
        if(window.location.href.indexOf(".html")==-1)
        {
        document.getElementById('htmlgrid').value="";
        document.getElementById('htmlgridid').value="";
        }
        if(isExtJsGrid().indexOf("x-grid3-hd x-grid3-cell")>0)
        {
        var htmlgridData=Ext.ux.GridPrinter.print(htmlgrid);
        document.getElementById('htmlgrid').value=htmlgridData;
        document.getElementById('htmlgridid').value=htmlgrid.id;
        }

        var winH, winW;
        var winleft,wintop;
        if (document.body && document.body.offsetWidth) {
                winW = document.body.offsetWidth;
                winH = document.body.offsetHeight;
        }
        if (document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth ) {
                winW = document.documentElement.offsetWidth;
                winH = document.documentElement.offsetHeight;
        }
        if (window.innerWidth && window.innerHeight) {
                winW = window.innerWidth;
                winH = window.innerHeight;
        }
        winleft= (winW - 700)/2;
        wintop= (winH - 700)/2;
        windowPosttions = 'toolbar=0,location=0,menubar=1,status=false,statusbar=false,scrollbars=1,resizable=1,width=700,height=700,left='+winleft+',top='+wintop;
        myWindow=window.open("/NOW/css/newui/css/Print.html?popup=opened", 'mywin',windowPosttions);
}

function isExtJsGrid()
{
        var portalBody ="<div style='overflow-y:visible;'><div style='width:950px;height:100%;margin:0px auto;'>";
        var divElements = document.getElementsByTagName("div");
        for(var i=0; i<divElements.length;i++)
        {
      if(divElements[i].className=='wlp-bighorn-book-content'){
            portalBody = portalBody+divElements[i].innerHTML;
            break;
      }
      else
      if(divElements[i].className=='bodyContentMainContainer'){
            portalBody = portalBody+divElements[i].innerHTML;
            break;
      }

        }
return portalBody;
}

function sendMail()
{

                location.href="/portal?currenturl="+escape(location.href)+"&_nfpb=true&initialPage=true&_st=&_pageLabel=support_sendMail#wlp_support_sendMail";

}
function addToFavorites()
{
var url=location.hostname+((location.port=="")?"":(":"+location.port))+location.pathname+location.search;
if(document.title.length==0)
{
document.title='Search';
}
favurl="/portal?pageName="+document.title+"&currenturl="+escape(url)+"&_nfpb=true&initialPage=true&_st=&_pageLabel=support_favorites";
window.open(favurl);

}


/**
 * Returns the XmlHttpObject for all browsers.
 */
function getXmlHttpObj() {
        myAppName = window.navigator.appName;
        if (myAppName != "Microsoft Internet Explorer") {
                if (window.XMLHttpRequest) {
                        // code for Firefox, Chrome, Opera, Safari
                        return new XMLHttpRequest();
                }
        } else if (window.ActiveXObject) {
                // code for IE6, IE5
                return new ActiveXObject("Microsoft.XMLHTTP");
        } else {
                return null;
        }
}

function getHeader(role) {
/*
var headerURL;
//alert("after var def");
    headerURL = (role == "partner") ? "/NOW/css/newui/css/partnerHeader.html": "/NOW/css/newui/css/nowHeader.html";
    $.ajax({
    url :  '/NOW/css/newui/css/sa/header.html',
    type : 'POST',
    success :function(data){
    //alert(data);
    $("#headerContent").html(data);
    },
    });
    var loc = document.location.href;
    if(loc.indexOf("page=print")>0){
            document.body.style.background="none";
            document.getElementById("headerContent").style.display="none";
    }
*/
}

function getPostLoginPage()
{
        return "/portal";
}


 function getHeaderSearch(role){
         var searchURL;

         searchURL = (role == "prelogin") ? "/NOW/css/ecmPages/ECMD1010435.html":"/NOW/css/ecmPages/ECMD1010429.html";
        //var searchURL="/NOW/css/newui/css/headerSearch.html";
        //var searchURL="http://library-dev.corp.netapp.com/ecm/ecm_get_file/ECMD1010435";
         try{
               httpReq = getXmlHttpObj();
               if (httpReq == null) {
                     alert("Your browser does not support XMLHTTP!");
               }
               httpReq.onreadystatechange = function()
               {
                     if (httpReq.readyState == 4 && httpReq.status==200 ){
                               try{
                                        var newdiv = document.createElement("div");
                                        newdiv.innerHTML = httpReq.responseText;
                                        var container = document.getElementById("headerGSearch");
                                        container.appendChild(newdiv);
                                                //document.getElementById("headerGSearch").innerHTML=httpReq.responseText;

                                        }catch(error){
                                                document.getElementById("headerGSearch").innerText=eval(httpReq.responseText);
                                        }

                   }
                };
                httpReq.open("POST",searchURL,true);
                httpReq.send();
         }catch(e){
                 errorMessage("headerSearch");
         }

         }

 function getFooter()
 {
    var loc = document.location.href;
    if(loc.indexOf("page=print")>0){
            document.getElementById("headerContent").style.display="none";
    }else{
	$.ajax({
		url : '/NOW/css/sa/footerPublic.html',
		type : 'GET',
		success : function(data) {
			$("#footerContent").html(data);
		},
		error : function(){
				$.ajax({
					url : '/NOW/css/sa/footerPublic.html',
					type : 'GET',
					success : function(data) {
						$("#footerContent").html(data);
					}
				});

		}
	});
     }
 }

function getCookie(c_name)
{
    var i,x,y,ARRcookies=document.cookie.split(";");
    for (i=0;i<ARRcookies.length;i++)
    {
        x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x=x.replace(/^\s+|\s+$/g,"");
        if (x==c_name)
        {
            return unescape(y);
        }
    }
}

function errorMessage(element){

         document.getElementById(element).innerHTML="<span>! Currently we are unbale to process your request.</span>"
 }
