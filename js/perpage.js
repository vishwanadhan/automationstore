// JavaScript Document

function pagelimit(type)
{
//alert(type);
var retVal = new Array();  
 retVal = type.split('&');
 var typeLen = retVal.length;
 var queryString = ""; var ss; var result;
 if(typeLen >= 2)
 {
  queryString = queryString + retVal[0]; 
  for(i=1;i<=typeLen - 1;i++)
  {
   ss = retVal[i]; 
   result = ss.indexOf("imit=");   
   if(result != 1)
   {   
    queryString += "&"+ss;
   }
  }
 }
 else 
 { 
  queryString = type;
 }
 //alert(queryString); 
var limit = document.getElementById("limit").value;
window.location=queryString+"&limit="+limit;
}