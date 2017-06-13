if(!window.bea){window.bea={}
}if(!bea.netuix){bea.netuix={}
}bea.netuix.getStyleValue=function(elem,cssProp,jsProp){var value=null;
if(elem.currentStyle){value=elem.currentStyle[jsProp];
if(!value){value=elem.currentStyle[cssProp]
}}else{if(window.getComputedStyle){var compStyle=window.getComputedStyle(elem,null);
value=compStyle.getPropertyValue(cssProp)
}else{value=elem.style[jsProp]
}}return value
};
bea.netuix.ampEntityToChar=function(string){return string.replace(/&amp;/g,"&")
};
bea.netuix.parseParams=function(query){var q={};
var params=query.split(/&amp;|&/);
for(var i=0;
i<params.length;
i++){var pair=params[i].split("=");
q[pair[0]]=pair[1]
}return q
};
bea.netuix.ajaxportlet=new function(){this.replaceContents=function(containerId,content){var container=document.getElementById(containerId);
container.innerHTML=content
};
this.clearContents=function(containerId){var container=document.getElementById(containerId);
while(container.hasChildNodes()){container.removeChild(container.firstChild)
}};
this.displayLoading=function(containerId,content){var newContent=content.cloneNode(true);
bea.netuix.ajaxportlet.overlayContents(containerId,newContent)
};
this.displayError=function(containerId,content,url,message){var newContent=content.cloneNode(true);
bea.netuix.ajaxportlet.overlayContents(containerId,newContent);
var urlContainer=document.getElementById(containerId+"_error_url");
urlContainer.appendChild(document.createTextNode(url));
var messageContainer=document.getElementById(containerId+"_error_message");
messageContainer.appendChild(document.createTextNode(message))
};
this.overlayContents=function(containerId,content){var container=document.getElementById(containerId);
content.style.display="block";
content.style.top="2px";
content.style.right="2px";
container.appendChild(content)
};
this.createPostbackUrl=function(containerId,query){var postbackUrl=document.getElementById(containerId+"_postbackUrl").innerHTML;
var postbackParts=postbackUrl.split("?");
var postbackParams=bea.netuix.parseParams(postbackParts[1]);
var linkParams=bea.netuix.parseParams(query);
for(name in linkParams){if(!postbackParams[name]&&name.indexOf("_portlet.")!=0&&name!="_nfsp"&&name!="_urlCompression"){postbackParams[name]=linkParams[name]
}}var newUrl=postbackParts[0]+"?";
for(name in postbackParams){newUrl+=name+"="+postbackParams[name]+"&"
}newUrl=newUrl.slice(0,newUrl.length-1);
return newUrl
};
this.rewriteLinks=function(containerId,data,url){var pattern=/(\\?[\u0027\u0022])(https?:[^\u0027\u0022]*\?[^\u0027\u0022]*(_portlet\.async=true|__c=\w+)[^\u0027\u0022]*)\1/gi;
var newData=data.replace(pattern,function(str,p1,p2,offset,s){var url;
if(p2.toLowerCase().indexOf("_portlet.async=false")==-1){var encodedUrl=encodeURI(p2);
url=p1+"javascript:bea.netuix.ajaxportlet.updateContents(%22"+containerId+"%22,%22"+encodedUrl+"%22)"+p1
}else{url=p1+p2+p1
}return url
});
pattern=/(\\?[\u0027\u0022])(https?:[^\u0027\u0022]*\?([^\u0027\u0022]*_portlet\.async=false[^\u0027\u0022]*))\1/gi;
newData=newData.replace(pattern,function(str,p1,p2,p3,offset,s){return p1+bea.netuix.ajaxportlet.createPostbackUrl(containerId,p3)+p1
});
return newData
};
this.rewriteForms=function(containerId,url){var container=document.getElementById(containerId);
var forms=container.getElementsByTagName("form");
for(var i=0;
i<forms.length;
i++){var forceUnRewrite=true;
for(var j=0;
j<forms[i].elements.length;
j++){var element=forms[i].elements[j];
if(element.type=="hidden"){if(element.name){if((element.name.search(/.*_portlet\.async/)!=-1)&&(element.value.toLowerCase()=="false")){forceUnRewrite=true
}}}}var encType=forms[i].getAttribute("enctype");
if(forceUnRewrite){var action=forms[i].getAttribute("action");
if(action&&action.length>0){var pattern=/javascript:bea\.netuix\.ajaxportlet\.updateContents\((%22)(.*?)\1,\1([^?]*?)\?(.*?)\1\)/gi;
var newAction=action.replace(pattern,function(str,p1,p2,p3,p4,offset,s){var cleanQuery=decodeURI(p4);
return bea.netuix.ajaxportlet.createPostbackUrl(p2,cleanQuery)
});
var pageLabelQuery=document.getElementById(containerId+"_postbackUrl").innerHTML;
pageLabelQuery=pageLabelQuery.replace(/&amp;/g,"&");
var postbackParts=newAction.split("?");
var postbackParams=new Array();
var cleanQuery=decodeURI(postbackParts[1]);
var linkParams=bea.netuix.parseParams(newAction);
for(name in linkParams){if(!postbackParams[name]&&name!="_nfsp"){postbackParams[name]=linkParams[name]
}}var newUrl=pageLabelQuery;
for(name in postbackParams){if(name.indexOf("remove")!=0&&name.indexOf("/portal?_nfpb")!=0){newUrl+="&"+name+"="+postbackParams[name]
}}forms[i].setAttribute("action",newUrl)
}}else{var formId=forms[i].getAttribute("id");
if(!formId||formId.length==0){formId=container.id+"_form_"+i;
forms[i].setAttribute("id",formId)
}var action=forms[i].getAttribute("action");
if(action&&action.length>0){var pattern=/javascript:bea\.netuix\.ajaxportlet\.updateContents\((%22)(.*?)\1,\1(.*?)\1\)/;
var newAction=action.replace(pattern,"javascript:bea.netuix.ajaxportlet.submitForm($1$2$1,$1$3$1,$1"+formId+"$1)");
forms[i].setAttribute("action",newAction)
}var submits=[];
var inputs=forms[i].getElementsByTagName("input");
for(var j=0;
j<inputs.length;
j++){submits.push(inputs[j])
}var buttons=forms[i].getElementsByTagName("button");
for(var j=0;
j<buttons.length;
j++){submits.push(buttons[j])
}for(var j=0;
j<submits.length;
j++){var type=submits[j].getAttribute("type");
var name=submits[j].getAttribute("name");
if((type=="submit"||type=="image")&&name&&name.length>0){if(!bea.netuix.ajaxportlet.attachEventHandler(submits[j],"click",bea.netuix.ajaxportlet.submitButtonHandler)){alert("Event handler could not be attached")
}}}}}};
this.evalScripts=function(containerId){var container=document.getElementById(containerId);
var scripts=container.getElementsByTagName("script");
var newScripts=new Array(scripts.length);
var newTexts=new Array(scripts.length);
var scriptContainer=document.getElementById(containerId+"_script");
bea.netuix.ajaxportlet.clearContents(scriptContainer.id);
for(var i=0;
i<scripts.length;
i++){var newScript=document.createElement("script");
newScripts[i]=newScript;
if(scripts[i].attributes){var attrNodes=scripts[i].attributes;
for(var j=0;
j<attrNodes.length;
j++){if(attrNodes[j].specified){var newAttrNode=document.createAttribute(attrNodes[j].name);
if(attrNodes[j].value){newAttrNode.value=attrNodes[j].value
}newScript.setAttributeNode(newAttrNode)
}}}else{var attrNames=["charset","type","language","src","defer"];
for(var j=0;
j<attrNames.length;
j++){var attrValue=scripts[i].getAttribute(attrNames[j]);
if(attrValue){newScript.setAttribute(attrNames[j],attrValue)
}}}newTexts[i]=[];
if(scripts[i].text){newScript.text=scripts[i].text
}else{var nodes=scripts[i].childNodes;
for(var j=0;
j<nodes.length;
j++){if(nodes[j].nodeType==3){newTexts[i].push(document.createTextNode(nodes[j].nodeValue))
}}}}for(var i=0;
i<newScripts.length;
i++){for(var j=0;
j<newTexts[i].length;
j++){newScripts[i].appendChild(newTexts[i][j])
}}bea.netuix.ajaxportlet.insertScripts(scriptContainer,newScripts,0)
};
this.insertScripts=function(container,scripts,i){if(i<scripts.length){container.appendChild(scripts[i]);
if(scripts[i].readyState&&scripts[i].readyState!="complete"){scripts[i].onreadystatechange=function(){if(scripts[i].readyState=="loaded"){bea.netuix.ajaxportlet.insertScripts(container,scripts,i+1)
}}
}else{bea.netuix.ajaxportlet.insertScripts(container,scripts,i+1)
}}};
this.attachEventHandler=function(target,type,handler){var result=false;
if(target.addEventListener){target.addEventListener(type,handler,false);
result=true
}else{if(target.attachEvent){result=target.attachEvent("on"+type,handler)
}else{var name="on"+type;
var old=(target[name])?target[name]:function(){};
target[name]=function(e){old(e);
handler(e)
};
return true
}}return result
};
this.submitButtonHandler=function(e){var event=(e)?e:window.event;
var button=(event.srcElement)?event.srcElement:((event.currentTarget)?event.currentTarget:this);
var input=document.createElement("input");
input.setAttribute("type","hidden");
input.setAttribute("name",button.getAttribute("name"));
input.setAttribute("value",button.getAttribute("value"));
button.form.appendChild(input);
if(button.type=="image"){var posX=e.offsetX!==undefined?e.offsetX:e.layerX-button.offsetLeft-((button.scrollWidth-button.clientWidth)/2);
var input=document.createElement("input");
input.setAttribute("type","hidden");
input.setAttribute("name",button.getAttribute("name")+".x");
input.setAttribute("value",posX);
button.form.appendChild(input);
var posY=e.offsetY!==undefined?e.offsetY:e.layerY-button.offsetTop-((button.scrollHeight-button.clientHeight)/2);
input=document.createElement("input");
input.setAttribute("type","hidden");
input.setAttribute("name",button.getAttribute("name")+".y");
input.setAttribute("value",posY);
button.form.appendChild(input)
}};
this.submitForm=function(containerId,url,formId){var args={url:url,sync:false,formNode:document.getElementById(formId),load:function(type,data,evt){var newData=bea.netuix.ajaxportlet.rewriteLinks(containerId,data,url);
bea.netuix.ajaxportlet.replaceContents(containerId,newData);
bea.netuix.ajaxportlet.evalScripts(containerId);
bea.netuix.ajaxportlet.rewriteForms(containerId,url)
},error:function(type,data,evt){var error=document.getElementById(containerId+"_error");
bea.netuix.ajaxportlet.displayError(containerId,error,url,data.message)
}};
try{var loading=document.getElementById(containerId+"_load");
bea.netuix.ajaxportlet.displayLoading(containerId,loading);
bea.netuix.ajaxportlet.bind(args)
}catch(e){bea.netuix.ajaxportlet.replaceContents(containerId,e)
}};
this.updateContents=function(containerId,url){var args={url:url,sync:false,load:function(type,data,evt){var newData=bea.netuix.ajaxportlet.rewriteLinks(containerId,data,url);
bea.netuix.ajaxportlet.replaceContents(containerId,newData);
bea.netuix.ajaxportlet.evalScripts(containerId);
bea.netuix.ajaxportlet.rewriteForms(containerId,url)
},error:function(type,data,evt){var error=document.getElementById(containerId+"_error")
}};
try{var loading=document.getElementById(containerId+"_load");
bea.netuix.ajaxportlet.displayLoading(containerId,loading);
bea.netuix.ajaxportlet.bind(args)
}catch(e){bea.netuix.ajaxportlet.replaceContents(containerId,e)
}};
this.bind=function(args){var url=bea.netuix.ampEntityToChar(args.url);
var query=null;
if(args.formNode){var method=args.formNode.getAttribute("method");
if((method)&&(!args.method)){args.method=method
}query=bea.netuix.ajaxportlet.encodeForm(args.formNode)
}if(!args.method){args.method="get"
}var async=args.sync?false:true;
var http=bea.netuix.ajaxportlet.getXmlHttp();
if(http){http.onreadystatechange=function(){bea.netuix.ajaxportlet.load(args,http,url)
};
if(args.method.toLowerCase()=="post"){http.open("POST",url,async);
http.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
http.send(query)
}else{var queryUrl=url;
if(query){queryUrl+=(url.indexOf("?")>-1?"&":"?")+query
}http.open(args.method.toUpperCase(),queryUrl,async);
http.send(null)
}if(!async){bea.netuix.ajaxportlet.load(args,http,url,query)
}}else{alert("XMLHTTP not available")
}};
this.load=function(args,http,url){if(http.readyState==4){if(http.status==200){args.load("load",http.responseText,http)
}else{var message="XMLHttp Error: "+http.status+" "+http.statusText;
args.error("error",message,http)
}}};
this.encodeForm=function(formNode){var values=[];
for(var i=0;
i<formNode.elements.length;
i++){var element=formNode.elements[i];
if(element.disabled||element.tagName.toLowerCase()=="fieldset"){continue
}if(element.tagName.toLowerCase()=="input"){var name=encodeURIComponent(element.name);
var type=element.type.toLowerCase();
switch(type){case"radio":case"checkbox":if(element.checked){values.push(name+"="+encodeURIComponent(element.value))
}break;
case"button":case"file":case"image":case"reset":case"submit":break;
default:values.push(name+"="+encodeURIComponent(element.value))
}}if(element.tagName.toLowerCase()=="textarea"){var name=encodeURIComponent(element.name);
values.push(name+"="+encodeURIComponent(element.value))
}if(element.tagName.toLowerCase()=="select"){var name=encodeURIComponent(element.name);
for(var j=0;
j<element.options.length;
j++){if(!element.options[j].disabled&&element.options[j].selected){var value=element.options[j].value||element.options[j].text;
values.push(name+"="+encodeURIComponent(value))
}}}}return values.join("&")
};
var XMLHTTP_PROGIDS=["Msxml2.XMLHTTP","Microsoft.XMLHTTP","Msxml2.XMLHTTP.4.0"];
this.getXmlHttp=function(){var http=null;
try{http=new XMLHttpRequest()
}catch(e){}if(!http){for(var i=0;
i<XMLHTTP_PROGIDS.length;
++i){var progid=XMLHTTP_PROGIDS[i];
try{http=new ActiveXObject(progid)
}catch(e){}if(http){XMLHTTP_PROGIDS=[progid];
break
}}}return http
}
};
bea.netuix.iframe=new function(){this.init=function(iframe,addStyle){var innerDoc=(iframe.contentDocument)?iframe.contentDocument:iframe.contentWindow.document;
if((navigator.userAgent.indexOf("MSIE")==-1)||(event&&event.type=="readystatechange"&&innerDoc.readyState=="complete")){if(addStyle){bea.netuix.iframe.addWsrpStylesheet(iframe,innerDoc)
}else{bea.netuix.iframe.contentResize(iframe,innerDoc)
}}};
this.addWsrpStylesheet=function(iframe,innerDoc){var linkElements=document.getElementsByTagName("link");
var wsrpStylesheetElement;
for(var i=0;
i<linkElements.length;
i++){if(linkElements.item(i).href&&linkElements.item(i).href.indexOf("wsrp.css")>-1){wsrpStylesheetElement=linkElements.item(i);
break
}}if(wsrpStylesheetElement){var wsrpLink=innerDoc.createElement("link");
wsrpLink.href=wsrpStylesheetElement.href;
wsrpLink.rel=wsrpStylesheetElement.rel;
wsrpLink.type=wsrpStylesheetElement.type;
wsrpLink.media=wsrpStylesheetElement.media;
var headElements=innerDoc.getElementsByTagName("head");
if(headElements&&headElements.length>0){headElements.item(0).appendChild(wsrpLink)
}var styleSheets=innerDoc.styleSheets;
for(var i=0;
i<styleSheets.length;
i++){var styleSheet=styleSheets.item(i);
if(styleSheet.href&&styleSheet.href.indexOf("wsrp.css")>-1){var to=setTimeout(function(){if(!styleSheet.disabled){clearTimeout(to);
bea.netuix.iframe.contentResize(iframe,innerDoc)
}},10);
break
}}}};
this.contentResize=function(iframe,innerDoc){try{var divs=innerDoc.getElementsByTagName("div");
for(var i=0;
i<divs.length;
i++){divs[i].style.padding="0px";
divs[i].style.margin="0px";
if("bea-portal-window-content"==divs[i].getAttribute("class")){break
}}var body=innerDoc.getElementsByTagName("body")[0];
body.style.padding="0px";
body.style.margin="0px";
var overflow="hidden";
var height=body.scrollHeight;
var minHeight=bea.netuix.getStyleValue(iframe.parentNode,"min-height","minHeight");
if(minHeight){minHeight=minHeight.match(/(\d+)px/);
if(minHeight&&height<minHeight[1]){height=minHeight[1]
}}var maxHeight=bea.netuix.getStyleValue(iframe.parentNode,"max-height","maxHeight");
if(maxHeight){maxHeight=maxHeight.match(/(\d+)px/);
if(maxHeight&&height>maxHeight[1]){height=maxHeight[1];
overflow="auto"
}}if(height<=0){height=400
}iframe.parentNode.style.height=height+"px"
}catch(e){}}
};