bea.wlp.disc.Module._bundle(function(){bea.wlp.disc.Module.create("bea.wlp.disc._util",{declare:function($,L){var $Disc=bea.wlp.disc;
var ua=navigator.userAgent;
$.Browser={IE:!!(window.attachEvent&&!window.opera)&&new function(){var offset=ua.indexOf("MSIE ");
this.version=+(ua.substring(offset+5,ua.indexOf(";",offset)));
this.is7Up=(this.version>=7);
this.isPre7=!this.is7Up;
this.jscript=ScriptEngine&&parseFloat(ScriptEngineMajorVersion()+"."+ScriptEngineMinorVersion())
},Gecko:!!(ua.indexOf("Gecko")>-1&&ua.indexOf("KHTML")==-1)&&{},Opera:!!window.opera&&{},WebKit:!!(ua.indexOf("AppleWebKit/")>-1)&&{}};
$.Object=$Disc._Object.mixin({},$Disc._Object);
$.Array={each:function(src,iterator,when){if(src){for(var i=0,len=src.length;
i<len;
i++){if(!when||when(i,src[i])){iterator(i,src[i])
}}}}};
$.Function={nop:function(){},filter:function(condition,fn){return(condition?fn:$.Function.nop)
},name:function(fn){var match=fn.toString().match(/function\s*([^\(\s]*)/);
return(match?match[1]:"")
},signature:function(fn,body){if(body){var sig=fn.toString().replace(/\s+/g," ");
return(sig.length>100?sig.substring(0,97)+"...":sig)
}else{var match=fn.toString().match(/function[^\)]*/);
return(match?match[0]+")":"")
}},trace:function(fn,start,depth,max){fn=(fn||arguments.callee&&arguments.callee.caller);
if(fn&&fn.caller){var trace=[];
start=(start||0),depth=(depth||0),max=(max||10);
if(depth>=start){trace.push(fn)
}if(depth<max){try{var next=arguments.callee(fn.caller,start,depth+1,max);
if(next){trace=trace.concat(next)
}}catch(ignore){}}if(depth==0){trace.toString=function(){var st="";
$.Array.each(this,function(i,v){st+="  "+$.Function.signature(v,true)+"\n"
});
return st
}
}return trace
}}};
$.Error=$Disc.Class.create({initialize:function(error,traceFrom){if(error instanceof Error){this.name=error.name;
this.message=error.message;
this.cause=error
}else{if(typeof error=="string"){this.name="Error";
this.message=args
}else{if(typeof error=="object"){$.Object.mixin(this,error)
}else{this.name="Error"
}}}if(arguments.callee.caller){this.trace=$.Function.trace(arguments.callee,(traceFrom||0)+2)
}},toString:function(){return((this.trace&&(L("Trace to capture:")+"\n"+this.trace.toString()))||(this.cause&&this.cause.toString())||(this.name+(this.message?": "+this.message:"")))
}});
$.Dom={VISIBILITY:$.$meta.getName("Dom.visibility"),suppressSelects:$.Function.filter($.Browser.IE.isPre7,function(){var selects=document.getElementsByTagName("select");
if(selects){for(var i=0,len=selects.length;
i<len;
i++){var visibility=selects[i].style.visibility;
var isSet=visibility&&visibility.length>0;
selects[i][$.Dom.VISIBILITY]=(isSet?visibility:"visible");
selects[i].style.visibility="hidden"
}}}),restoreSelects:$.Function.filter($.Browser.IE.isPre7,function(){var selects=document.getElementsByTagName("select");
if(selects){for(var i=0,len=selects.length;
i<len;
i++){var visibility;
try{visibility=selects[i][$.Dom.VISIBILITY]
}catch(ignore){}if(visibility){selects[i].style.visibility=visibility
}selects[i][$.Dom.VISIBILITY]=undefined
}}}),purge:$.Function.filter(($.Browser.IE&&$.Browser.IE.jscript<5.7),function(d){var a=d.attributes,i,l,n;
if(a){for(i=0,l=a.length;
i<l;
i++){n=a[i].name;
if(typeof d[n]==="function"){d[n]=null
}}}a=d.childNodes;
if(a){for(i=0,l=a.length;
i<l;
i++){arguments.callee(d.childNodes[i])
}}}),clearContents:function(container){while(container.hasChildNodes()){$.Dom.purge(container.firstChild);
container.removeChild(container.firstChild)
}},getTextContent:function(el){return(el.innerText?el.innerText:(el.textContent?el.textContent:""))
},getViewport:function(){var w=0,h=0;
if($.Browser.Gecko){w=document.documentElement.clientWidth;
h=window.innerHeight
}else{if(!$.Browser.Opera&&window.innerWidth){w=window.innerWidth;
h=window.innerHeight
}else{if(!$.Browser.Opera&&document.documentElement&&document.documentElement.clientWidth){var w2=document.documentElement.clientWidth;
if(!w||w2&&w2<w){w=w2
}h=document.documentElement.clientHeight
}else{if(document.body&&document.body.clientWidth){w=document.body.clientWidth;
h=document.body.clientHeight
}}}}return{width:w,height:h}
},getScroll:function(){var top,left;
if(window.pageYOffset){top=window.pageYOffset;
left=window.pageXOffset
}else{if(document.documentElement){top=document.documentElement.scrollTop;
left=document.documentElement.scrollLeft
}else{if(document.body){top=document.body.scrollTop;
left=document.body.scrollLeft
}}}return{top:top||0,left:left||0}
},getHeadTag:function(){var head=document.getElementsByTagName("head");
if(head){head=head[0]
}else{head=document.createElement("head");
var body=document.getElementsByTagName("body");
if(body){document.documentElement.insertBefore(head,body[0])
}else{document.appendChild(head)
}}return head
},eachAncestor:function(element,action,self){if(!self){element=element.parentNode
}while(element&&action(element)){element=element.parentNode
}},eachDescendantLinear:function(element,action,tagName){var name=tagName||"*";
var elements=element.getElementsByTagName(name);
for(var i=0;
i<elements.length;
i++){if(!action(elements[i])){break
}}},eachDescendantRecursive:function(element,action,tagName){var name=tagName.toLowerCase();
var elements=element.childNodes;
for(var i=0;
i<elements.length;
i++){var child=elements[i];
var childName=child.tagName&&child.tagName.toLowerCase();
if(!((name==childName)||(name=="*"))||action(child)){this.eachDescendantRecursive(child,action,tagName)
}}}};
$.Event={getEvent:function(event){return(event?event:window.event)
},connect:function(target,type,handler){function wrapper(event){return handler($.Event.getEvent(event))
}var result=false;
if(target.addEventListener){target.addEventListener(type,wrapper,false);
result=true
}else{if(target.attachEvent){result=target.attachEvent("on"+type,wrapper)
}else{var name="on"+type;
var old=(target[name])?target[name]:function(){};
target[name]=function(event){old(event);
wrapper(event)
};
result=true
}}return result
}};
$.Json={parse:function(jsonText){if(jsonText.indexOf("/*")==0&&jsonText.lastIndexOf("*/")==jsonText.length-2){jsonText=jsonText.substring(2,jsonText.length-2)
}return eval("("+jsonText+")")
},serialize:function(obj){var serialize=arguments.callee;
var objtype=typeof(obj);
var props=[];
var value;
var val;
if(obj===null){value="null"
}else{if(objtype=="undefined"){value="undefined"
}else{if((objtype=="number")||(objtype=="boolean")){value=obj
}else{if(objtype=="string"){value='"'+obj+'"'
}else{if(objtype=="function"){value="(function)"
}else{if((objtype!="function")&&(typeof(obj.length)=="number")){for(var i=0;
i<obj.length;
i++){val=serialize(obj[i]);
props.push(val)
}value="["+props.join(",")+"]"
}else{for(var p in obj){val=serialize(obj[p]);
props.push('"'+p+'":'+val)
}value="{"+props.join(",")+"}"
}}}}}}return value
}};
$.Wlp={parseHookId:function(hookId){var label=hookId.slice(0,hookId.length-5);
var type=label.slice(0,2);
label=label.slice(2,label.length);
return{label:label,type:type}
}}
}});
bea.wlp.disc.Module._include("AsyncRequestOverlay","bea.wlp.disc.xie",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util","bea.wlp.disc.event"],declare:function($,L){var $Util=bea.wlp.disc._util;
$.AsyncRequestOverlay=new (bea.wlp.disc.Class.create(function(){this.initialize=function(){var dom=document.createElement("div");
this._dom=dom;
dom.id=this._getElementId();
dom.style.position=(this._useFixed()?"fixed":"absolute");
dom.style.visibility="hidden";
dom.style.top="0";
dom.style.left="0";
this.setOpacity();
this.setColor();
this.setZIndex();
var self=this;
function update(){self._update()
}$Util.Event.connect(window,"resize",update);
$Util.Event.connect(window,"scroll",update);
this._enabled=true;
this._events={OnShown:new bea.wlp.disc.event.Event("OnShown"),OnHidden:new bea.wlp.disc.event.Event("OnHidden")}
};
this.setEnabled=function(enabled){this._enabled=!!enabled;
if(!this._enabled){this._hide()
}};
this.getEnabled=function(){return this._enabled
};
this.setOpacity=function(opacity){this._opacity=((typeof opacity=="number"&&opacity<=1&&opacity>=0&&opacity)||0);
if($Util.Browser.IE){this._dom.style.filter="alpha(opacity="+(this._opacity*100)+")"
}else{this._dom.style.opacity=this._opacity
}};
this.getOpacity=function(){return this._opacity
};
this.setColor=function(color){this._dom.style.backgroundColor=(color||"#000")
};
this.getColor=function(){return this._dom.style.backgroundColor
};
this.setZIndex=function(zIndex){this._dom.style.zIndex=(typeof zIndex=="number"?zIndex:9000)
};
this.getZIndex=function(){return this._dom.style.zIndex
};
this.on=function(name,listener){this._listener("add",name,listener)
};
this.un=function(name,listener){this._listener("remove",name,listener)
};
this._listener=function(op,name,listener){if(name){var event=this._events["On"+name.charAt(0).toUpperCase()+name.slice(1)];
if(event instanceof bea.wlp.disc.event.Event){event[op+"Listener"].call(event,listener)
}}};
this._show=function(){if(this._enabled){if(!document.getElementById(this._getElementId())){(document.body||document.documentElement).appendChild(this._dom)
}this._isShowing=true;
this._update();
this._dom.style.visibility="visible";
this._dom.style.cursor="wait";
$Util.Dom.suppressSelects();
this._events.OnShown._fire(this)
}};
this._hide=function(){$Util.Dom.restoreSelects();
this._dom.style.cursor="default";
this._dom.style.visibility="hidden";
this._isShowing=false;
this._events.OnHidden._fire(this)
};
this._getElementId=function(){return $.$meta.getName("AsyncRequestOverlay._dom")
};
this._useFixed=function(){return(!$Util.Browser.IE||$Util.Browser.IE.is7Up)
};
this._update=function(){if(this._isShowing){var viewport=$Util.Dom.getViewport();
var h=viewport.height;
var w=viewport.width;
this._dom.style.width=w+"px";
this._dom.style.height=h+"px";
if(!this._useFixed()){var scroll=$Util.Dom.getScroll();
this._dom.style.top=scroll.top+"px";
this._dom.style.left=scroll.left+"px"
}viewport=$Util.Dom.getViewport();
if(viewport.width!=w){this._dom.style.width=viewport.width+"px"
}if(viewport.height!=h){this._dom.style.height=viewport.height+"px"
}}}
}))
}})
});
bea.wlp.disc.Module._include("Events","bea.wlp.disc.xie",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc.event","bea.wlp.disc._util"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Event=$Disc.event;
var $Util=$Disc._util;
$.Events={OnPrepareUpdate:new ($Event.Event.extend({initialize:function(){this.sup("OnPrepareUpdate",true)
},_payload:function(args){return new $.Events.Payloads.MutableUpdatePayload(args)
}})),OnRedirectUpdate:new ($Event.Event.extend({initialize:function(){this.sup("OnRedirectUpdate",$)
},_payload:function(args,xhr,response){return new $.Events.Payloads.RedirectPayload(args,xhr,response)
}})),OnHandleUpdate:new ($Event.Event.extend({initialize:function(){this.sup("OnHandleUpdate",$)
},_payload:function(args,xhr,response){return new $.Events.Payloads.ResponsePayload(args,xhr,response)
}})),OnPrepareMarkup:new ($Event.Event.extend({initialize:function(){this.sup("OnPrepareMarkup",$)
},_payload:function(args,xhr,response,label,type,control){return new $.Events.Payloads.MutableMarkupPayload(args,xhr,response,label,type,control)
}})),OnPrepareContent:new ($Event.Event.extend({initialize:function(){this.sup("OnPrepareContent",$)
},_payload:function(args,xhr,response,label,type,control,container){return new $.Events.Payloads.ContentPayload(args,xhr,response,label,type,control,container)
}})),OnInjectContent:new ($Event.Event.extend({initialize:function(){this.sup("OnInjectContent",$)
},_payload:function(args,xhr,response,label,type,control,container){return new $.Events.Payloads.ContentPayload(args,xhr,response,label,type,control,container)
}})),OnCompleteUpdate:new ($Event.Event.extend({initialize:function(){this.sup("OnCompleteUpdate",$)
},_payload:function(args,xhr,response){return new $.Events.Payloads.ResponsePayload(args,xhr,response)
}})),OnAbandonUpdate:new ($Event.Event.extend({initialize:function(){this.sup("OnAbandonUpdate",true)
},_payload:function(args,xhr){return new $.Events.Payloads.AbandonedPayload(args,xhr)
}})),OnError:new ($Event.Event.extend({initialize:function(){this.sup("OnError",$)
},_payload:function(message,description,error){return new $.Events.Payloads.ErrorPayload(message,description,error)
}})),_Debug:{enable:function(verbose){if(!this._listeners){this._listeners={};
var self=this;
$Util.Object.each($.Events,function(name,event){if(event instanceof $Event.Event){self._listeners[name]=function(payload){$Disc.Console.debug(name+(verbose?": "+payload.toString():""))
};
event.addListener(self._listeners[name])
}})
}},disable:function(){if(this._listeners){$Util.Object.each(this._listeners,function(name,listener){$.Events[name].removeListener(listener)
});
delete this._listeners
}}}}
}})
});
bea.wlp.disc.Module.create("bea.wlp.disc.xie",{include:["AsyncRequestOverlay","Events","Payloads","_Service"]});
bea.wlp.disc.Module._include("Payloads","bea.wlp.disc.xie",function(){bea.wlp.disc.Module.contribute({declare:function($,L){$.Events.Payloads={};
$.Events.Payloads.UpdatePayload=bea.wlp.disc.Class.create({initialize:function(args){this._args=args;
this._args._attrs=(this._args._attrs||{});
this._args._iattrs=(this._args._iattrs||{})
},getRequestUri:function(){return this._args.uri
},getRequestHeader:function(name){return(this._args.headers&&this._args.headers[name])
},setUpdateAttribute:function(name,value,_internal){(_internal?this._args._attrs:this._args._iattrs)[name]=value
},getUpdateAttribute:function(name,_internal){return(_internal?this._args._attrs:this._args._iattrs)[name]
},_addLifecycleListener:function(stage,action){this._args.lifecycle.addLifecycleListener(stage,action)
},_setRewrite:function(rewrite){this._args.rewrite=rewrite
},toString:function(){var headers=[];
bea.wlp.disc._Object.each(this._args.headers,function(name,header){headers.push("["+name+"="+header+"]")
});
return"uri: "+this._args.uri+", headers: ["+headers.join(", ")+"]"
}});
$.Events.Payloads.MutableUpdatePayload=$.Events.Payloads.UpdatePayload.extend({initialize:function(args){this.sup(args)
},setRequestHeader:function(name,value){(this._args.headers=(this._args.headers||{}))[name]=value
}});
$.Events.Payloads.ResponsePayload=$.Events.Payloads.UpdatePayload.extend({initialize:function(args,xhr,response){this.sup(args);
this._xhr=xhr;
this._response=response
},_getXhr:function(){return this._xhr
}});
$.Events.Payloads.AbandonedPayload=$.Events.Payloads.UpdatePayload.extend({initialize:function(args,xhr){this.sup(args,xhr);
this._xhr=xhr
},getXhr:function(){return this._xhr
}});
$.Events.Payloads.RedirectPayload=$.Events.Payloads.ResponsePayload.extend({initialize:function(args,xhr,response){this.sup(args,xhr,response)
},getRedirectUri:function(){return this._response.redirect
},toString:function(){return this.sup()+", redirectUri: "+this._response.redirect
}});
$.Events.Payloads.MarkupPayload=$.Events.Payloads.ResponsePayload.extend(function(MarkupPayload){this.initialize=function(args,xhr,response,label,type,control){this.sup(args,xhr,response);
this._label=label;
var ct=MarkupPayload.ControlType;
this._type=(type=="t_"?ct.PORTLET:(type=="e_"?ct.PAGE:ct.BOOK));
this._control=control
};
this.getControlLabel=function(){return this._label
};
this.getControlType=function(){return this._type
};
this.getControlContentType=function(){return this._control.contentType
};
this.getControlMarkup=function(){return this._control.markup
};
this.toString=function(){return this.sup()+", label: "+this._label+", type: "+this._type+", markup: "+this._control.markup
};
MarkupPayload.ControlType={BOOK:"book",PAGE:"page",PORTLET:"portlet"}
});
$.Events.Payloads.MutableMarkupPayload=$.Events.Payloads.MarkupPayload.extend({initialize:function(args,xhr,response,label,type,control){this.sup(args,xhr,response,label,type,control)
},setControlMarkup:function(markup){this._control.markup=markup
}});
$.Events.Payloads.ContentPayload=$.Events.Payloads.MarkupPayload.extend({initialize:function(args,xhr,response,label,type,control,container){this.sup(args,xhr,response,label,type,control);
this._container=container
},getControlContainer:function(){return this._container
},toString:function(){return this.sup()+", container.innerHTML: "+this._container.innerHTML
}});
$.Events.Payloads.ErrorPayload=bea.wlp.disc.Class.create({initialize:function(message,description,error){this._message=message;
this._description=description;
this._error=error
},getMessage:function(){return this._message
},getDescription:function(){return this._description
},getError:function(){return this._error
},toString:function(){return"message: '"+this._message+"', description: '"+this._description+(this._error?"', error: "+this._error:"")
}})
}})
});
bea.wlp.disc.Module._include("_Service","bea.wlp.disc.xie",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util","bea.wlp.disc.xie._impl"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Impl=$Disc.xie._impl;
var rewrite;
$._Service={ENCODED_TEST_STR:"%3D",DECODED_TEST_STR:"=",onload:function(){$Impl.Rewrite.all();
rewrite=true
},update:function(uri,formId,testEncodingStr){if(testEncodingStr==this.ENCODED_TEST_STR){uri=decodeURI(uri);
if(formId){formId=decodeURIComponent(formId)
}}var args={uri:uri,async:true};
if(formId){args.formNode=document.getElementById(formId)
}new this.Gateway(args).send()
},Gateway:$Disc.Class.create(function(Gateway){this.initialize=function(args){this._args=(args||{});
this._args.rewrite=(this._args.rewrite||rewrite)
};
this.send=function(responders){new $Impl.Engine(this._args,responders).send()
}
})}
},initialize:function($,L){var $Disc=bea.wlp.disc;
var $Util=$Disc._util;
$.Events.OnError.addListener(function(payload){var args=[L("[{0}] {1}",payload.getMessage(),payload.getDescription())];
var err=payload.getError();
if(err){args.push(err)
}$Disc.Console.error.apply($Disc.Console,args)
})
}})
});
bea.wlp.disc.Module._include("Engine","bea.wlp.disc.xie._impl",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util","bea.wlp.disc.xie"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Util=$Disc._util;
var $Xie=$Disc.xie;
$.Engine=$Disc.Class.create(function(Engine){var active=0;
var count=0;
var pendingEvents={};
this.initialize=function(args,responders){this._args=args;
this._id=""+count++;
this._args.lifecycle=new $.Lifecycle(responders);
this._args.error=this._args.lifecycle.onError;
if(!this._args.noAsyncOverlay&&this._args.async){function show(){if(active==0){$Xie.AsyncRequestOverlay._show()
}active+=1
}function hide(){active-=1;
if(active==0){$Xie.AsyncRequestOverlay._hide()
}}this._args.lifecycle.addLifecycleListener("onPrepareUpdate",show,hide);
this._args.lifecycle.addLifecycleListener("onCompleteUpdate",hide);
this._args.lifecycle.addLifecycleListener("onError",hide)
}this._tagActivator=new $.TagActivator()
};
this.getId=function(){return this._id
};
var current;
var pending=[];
this.send=function(){var self=this;
this._args.load=function(xhr,text){if(self._args.lock){function doLoad(){current=self;
self._load(xhr,text)
}if(!current){doLoad()
}else{pending.push(doLoad)
}}else{self._load(xhr,text)
}};
if(this._args.lifecycle.onPrepareUpdate(this._args)){try{$.Io.bind(this._args)
}catch(e){this._args.lifecycle.onError(e)
}}};
this.onCompleteUpdate=function(){try{this._args.lifecycle.onCompleteUpdate(this._args,this._xhr,this._response)
}finally{if(this._args.lock){current=null;
if(pending.length>0){pending.splice(0,1)[0]()
}}Engine.destroyWorkspace(this._workspace)
}};
this.addPendingEvent=function(id,event){pendingEvents[this._id]=(pendingEvents[this._id]||$Util.Object.map());
pendingEvents[this._id].put(id,event)
};
this._load=function(xhr,text){this._xhr=xhr;
try{var response=this._args.lifecycle.onHandleResponseText(text,this._tagActivator);
this._args.lifecycle.onHandleUpdate(this._args,xhr,response);
if(response.redirect){this._args.lifecycle.onRedirectUpdate(this._args,xhr,response);
window.location=response.redirect
}else{var markups=[],scripts=[];
if(response.books){markups=markups.concat(response.books)
}if(response.pages){markups=markups.concat(response.pages)
}if(response.portlets){markups=markups.concat(response.portlets)
}if(markups.length==0){this.onCompleteUpdate()
}else{for(var i=0,len=markups.length;
i<len;
i++){var processor=new $.MarkupProcessor(markups[i],this._args,xhr,response,this);
processor.process(scripts)
}this._tagActivator.enqueue({tags:scripts,container:this._getWorkspace(),temporal:true});
this._tagActivator.activate()
}}}catch(e){if(e instanceof SyntaxError){throw L("Invalid response from server; please check server log for potential problems")
}else{this._args.lifecycle.onError(e)
}}};
this._getWorkspace=function(){var parent=document.body||document.documentElement;
return(this._workspace||(this._workspace=Engine.createWorkspace(parent,this._id)))
};
Engine.firePendingEvent=function(engineId,eventId){var event=pendingEvents[engineId].remove(eventId);
var size=pendingEvents[engineId].size();
if(!size){delete pendingEvents[engineId]
}event(size)
};
Engine.createWorkspace=function(parent,id){id=$.$meta.getName("Engine.workspace_"+id);
var workspace=document.getElementById(id);
if(workspace){throw L("Workspace in use for id '{0}'",id)
}workspace=document.createElement("div");
workspace.id=id;
workspace.style.display="none";
parent.appendChild(workspace);
return workspace
};
Engine.destroyWorkspace=function(workspace){if(workspace){workspace.parentNode.removeChild(workspace)
}}
});
$.MarkupProcessor=bea.wlp.disc.Class.create({initialize:function(envelope,args,xhr,response,engine){this._validate(envelope);
this._envelope=envelope;
this._args=args;
this._xhr=xhr;
this._response=response;
this._engine=engine;
var parsed=$Util.Wlp.parseHookId(envelope.hookId);
this._label=parsed.label;
this._type=parsed.type;
this._origHookId=envelope.hookId;
this._args.lifecycle.onHandleMarkup(this._label,envelope);
this._destination=document.getElementById(this._envelope.hookId)
},process:function(scriptQueue){if(this._envelope.returnToCaller){this._args.lifecycle.onForwardMarkup(this._xhr,this._envelope);
this._event=this._tryOnCompleteUpdate;
this._enqueueScripts(scriptQueue)
}else{if(this._destination){var workspace=this._getWorkspace();
$Util.Dom.clearContents(this._destination);
if(this._envelope.markup.length==0){this._destination.style.visibility="hidden";
this._engine.onCompleteUpdate()
}else{this._onPrepareMarkup();
this._destination.style.visibility="visible";
this._innerHTML(workspace,this._envelope.markup);
var src=workspace;
var child=src.firstChild;
if(child&&child.id==this._origHookId){src=child
}this._onPrepareContent(src);
$Util.Dom.purge(this._destination);
this._innerHTML(this._destination,src.innerHTML);
if(this._args.rewrite){$.Rewrite.all(this._destination)
}$Util.Dom.clearContents(workspace);
this._enqueueScripts(scriptQueue)
}this._event=this._onInjectContent
}else{if(this._envelope.markup){throw L("Unable to locate tag with id '{0}'",this._envelope.hookId)
}}}},_innerHTML:function(destination,markup){var ieFixId;
if(markup&&$Util.Browser.IE){ieFixId=($.$meta.getName("Engine.ie_fix_"+this._engine.getId()+"_"+this._label));
markup="<div id='"+ieFixId+"' style='display: none;'>&nbsp;</div>"+markup
}destination.innerHTML=markup;
if(ieFixId){var ieFix=document.getElementById(ieFixId);
ieFix.parentNode.removeChild(ieFix)
}},_validate:function(envelope){function check(name){if(typeof envelope[name]=="undefined"){throw L("Invalid server response: markup envelope lacks field '{0}'",name)
}}check("hookId");
check("contentType");
check("markup")
},_enqueueScripts:function(queue){if(!this._envelope.returnToCaller){var tags=this._destination.getElementsByTagName("script");
$Util.Array.each(tags,function(i,tag){queue.push(tag)
})
}var self=this,engineId=this._engine.getId(),eventId=this._label;
this._engine.addPendingEvent(eventId,function(remaining){try{self._event.call(self,remaining)
}catch(e){self._args.lifecycle.onError(e)
}});
queue.push({name:"script",attributes:[{name:"type",value:"text/javascript"}],text:"bea.wlp.disc.xie._impl.Engine.firePendingEvent('"+engineId+"', '"+eventId+"');"})
},_getWorkspace:function(){var parent=this._destination.parentNode;
var id=this._engine.getId()+"_"+this._label;
return(this._workspace||(this._workspace=$.Engine.createWorkspace(parent,id)))
},_onPrepareMarkup:function(){this._args.lifecycle.onPrepareMarkup(this._args,this._xhr,this._response,this._label,this._type,this._envelope)
},_onPrepareContent:function(src){this._args.lifecycle.onPrepareContent(this._args,this._xhr,this._response,this._label,this._type,this._envelope,src)
},_onInjectContent:function(remaining){try{this._args.lifecycle.onInjectContent(this._args,this._xhr,this._response,this._label,this._type,this._envelope,this._destination)
}finally{this._tryOnCompleteUpdate(remaining)
}},_tryOnCompleteUpdate:function(remaining){$.Engine.destroyWorkspace(this._workspace);
if(remaining==0){try{this._engine.onCompleteUpdate()
}catch(e){self._args.lifecycle.onError(e)
}}}})
}})
});
bea.wlp.disc.Module._include("Io","bea.wlp.disc.xie._impl",function(){bea.wlp.disc.Module.contribute({declare:function($,L){var xhrSources=[function(){return new XMLHttpRequest()
},function(){return new ActiveXObject("Msxml2.XMLHTTP")
},function(){return new ActiveXObject("Microsoft.XMLHTTP")
},function(){return new ActiveXObject("Msxml2.XMLHTTP.4.0")
}];
var xhrIndex=(location.protocol=="file:"&&window.ActiveXObject?[1,0,2,3]:[0,1,2,3]);
$.Io={bind:function(args){var uri=args.uri;
var query=args.postdata;
if(args.formNode){if((args.formNode.method)&&(!args.method)){args.method=args.formNode.method.toUpperCase()
}query=this._encodeForm(args.formNode)
}args.method=(args.method?args.method.toUpperCase():"GET");
var async=!!args.async;
var xhr=this._getXhr();
var headerName;
var loadCalled;
if(xhr){xhr.onreadystatechange=function(){loadCalled=$.Io._load(args,xhr)
};
var reqUri;
var postData;
if(args.method=="POST"){reqUri=uri;
postData=query
}else{var queryUri=uri;
if(query){queryUri+=(uri.indexOf("?")>-1?"&":"?")+query
}reqUri=queryUri;
postData=null
}if(args.user&&args.password){xhr.open(args.method,reqUri,async,args.user,args.password)
}else{xhr.open(args.method,reqUri,async)
}if(postData){xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded")
}xhr.setRequestHeader("x-bea-netuix-xhr","true");
if(args.headers){for(headerName in args.headers){if(args.headers.hasOwnProperty(headerName.toString())){xhr.setRequestHeader(headerName,args.headers[headerName])
}}}xhr.send(postData);
if(!async&&!loadCalled){this._load(args,xhr)
}}else{throw L("XMLHttpRequest not available")
}},_getXhr:function(){var xhr;
for(var i=0;
i<xhrIndex.length;
i++){try{xhr=xhrSources[xhrIndex[i]]();
xhrIndex.splice(0,xhrIndex.length,xhrIndex[i]);
break
}catch(ignore){}}return xhr
},_load:function(args,xhr){var loaded;
try{if(xhr.readyState<4){args.lifecycle.onChangeXhrReadyState(xhr)
}else{if(xhr.readyState==4){var ctype=xhr.getResponseHeader("Content-Type");
if(ctype&&ctype.indexOf("text/x-netuix-json-comment-filtered")==0){var code;
try{code=xhr.status
}catch(ignore){throw L("Service not available")
}if(code==200){args.load(xhr,xhr.responseText)
}else{var status=xhr.statusText,body=xhr.responseText;
throw L("Unexpected response: status: '{0}', code: '{1}', body: '{2}'",status,code,body)
}}else{if(!args.xpr){args.lifecycle.onAbandonUpdate(args,xhr)
}args.lifecycle.onCompleteUpdate(args,xhr)
}loaded=true
}}}catch(e){args.error(e)
}return loaded
},_encodeForm:function(formNode){var values=[];
var name;
for(var i=0;
i<formNode.elements.length;
i++){var element=formNode.elements[i];
if(element.disabled||element.tagName.toLowerCase()=="fieldset"){continue
}if(element.tagName.toLowerCase()=="input"){name=encodeURIComponent(element.name);
var type=element.type.toLowerCase();
switch(type){case"radio":case"checkbox":if(element.checked){values.push(name+"="+encodeURIComponent(element.value))
}break;
case"button":case"file":case"image":case"reset":case"submit":break;
default:values.push(name+"="+encodeURIComponent(element.value))
}}if(element.tagName.toLowerCase()=="textarea"){name=encodeURIComponent(element.name);
values.push(name+"="+encodeURIComponent(element.value))
}if(element.tagName.toLowerCase()=="select"){name=encodeURIComponent(element.name);
for(var j=0;
j<element.options.length;
j++){if(!element.options[j].disabled&&element.options[j].selected){var value=element.options[j].text;
if(element.options[j].getAttribute("value")!==null){value=element.options[j].value
}values.push(name+"="+encodeURIComponent(value))
}}}}return values.join("&")
}}
}})
});
bea.wlp.disc.Module._include("Lifecycle","bea.wlp.disc.xie._impl",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util","bea.wlp.disc.xie"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Util=$Disc._util;
var $Xie=$Disc.xie;
$.Lifecycle=$Disc.Class.create(function(){this.initialize=function(responders){responders=(responders||{});
this.onHandleResponseText=responders.onHandleResponseText||function(text){return $Util.Json.parse(text)
};
this.onChangeXhrReadyState=responders.onChangeXhrReadyState||$Util.Function.nop;
this.onHandleMarkup=responders.onHandleMarkup||$Util.Function.nop;
this.onForwardMarkup=responders.onForwardMarkup||$Util.Function.nop;
var self=this;
$Util.Object.each($Xie.Events,function(name){if(name.indexOf("On")>=0&&name!="OnError"){var eventFn=name.replace(/^On/,"on");
self[eventFn]=function(){var result;
try{var event=$Xie.Events[name];
var payload=event._payload.apply(event,arguments);
if(responders&&typeof responders[eventFn]=="function"){responders[eventFn].call(self,payload)
}result=event._fire.call(event,payload)
}catch(e){self.onError(e)
}return result
}
}})
};
this.onError=function(exception){var message=L("EXCEPTION"),description,result;
if(exception.name&&exception.message){description=L("{0}: {1}",exception.name,exception.message)
}else{description=exception
}try{var error;
if(exception instanceof Error){error=(window.console&&window.console.firebug?exception:new $Util.Error(exception,1))
}var payload=$Xie.Events.OnError._payload(message,description,error);
result=$Xie.Events.OnError._fire(payload)
}catch(e){var msg;
if(e.name&&e.message){msg=L("{0}: {1}",e.name,e.message)
}else{msg=e
}var orig=L("{0}: {1}",message,description);
var log=L("Nested error [{0}] when firing OnError event with error [{1}]",msg,orig);
$Disc.Console.error(log,e instanceof Error?e:undefined)
}return result
};
this.addLifecycleListener=function(stage,action,cancelAction){if(stage&&typeof action=="function"){var lifecycle=this;
var orig=lifecycle[stage];
lifecycle[stage]=function(){var result;
action.apply(lifecycle,arguments);
if(orig){result=orig.apply(lifecycle,arguments);
if(typeof result=="boolean"&&!result&&typeof cancelAction=="function"){cancelAction.apply(lifecycle,arguments)
}}return result
}
}}
})
}})
});
bea.wlp.disc.Module.create("bea.wlp.disc.xie._impl",{include:["Engine","Io","Lifecycle","Rewrite","TagActivator"]});
bea.wlp.disc.Module._include("Rewrite","bea.wlp.disc.xie._impl",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util"],declare:function($,L){var $Util=bea.wlp.disc._util;
$.Rewrite={all:function(dom){var root=(dom?dom:document);
this._links(root);
this._forms(root)
},_submitButtonHandler:function(event){event=$Util.Event.getEvent(event);
var button=(event.srcElement)?event.srcElement:((event.currentTarget)?event.currentTarget:this);
var input=document.createElement("input");
input.setAttribute("type","hidden");
input.setAttribute("name",button.getAttribute("name"));
input.setAttribute("value",button.getAttribute("value"));
button.form.appendChild(input);
if(button.type=="image"){var posX=(event.offsetX!==undefined)?event.offsetX:event.layerX-button.offsetLeft-((button.scrollWidth-button.clientWidth)/2);
input=document.createElement("input");
input.setAttribute("type","hidden");
input.setAttribute("name",button.getAttribute("name")+".x");
input.setAttribute("value",posX);
button.form.appendChild(input);
var posY=(event.offsetY!==undefined)?event.offsetY:event.layerY-button.offsetTop-((button.scrollHeight-button.clientHeight)/2);
input=document.createElement("input");
input.setAttribute("type","hidden");
input.setAttribute("name",button.getAttribute("name")+".y");
input.setAttribute("value",posY);
button.form.appendChild(input)
}},_links:function(root){var list=root.getElementsByTagName("a");
for(var i=0;
i<list.length;
i++){this._link(list.item(i))
}},_forms:function(root){var forms=root.getElementsByTagName("form");
for(var i=0;
i<forms.length;
i++){this._form(forms[i])
}},_JS_UPDATE_FUNCTION:"javascript:bea.wlp.disc.xie._Service.update",_JS_UPDATE_FUNCTION_ARG:bea.wlp.disc.xie._Service.ENCODED_TEST_STR,_link:function(link){var prefixAdded;
if((link.innerHTML)&&(link.innerHTML.length>4)&&(link.innerHTML.substring(0,4)=="www.")){link.innerHTML="x"+link.innerHTML;
prefixAdded="true"
}var screen=this._screenUri(link.href);
if(screen.rewrite){link.href=this._JS_UPDATE_FUNCTION+"('"+encodeURI(screen.uri)+"', null, '"+this._JS_UPDATE_FUNCTION_ARG+"');"
}else{link.href=screen.uri
}if(prefixAdded){link.innerHTML=link.innerHTML.substring(1)
}},_form:function(form){if(form.enctype!="multipart/form-data"){if(!form.id||form.id.length==0){var result=this._getUniqueFormId();
form.id=result.id
}var screen=this._screenUri(form.action);
if(screen.rewrite){form.action=this._JS_UPDATE_FUNCTION+"('"+encodeURI(screen.uri)+"', '"+encodeURIComponent(form.id)+"', '"+this._JS_UPDATE_FUNCTION_ARG+"');"
}else{form.action=screen.uri
}var submits=[];
var inputs=form.getElementsByTagName("input");
var j;
for(j=0;
j<inputs.length;
j++){submits.push(inputs[j])
}var buttons=form.getElementsByTagName("button");
for(j=0;
j<buttons.length;
j++){submits.push(buttons[j])
}for(j=0;
j<submits.length;
j++){var type=submits[j].type;
var name=submits[j].name;
if((type=="submit"||type=="image")&&name&&name.length>0){$Util.Event.connect(submits[j],"click",this._submitButtonHandler)
}}}},_screenUri:function(uri){var rewrite;
if(uri){uri=uri.replace(new RegExp("([?&])_portlet\\.async=(\\w+)(&)?","i"),function(match,delim1,value,delim2){rewrite=(value.toLowerCase()=="true");
return delim1+"_nfxr="+value+(delim2||"")
});
var script=uri.indexOf("javascript:")==0;
var portal=uri.indexOf("_nfpb=true")>=0||uri.indexOf("__c=")>=0;
var resource=uri.indexOf("_portlet.renderResource=true")>=0||uri.indexOf("_portlet.renderResource%3Dtrue")>=0;
rewrite=(!script&&!resource&&portal&&(typeof rewrite=="undefined"||rewrite));
if(rewrite&&uri.indexOf("_nfxr")<0){var split=uri.split("#");
split[0]+=(split[0].indexOf("?")>=0?"&":"?")+"_nfxr=true";
uri=split[0]+(split[1]&&split[1].length>0?"#"+split[1]:"")
}}return{uri:uri,rewrite:rewrite}
},_getFormId:function(counter){return"ajax_form_"+counter
},_getUniqueFormId:function(){var counter=0;
var id=this._getFormId(counter);
while(document.getElementById(id)){counter++;
id=this._getFormId(counter)
}return{id:id,counter:counter}
}}
}})
});
bea.wlp.disc.Module._include("TagActivator","bea.wlp.disc.xie._impl",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Util=$Disc._util;
function createTag(src){var tag=document.createElement(src.name);
$Util.Array.each(src.attributes,function(i,attr){tag.setAttribute(attr.name,attr.value)
});
if(src.text&&src.text!="null"){if(typeof tag.appendChild=="function"){var node=document.createTextNode(src.text);
tag.appendChild(node)
}else{tag.text=src.text
}}return tag
}function activateTag(tag,container,onload){if(tag.parentNode){var clone=createTag({name:tag.nodeName,text:(tag.text||tag.textContent||tag.innerHTML),attributes:(function(){var attributes=[];
$Util.Array.each(tag.attributes,function(i,attr){if(attr.specified){attributes.push({name:attr.name,value:attr.value})
}});
return attributes
})()});
onload&&(clone.onload=clone.onerror=onload);
tag.parentNode.replaceChild(clone,tag);
tag=clone
}else{onload&&(tag.onload=tag.onerror=onload);
container.appendChild(tag)
}return tag
}$.TagActivator=$Disc.Class.create(function(){this.initialize=function(){this._tagBundles=[]
};
this.enqueue=function(tagBundle){this._tagBundles.push(tagBundle)
};
this.activate=function(){var self=this;
(function(bundleIndex){if(bundleIndex>=self._tagBundles.length){self._tagBundles=[]
}else{var activateBundle=arguments.callee;
var bundle=self._tagBundles[bundleIndex];
var container=bundle.container,tags=bundle.tags,temporal=bundle.temporal;
(function(tagIndex){if(tagIndex>=tags.length){activateBundle(bundleIndex+1)
}else{var activateTags=arguments.callee;
var tag;
if(tags[tagIndex].nodeName){tag=tags[tagIndex]
}else{tag=createTag(tags[tagIndex]);
if($Util.Browser.IE.isPre7&&tag&&temporal&&tags[tagIndex].name.toLowerCase()=="script"){tag=null;
function evaluate(text){if(typeof text=="string"){try{window.execScript(text.replace(/^\s*((<!--)|(<!CDATA\[))/g,""))
}catch(e){$Disc.Console.error(e.message)
}}}var src;
$Util.Array.each(tags[tagIndex].attributes,function(tagIndex,attr){if(attr.name=="src"){src=attr.value
}});
if(src){try{new $Disc._Request("GET",src,true).send(function(xhr){if(xhr.readyState==4){if(xhr.status==200){evaluate(xhr.responseText)
}else{$Disc.Console.warn(L("{0}: {1}",xhr.status,src))
}activateTags(tagIndex+1)
}})
}catch(e){$Disc.Console.warn(e.message);
activateTags(tagIndex+1)
}}else{if(tags[tagIndex].text){evaluate(tags[tagIndex].text);
activateTags(tagIndex+1)
}}}}if(tag){if(tag.nodeName.toLowerCase()=="script"){function scriptLoaded(){try{activateTags(tagIndex+1)
}catch(e){$Disc.Console.error(e.message)
}}tag=activateTag(tag,container,function(){scriptLoaded()
});
if(tag.src&&tag.readyState){if(!/loaded|complete/.test(tag.readyState)){var timeout;
if($.Config&&typeof $.Config.scriptLoadTimeoutHint=="number"){timeout=setTimeout(function(){scriptLoaded()
},$.Config.scriptLoadTimeoutHint)
}tag.onreadystatechange=function(){if(/loaded|complete/.test(tag.readyState)){if(timeout){clearTimeout(timeout)
}scriptLoaded()
}}
}else{scriptLoaded()
}}else{if(!tag.src){scriptLoaded()
}}}else{activateTag(tag,container);
activateTags(tagIndex+1)
}}}})(0)
}})(0)
}
})
}})
});
bea.wlp.disc.Module._include("GenericUriRewriter","bea.wlp.disc.uri",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Uri=$Disc.uri;
$.GenericUriRewriter=$Uri.UriRewriter.extend(function(GenericUriRewriter){this.initialize=function(uriStr,tmpltMap){this.sup(uriStr,tmpltMap)
};
GenericUriRewriter.groksUri=function(uriStr,tmpltMap){return true
}
})
}})
});
bea.wlp.disc.Module.create("bea.wlp.disc.uri",{include:["Uri","UriRewriter","GenericUriRewriter","WsrpUriRewriters","UriRewriterFactory"]});
bea.wlp.disc.Module._include("Uri","bea.wlp.disc.uri",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util","bea.wlp.disc.context"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Util=$Disc._util;
var $Context=$Disc.context;
$.Uri=$Disc.Class.create(function(Uri){var _REGEX=/^(((\w+):\/\/)((\w+)(:(\w+))?@)?([^\/\?:]+)(:?(\d+)?))?(\/?[^\?#]+)?(\?([^#]+)?)?(#(\w*))?/,_FIELDS={protocol:3,userName:5,password:7,host:8,port:10,path:11,query:13,fragment:15},_TEMPLATE_REGEX=/\{(\w+)\}/g;
Uri.getTemplateKeys=function(uriStr){var returnArray=[];
var result;
while((result=_TEMPLATE_REGEX.exec(uriStr))!=null){returnArray.push(result[1])
}return returnArray.length>0?returnArray:null
};
Uri.replace=function(uriStr,tmpltMap){var replacedUriStr=uriStr;
if(uriStr!=null){replacedUriStr=replacedUriStr.replace(_TEMPLATE_REGEX,function($0,$1){if(tmpltMap){if(tmpltMap[$1]!=null){return tmpltMap[$1]
}else{throw L("Invalid URI template key: {0}",$1)
}}else{return $0
}})
}return replacedUriStr
};
this.initialize=function(uriStr,tmpltMap){this._values={};
this._values.params={};
var self=this;
$Util.Object.each(_FIELDS,function(k,v){if(k!="query"){self["get"+k.charAt(0).toUpperCase()+k.slice(1)]=function(){return self._values[k]
};
self["set"+k.charAt(0).toUpperCase()+k.slice(1)]=function(value){if(value==null){delete self._values[k]
}else{self._values[k]=value
}}
}});
if(uriStr!=null&&uriStr!==""){if(tmpltMap){uriStr=Uri.replace(uriStr,tmpltMap)
}if(uriStr.indexOf("&amp;")!=-1){throw L("Invalid URI.  Do not entity encode ampersands (&amp;): {0}",uriStr)
}var match=_REGEX.exec(uriStr);
if(!match){throw L("Invalid URI: {0}",uriStr)
}$Util.Object.each(_FIELDS,function(k,v){self["set"+k.charAt(0).toUpperCase()+k.slice(1)].call(self,match[v])
})
}};
this.getParameter=function(key){var paramVals=this.getParameterValues(key);
return(paramVals!=null&&paramVals.length>0)?paramVals[0]:null
};
this.getParameterValues=function(key){return this._values.params[key]
};
this.getParameterMap=function(){return this._values.params
};
this.getParameterNames=function(){var paramNames=[];
for(paramName in this._values.params){paramNames.push(paramName)
}return(paramNames.length>0)?paramNames:null
};
this.setParameter=function(key,val){if(val==null){delete this._values.params[key]
}else{val instanceof Array?this._values.params[key]=val:this._values.params[key]=[val]
}};
this.addParameter=function(key,val){if(this._values.params[key]==null){this._values.params[key]=[]
}if(val!=null){this._values.params[key].push(val)
}};
this.removeParameter=function(key){delete this._values.params[key]
};
this.setParameterMap=function(paramMap){if(paramMap){this._values.params={};
for(param in paramMap){this.setParameter(param,paramMap[param])
}}else{this._values.params={}
}};
this.getQuery=function(){var qStr=null;
$Util.Object.each(this._values.params,function(k,v){if(qStr===null){qStr=""
}$Util.Array.each(v instanceof Array?v:[v],function(i,item){qStr=qStr+(qStr?"&":"")+encodeURIComponent(k)+"="+encodeURIComponent(item)
})
});
return qStr
};
this.setQuery=function(qStr){var self=this;
self._values.params={};
if(typeof qStr==="string"){qStr=qStr.replace(/\+/g,"%20");
$Util.Array.each(qStr.split("&"),function(i,v){var kv=v.split("=");
if(kv[0]!=null&&kv[0]!==""){self.addParameter(decodeURIComponent(kv[0]),(kv[1]!=null&&kv[1]!=="")?decodeURIComponent(kv[1]):"")
}})
}};
this.toString=function(){var uri="",self=this;
function get(f){return self["get"+f.charAt(0).toUpperCase()+f.slice(1)].call(self)
}function pre(d,f){return get(f)?(d?d:"")+get(f):""
}function post(f,d){return get(f)?get(f)+(d?d:""):""
}uri+=post("protocol","://");
uri+=post("userName",pre(":","password")+"@");
uri+=post("host",pre(":","port"));
uri+=pre("","path");
uri+=pre("?","query");
uri+=pre("#","fragment");
return uri
}
});
$.PageUrl=$.Uri.extend(function(PageUrl){this.initialize=function(pageLabel,encodeLabel){if(encodeLabel!==false){pageLabel=encodeURIComponent(pageLabel)
}var pageUrlTmplt=$Context.Url.getInstance().getPageUrlTemplate();
this.sup(pageUrlTmplt,{pageLabel:pageLabel})
}
})
}})
});
bea.wlp.disc.Module._include("UriRewriter","bea.wlp.disc.uri",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Uri=$Disc.uri;
$.UriRewriter=$Disc.Class.create(function(UriRewriter){UriRewriter.SERVICE_UPDATE_REGEX={regex:/(^javascript\:bea\.wlp\.disc\.xie\.\_Service\.update\s*\(\s*(['"]|%22|%27))([^,\)]*)(\2[\s\S]*\)\s*[\;]*\s*$)/,preBackRef:1,uriBackRef:3,endBackRef:4};
UriRewriter.UPDATE_CONTENTS_REGEX={regex:/(^javascript\:bea\.netuix\.ajaxportlet\.updateContents\s*\([^,]*,\s*(['"]|%22|%27))([\s\S]*)(\2\s*\)\s*;*$)/,preBackRef:1,uriBackRef:3,endBackRef:4};
UriRewriter.JS_PSEUDO_PROTOCOL_REGEXES=[UriRewriter.SERVICE_UPDATE_REGEX,UriRewriter.UPDATE_CONTENTS_REGEX];
this.initialize=function(uriStr,tmpltMap){if(uriStr!=null){var initUriStr=(tmpltMap?$Uri.Uri.replace(uriStr,tmpltMap):uriStr);
var uriStrParts=this.parseUriStrParts(initUriStr);
this.setUriPrefix(uriStrParts.uriPreStr);
this.setUriSuffix(uriStrParts.uriEndStr);
var initUri=new $Uri.Uri(uriStrParts.uriStr);
var uriParts=this.splitProxyAndTargetUris(initUri);
this._targetUri=uriParts.targetUri;
this._proxyUri=uriParts.proxyUri
}else{this._targetUri=new $Uri.Uri()
}};
this.parseUriStrParts=function(uriStr){return UriRewriter.staticParseUriStrParts(uriStr)
};
UriRewriter.staticParseUriStrParts=function(theUriStr){var uriStrParts={uriPreStr:"",uriStr:"",uriEndStr:""};
if(theUriStr.indexOf("javascript:")===0){var match;
for(var i=0;
i<UriRewriter.JS_PSEUDO_PROTOCOL_REGEXES.length;
i++){match=theUriStr.match(UriRewriter.JS_PSEUDO_PROTOCOL_REGEXES[i].regex);
if(match){uriStrParts.uriPreStr=match[UriRewriter.JS_PSEUDO_PROTOCOL_REGEXES[i].preBackRef];
uriStrParts.uriStr=match[UriRewriter.JS_PSEUDO_PROTOCOL_REGEXES[i].uriBackRef];
uriStrParts.uriEndStr=match[UriRewriter.JS_PSEUDO_PROTOCOL_REGEXES[i].endBackRef];
break
}}if(!match){throw L("Unrecognized javascript pseudo-protocol URI.  You must parse the URI out of the javascript yourself: {0}",theUriStr)
}}else{uriStrParts.uriStr=theUriStr
}return uriStrParts
};
this.splitProxyAndTargetUris=function(uri){return{targetUri:uri,proxyUri:null}
};
UriRewriter.groksUri=function(uriStr,tmpltMap){throw L("subclasses of UriRewriter must implement a static groksUri() method")
};
this.isProxyUri=function(){return !!this._proxyUri
};
this.getProxyUri=function(){if(this._proxyUri){if(this._proxyUriMayBeDirty){this.refreshTargetOnProxyUri();
this._proxyUriMayBeDirty=false
}return this._proxyUri
}else{return null
}};
this.setProxyUri=function(proxyUri){if(proxyUri){var uris=this.splitProxyAndTargetUris(proxyUri);
this._proxyUri=uris.proxyUri;
this._targetUri=uris.targetUri;
this._proxyUriMayBeDirty=false
}else{this._proxyUri=null
}};
this._proxyUriMayBeDirty=false;
this.getTargetUri=function(){if(this._proxyUri){this._proxyUriMayBeDirty=true
}return this._targetUri
};
this.setTargetUri=function(targetUri){if(targetUri){this._targetUri=targetUri;
this.refreshTargetOnProxyUri();
this._proxyUriMayBeDirty=false
}else{this._targetUri=null
}};
this.refreshTargetOnProxyUri=function(){};
this.getUriPrefix=function(){return this._uriPreStr
};
this.setUriPrefix=function(uriPreStr){uriPreStr!=null?this._uriPreStr=uriPreStr:this._uriPreStr="";
return this._uriPreStr
};
this.getUriSuffix=function(){return this._uriEndStr
};
this.setUriSuffix=function(uriEndStr){uriEndStr!=null?this._uriEndStr=uriEndStr:this._uriEndStr="";
return this._uriEndStr
};
this.getFullUriString=function(){var proxyUri=this.getProxyUri();
var targetUri=this.getTargetUri();
var uri=(proxyUri||targetUri);
return this.getUriPrefix()+uri.toString()+this.getUriSuffix()
}
})
}})
});
bea.wlp.disc.Module._include("UriRewriterFactory","bea.wlp.disc.uri",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Uri=$Disc.uri;
var $Util=$Disc._util;
$.UriRewriterFactory=$Disc.Class.create(function(UriRewriterFactory){UriRewriterFactory.REWRITERS=[$Uri.WsrpProxiedResourceUriRewriter,$Uri.WsrpServedResourceUriRewriter,$Uri.WsrpPbiaUriRewriter,$Uri.GenericUriRewriter];
UriRewriterFactory.getUriRewriter=function(uriStr,tmpltMap){var rewriter;
for(var i=0;
i<UriRewriterFactory.REWRITERS.length;
i++){if(UriRewriterFactory.REWRITERS[i].groksUri(uriStr,tmpltMap)){rewriter=new UriRewriterFactory.REWRITERS[i](uriStr,tmpltMap);
break
}}return rewriter
}
})
}})
});
bea.wlp.disc.Module._include("WsrpUriRewriters","bea.wlp.disc.uri",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util","bea.wlp.disc.context"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Uri=$Disc.uri;
var $Context=$Disc.context;
var _WSRP_URL_PARAM="wsrp-url";
var _WSRP_URL_TYPE_PARAM="wsrp-urlType";
var _WSRP_URL_SEPARATOR="/*wsrp*separator";
var _WSRP_URL_PATH_TOKEN="/u_";
var _WSRP_IMMUTABLE_URL_PATH_TOKEN="/iu_";
var _WSRP_PREFER_OPERATION_PATH_TOKEN="/po_";
var _WSRP_IMMUTABLE_URL_REG_EX=/\/iu\_([^\/]*)\//;
var _WSRP_URL_REG_EX=/\/u\_([^\/]*)\//;
var _WSRP_URL_SEPARATOR_REG_EX=/\/\*wsrp\*separator([\s\S]*)$/;
var _WSRP_URL_TYPE_BLOCKING_ACTION="blockingAction";
var _WSRP_URL_TYPE_RESOURCE="resource";
var _WSRP_RESOURCE_ID_PARAM="wsrp-resourceID";
var _getResourceProxyTargetUriParam=function(defaultValue,overrideValue){var targetUriParam;
if(overrideValue!=null){targetUriParam=overrideValue
}else{if($Context.Application.getInstance()!=null){targetUriParam=$Context.Application.getInstance().getResourceProxyTargetUriParam()
}}if(targetUriParam==null){targetUriParam=defaultValue
}return targetUriParam
};
$.WsrpProxiedResourceUriRewriter=$Uri.UriRewriter.extend(function(WsrpProxiedResourceUriRewriter){WsrpProxiedResourceUriRewriter.WSRP_URL_TYPE_PARAM=_WSRP_URL_TYPE_PARAM;
WsrpProxiedResourceUriRewriter.WSRP_URL_TYPE_RESOURCE=_WSRP_URL_TYPE_RESOURCE;
WsrpProxiedResourceUriRewriter.WSRP_URL_SEPARATOR=_WSRP_URL_SEPARATOR;
WsrpProxiedResourceUriRewriter.WSRP_URL_PATH_TOKEN=_WSRP_URL_PATH_TOKEN;
WsrpProxiedResourceUriRewriter.WSRP_IMMUTABLE_URL_PATH_TOKEN=_WSRP_IMMUTABLE_URL_PATH_TOKEN;
WsrpProxiedResourceUriRewriter.WSRP_PREFER_OPERATION_PATH_TOKEN=_WSRP_PREFER_OPERATION_PATH_TOKEN;
WsrpProxiedResourceUriRewriter.TARGET_URI_PARAM_DEFAULT=_WSRP_URL_PARAM;
WsrpProxiedResourceUriRewriter.TARGET_URI_PARAM_OVERRIDE=null;
WsrpProxiedResourceUriRewriter.WSRP_IMMUTABLE_URL_REG_EX=_WSRP_IMMUTABLE_URL_REG_EX;
WsrpProxiedResourceUriRewriter.WSRP_URL_REG_EX=_WSRP_URL_REG_EX;
WsrpProxiedResourceUriRewriter.WSRP_URL_SEPARATOR_REG_EX=_WSRP_URL_SEPARATOR_REG_EX;
this.initialize=function(uriStr,tmpltMap){this.sup(uriStr,tmpltMap)
};
this._isNewProxiedResourceURL=false;
this._isImmutableProxiedResourceURL=false;
WsrpProxiedResourceUriRewriter.getResourceProxyTargetUriParam=function(){return _getResourceProxyTargetUriParam(WsrpProxiedResourceUriRewriter.TARGET_URI_PARAM_DEFAULT,WsrpProxiedResourceUriRewriter.TARGET_URI_PARAM_OVERRIDE)
};
WsrpProxiedResourceUriRewriter.groksUri=function(uriStr,tmpltMap){var groksUri=false;
var uriParts=$Uri.UriRewriter.staticParseUriStrParts(uriStr);
var testUri=new $Uri.Uri(uriParts.uriStr,tmpltMap);
if(WsrpProxiedResourceUriRewriter._isNewSchoolProxiedResourceURL(testUri.toString())){groksUri=true
}else{if(WsrpProxiedResourceUriRewriter.WSRP_URL_TYPE_RESOURCE===testUri.getParameter(WsrpProxiedResourceUriRewriter.WSRP_URL_TYPE_PARAM)){var wsrpUrlParam=testUri.getParameter(WsrpProxiedResourceUriRewriter.getResourceProxyTargetUriParam());
if(wsrpUrlParam!=null&&wsrpUrlParam!==""){groksUri=true
}}}return groksUri
};
WsrpProxiedResourceUriRewriter._isNewSchoolProxiedResourceURL=function(uriStr){var isNewSchool=false;
if(uriStr.indexOf(WsrpProxiedResourceUriRewriter.WSRP_URL_SEPARATOR)>-1&&uriStr.indexOf(WsrpProxiedResourceUriRewriter.WSRP_PREFER_OPERATION_PATH_TOKEN+"false/")>-1){isNewSchool=true
}return isNewSchool
};
this.splitProxyAndTargetUris=function(uri){var targetUriStr=null;
if(WsrpProxiedResourceUriRewriter._isNewSchoolProxiedResourceURL(uri.toString())){this._isNewProxiedResourceURL=true;
var uriStr=uri.toString();
var matchImmutableTargetURL=uriStr.match(WsrpProxiedResourceUriRewriter.WSRP_IMMUTABLE_URL_REG_EX);
if(matchImmutableTargetURL!=null){targetUriStr=decodeURIComponent(matchImmutableTargetURL[1]||"");
if(targetUriStr){this._isImmutableProxiedResourceURL=true
}}if(!targetUriStr){var matchWholeTargetURL=uriStr.match(WsrpProxiedResourceUriRewriter.WSRP_URL_REG_EX);
if(matchWholeTargetURL!=null){targetUriStr=decodeURIComponent(matchWholeTargetURL[1]||"")
}}var matchRightSideURL=uriStr.match(WsrpProxiedResourceUriRewriter.WSRP_URL_SEPARATOR_REG_EX);
if(matchRightSideURL!=null){if(matchRightSideURL[1]!=null){targetUriStr=(targetUriStr?(targetUriStr+matchRightSideURL[1]):matchRightSideURL[1])
}}}else{targetUriStr=uri.getParameter(WsrpProxiedResourceUriRewriter.getResourceProxyTargetUriParam())
}var theTargetUri=new $Uri.Uri(targetUriStr);
return{targetUri:theTargetUri,proxyUri:uri}
};
this.refreshTargetOnProxyUri=function(){var targetUriStr=(this._targetUri?this._targetUri.toString():"");
if(this._isNewProxiedResourceURL){var leftSideURLStr="";
if(this._targetUri){leftSideURLStr=(this._targetUri.getProtocol()?this._targetUri.getProtocol():"");
if(this._targetUri.getHost()){leftSideURLStr=leftSideURLStr+"://"+this._targetUri.getHost()
}if(this._targetUri.getPort()){leftSideURLStr=leftSideURLStr+":"+this._targetUri.getPort()
}}var rightSideURLStr="";
if(leftSideURLStr.length<targetUriStr.length){rightSideURLStr=targetUriStr.substring(leftSideURLStr.length)
}var proxyUriStr=(this._proxyUri?this._proxyUri.toString():"");
if(this._isImmutableProxiedResourceURL){proxyUriStr=proxyUriStr.replace(WsrpProxiedResourceUriRewriter.WSRP_IMMUTABLE_URL_REG_EX,WsrpProxiedResourceUriRewriter.WSRP_IMMUTABLE_URL_PATH_TOKEN+encodeURIComponent(leftSideURLStr)+"/")
}else{proxyUriStr=proxyUriStr.replace(WsrpProxiedResourceUriRewriter.WSRP_URL_REG_EX,WsrpProxiedResourceUriRewriter.WSRP_URL_PATH_TOKEN+encodeURIComponent(leftSideURLStr)+"/")
}proxyUriStr=proxyUriStr.replace(WsrpProxiedResourceUriRewriter.WSRP_URL_SEPARATOR_REG_EX,WsrpProxiedResourceUriRewriter.WSRP_URL_SEPARATOR+rightSideURLStr);
this._proxyUri=new $Uri.Uri(proxyUriStr)
}else{this._proxyUri.setParameter(WsrpProxiedResourceUriRewriter.getResourceProxyTargetUriParam(),targetUriStr)
}}
});
$.WsrpServedResourceUriRewriter=$Uri.UriRewriter.extend(function(WsrpServedResourceUriRewriter){WsrpServedResourceUriRewriter.WSRP_URL_TYPE_PARAM=_WSRP_URL_TYPE_PARAM;
WsrpServedResourceUriRewriter.WSRP_URL_TYPE_RESOURCE=_WSRP_URL_TYPE_RESOURCE;
WsrpServedResourceUriRewriter.TARGET_URI_PARAM_DEFAULT=_WSRP_URL_PARAM;
WsrpServedResourceUriRewriter.TARGET_URI_PARAM_OVERRIDE=null;
WsrpServedResourceUriRewriter.WSRP_RESOURCE_ID_PARAM=_WSRP_RESOURCE_ID_PARAM;
WsrpServedResourceUriRewriter.WSRP_URL_SEPARATOR=_WSRP_URL_SEPARATOR;
WsrpServedResourceUriRewriter.WSRP_PREFER_OPERATION_PATH_TOKEN=_WSRP_PREFER_OPERATION_PATH_TOKEN;
this.initialize=function(uriStr,tmpltMap){this.sup(uriStr,tmpltMap)
};
WsrpServedResourceUriRewriter.getResourceProxyTargetUriParam=function(){return _getResourceProxyTargetUriParam(WsrpServedResourceUriRewriter.TARGET_URI_PARAM_DEFAULT,WsrpServedResourceUriRewriter.TARGET_URI_PARAM_OVERRIDE)
};
WsrpServedResourceUriRewriter.groksUri=function(uriStr,tmpltMap){var groksUri=false;
var uriParts=$Uri.UriRewriter.staticParseUriStrParts(uriStr);
var testUri=new $Uri.Uri(uriParts.uriStr,tmpltMap);
if(testUri.toString().indexOf(WsrpServedResourceUriRewriter.WSRP_URL_SEPARATOR)>-1&&testUri.toString().indexOf(WsrpServedResourceUriRewriter.WSRP_PREFER_OPERATION_PATH_TOKEN+"true/")>-1){groksUri=true
}else{if(WsrpServedResourceUriRewriter.WSRP_URL_TYPE_RESOURCE===testUri.getParameter(WsrpServedResourceUriRewriter.WSRP_URL_TYPE_PARAM)){var wsrpUrlParam=testUri.getParameter(WsrpServedResourceUriRewriter.getResourceProxyTargetUriParam());
if(wsrpUrlParam==null||wsrpUrlParam===""){groksUri=true
}}}return groksUri
}
});
$.WsrpPbiaUriRewriter=$Uri.UriRewriter.extend(function(WsrpPbiaUriRewriter){WsrpPbiaUriRewriter.WSRP_URL_TYPE_PARAM=_WSRP_URL_TYPE_PARAM;
WsrpPbiaUriRewriter.WSRP_URL_TYPE_BLOCKING_ACTION=_WSRP_URL_TYPE_BLOCKING_ACTION;
this.initialize=function(uriStr,tmpltMap){this.sup(uriStr,tmpltMap)
};
WsrpPbiaUriRewriter.groksUri=function(uriStr,tmpltMap){var groksUri=false;
var uriParts=$Uri.UriRewriter.staticParseUriStrParts(uriStr);
var testUri=new $Uri.Uri(uriParts.uriStr,tmpltMap);
return(WsrpPbiaUriRewriter.WSRP_URL_TYPE_BLOCKING_ACTION===testUri.getParameter(WsrpPbiaUriRewriter.WSRP_URL_TYPE_PARAM))
}
})
}})
});
bea.wlp.disc.Module.create("bea.wlp.disc.io",{include:["XMLHttpRequest"]});
bea.wlp.disc.Module._include("XMLHttpRequest","bea.wlp.disc.io",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc.uri","bea.wlp.disc.xie"],declare:function($,L){var $Disc=bea.wlp.disc;
var $Uri=$Disc.uri;
$.XMLHttpRequest=function(){var _requestHeaders=null;
var _method;
var _uri;
var _async=true;
var _respData;
var _orig;
var _aborted=false;
var _user=null;
var _password=null;
this.onreadystatechange=undefined;
this.status=undefined;
this.statusText=undefined;
this.responseText=undefined;
this.responseXML=undefined;
this.readyState=0;
function verifyState(){if(this.readyState==0){throw"open() not called yet"
}else{if(this.readyState!=1){throw"Currently sending"
}}}this.setRequestHeader=function(name,value){verifyState.call(this);
if(_requestHeaders==null){_requestHeaders={}
}_requestHeaders[name]=value
};
this.open=function(method,loc,async,user,password){_requestHeaders=null;
_respData=null;
_orig=null;
_aborted=false;
this.status=undefined;
this.statusText=undefined;
this.responseText=undefined;
this.responseXML=undefined;
this.readyState=1;
_method=method;
_async=async;
_uri=undoUriRewriting(loc);
_user=user;
_password=password
};
this.send=function(data){verifyState.call(this);
var func=this.onreadystatechange;
var self=this;
var args={uri:_uri,async:_async,method:_method,headers:_requestHeaders,postdata:data,user:_user,password:_password,noAsyncOverlay:true,xpr:true};
new bea.wlp.disc.xie._Service.Gateway(args).send({onPrepareUpdate:function(payload){payload.setRequestHeader("x-bea-rt-client","true")
},onChangeXhrReadyState:function(xhr,slave){if(!_aborted){self.readyState=xhr.readyState;
if(self.readyState>2){self.status=200;
self.statusText="OK"
}if(self.readyState==3){delete self.responseText
}if(func&&!slave){func()
}}},onForwardMarkup:function(xhr,envelope){if(!_aborted){this.onChangeXhrReadyState(xhr,true);
_respData={contentType:envelope.contentType,markup:envelope.markup,headers:envelope.headers};
if(_respData.contentType&&isXML(_respData.contentType)){self.responseXML=parseXML(_respData.markup,_respData.contentType)
}self.responseText=_respData.markup;
if(func){func()
}}},onCompleteUpdate:function(payload){if(!_aborted&&!_respData){_orig=payload._getXhr();
this.onChangeXhrReadyState(_orig,true);
if(_orig.responseXML){self.responseXML=_orig.responseXML
}self.responseText=_orig.responseText;
if(func){func()
}}}})
};
this.abort=function(){_requestHeaders=null;
_method="GET";
_uri=null;
_async=true;
_respData=null;
_aborted=true;
this.status=undefined;
this.statusText=undefined;
this.responseText=undefined;
this.responseXML=undefined;
this.readyState=0
};
function verifyNotAborted(){if(_aborted){throw"Aborted"
}}this.getResponseHeader=function(name){verifyNotAborted();
if(_orig){return _orig.getResponseHeader(name)
}else{if(!_respData){return null
}if(name.toLowerCase()=="content-type"){return _respData.contentType
}else{if(_respData.headers){return _respData.headers[name]
}}}};
this.getAllResponseHeaders=function(){verifyNotAborted();
if(_orig){return _orig.getAllResponseHeaders()
}else{if(!_respData){return""
}var str="Content-Type: "+_respData.contentType;
if(_respData.headers){for(var headerName in _respData.headers){str=str+"\n"+headerName+": "+_respData.headers[headerName]
}}return str
}};
function undoUriRewriting(orig){if(typeof orig!="string"){orig=orig.toString()
}var match=orig.match($Uri.UriRewriter.SERVICE_UPDATE_REGEX.regex);
if(match){orig=match[$Uri.UriRewriter.SERVICE_UPDATE_REGEX.uriBackRef]
}return orig
}function isXML(type){if(type){var index=type.indexOf(";");
if(index>0){type=type.substring(0,index)
}return(type=="text/xml"||type=="application/xml"||endsWith(type,"+xml"))
}return false
}function parseXML(text,contentType){var parsed;
try{if(window.ActiveXObject){var doc=new ActiveXObject("Microsoft.XMLDOM");
doc.async=false;
doc.loadXML(text);
parsed=doc.documentElement?doc:null
}else{if(document.implementation&&document.implementation.createDocument){var parser=new DOMParser();
parsed=parser.parseFromString(text,contentType);
var ns="http://www.mozilla.org/newlayout/xml/parsererror.xml";
if(parsed.firstChild.tagName=="parsererror"&&parsed.firstChild.namespaceURI==ns){parsed=null
}}}}catch(ignore){parsed=null
}return parsed
}function endsWith(s1,s2){var start=s1.length-s2.length;
if(start<0){throw"IllegalArgumentException: "+s1+" is shorter than "+s2
}return s1.substring(start)==s2
}}
},initialize:function($,L){bea.wlp.disc.Module.create("bea.netuix.ajax.client",{declare:function($,L){$.XMLHttpRequest=bea.wlp.disc.io.XMLHttpRequest
}})
}})
});
bea.wlp.disc.Module.create("bea.wlp.disc.event",{declare:function($,L){$.Event=bea.wlp.disc.Class.create(function(){var cancel={};
this.initialize=function(name,cancellable){this._listeners=[];
this._name=name;
this._cancellable=!!cancellable
};
this.getName=function(){return this._name
};
this.isCancellable=function(){return this._cancellable
};
this.cancel=function(){if(this._cancellable){throw cancel
}else{throw L("Event {0} is not cancellable",this._name)
}};
this.addListener=function(listener,cancelListener){if(typeof listener=="function"&&this._find(listener)<0){this._listeners.push({listen:listener,cancel:(typeof cancelListener=="function"&&cancelListener)})
}};
this.removeListener=function(listener){var i=this._find(listener);
if(i>=0){if(this._isFiring){this._listeners[i]=null
}else{this._listeners.splice(i,1)
}}};
this._fire=function(payload){this._isFiring=true;
var i;
try{for(i=0;
i<this._listeners.length;
i++){if(this._listeners[i]){this._listeners[i].listen(payload,this)
}}}catch(e){if(e===cancel){for(var j=0;
j<i;
j++){if(this._listeners[j]&&this._listeners[j].cancel){this._listeners[j].cancel(payload,this)
}}return false
}throw e
}finally{var len=this._listeners.length;
for(i=0;
i<len;
i++){if(!this._listeners[i]){this._listeners.splice(i,1)
}}delete this._isFiring
}return true
};
this._find=function(listener){var index=-1;
for(var i=0,len=this._listeners.length;
i<len;
i++){if(this._listeners[i]&&this._listeners[i].listen===listener){index=i;
break
}}return index
}
})
}});
bea.wlp.disc.Module._include("Base","bea.wlp.disc.context",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util"],declare:function($){var $Util=bea.wlp.disc._util;
$._HookContextProperty=$.$meta.getName("Context");
$.Context=bea.wlp.disc.Class.create(function(Context){this.initialize=function(props){this._props=props
};
this.toString=function(){var buff=[];
buff.push(this.getType());
buff.push(": ");
var props=this._getProps();
buff.push($Util.Json.serialize(props));
return buff.join("")
};
this._getPropValue=function(prop){var value=this._props[prop];
if(value===undefined){value=null
}return value
};
Context._BEA_WLP_DISC_CONTEXT=true
});
$._Metas={};
$.MetaContext=$.Context.extend(function(MetaContext){this.initialize=function(props){this.sup(props);
$._Metas[this.getType()]=this
};
MetaContext.getInstance=function(){return $._Metas[this.TYPE]
}
});
$.HookedContext=$.Context.extend(function(HookedContext){this.initialize=function(id,props){this.sup(props);
this._id=id;
var hook=document.getElementById(id);
hook[$._HookContextProperty]=this
};
this.getMarkupElement=function(){return document.getElementById(this._id)
};
HookedContext.getAll=function(){var all=[];
var divs=document.getElementsByTagName("div");
for(var i=0;
i<divs.length;
i++){var ctx=divs[i][$._HookContextProperty];
if(ctx instanceof this){all.push(ctx)
}}return all
};
HookedContext.findByElement=function(element,self){var type=this;
var context=null;
$Util.Dom.eachAncestor(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx&&ctx instanceof type){context=ctx;
proceed=false
}return proceed
},self);
return context
}
});
$.PresentationContext=$.HookedContext.extend(function(PresentationContext){PresentationContext._PROPS=["presentationClass","presentationId","presentationStyle"]
})
}})
});
bea.wlp.disc.Module._include("Layout","bea.wlp.disc.context",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util"],declare:function($){var $Util=bea.wlp.disc._util;
$.Layout=$.PresentationContext.extend(function(Layout){this.getParentPage=$._Common.getParentPage;
this.getPlaceables=$._Common.getPlaceables;
this.getPlaceholders=function(){var element=this.getMarkupElement();
var placeholders=[];
$Util.Dom.eachDescendantRecursive(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){if((ctx instanceof $.Window)||(ctx instanceof $.Layout)){proceed=false
}else{if(ctx.getType()==$.Placeholder.TYPE){placeholders.push(ctx)
}}}return proceed
},"div");
return placeholders
};
Layout._PROPS=["markupName"]
});
$.BorderLayout=$.Layout.extend(function(BorderLayout){BorderLayout._PROPS=["columns","strategy"];
BorderLayout.Strategy={BY_ORDER:"order",BY_TITLE:"title"}
});
$.FlowLayout=$.Layout.extend(function(FlowLayout){this.getParentPlaceholder=$._Common.getParentPlaceholder;
FlowLayout._PROPS=["implicit","orientation"];
FlowLayout.Orientation={HORIZONTAL:"horizontal",VERTICAL:"vertical"}
});
$.GridLayout=$.Layout.extend(function(GridLayout){GridLayout._PROPS=["columns","rowMajor","rows"]
});
$.Placeholder=$.PresentationContext.extend(function(Placeholder){this.getImplicitLayout=function(){var element=this.getMarkupElement();
var layout=null;
$Util.Dom.eachDescendantLinear(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){if(ctx instanceof $.Window){proceed=false
}else{if(ctx instanceof $.Layout){layout=ctx;
proceed=false
}}}return proceed
},"div");
return layout
};
this.getParentLayout=function(){var element=this.getMarkupElement();
var layout=null;
$Util.Dom.eachAncestor(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx&&(ctx instanceof $.Layout)){layout=ctx;
proceed=false
}return proceed
});
return layout
};
this.getPlaceables=$._Common.getPlaceables;
Placeholder._PROPS=["flow","implicit","location","locked","title","usingFlow","width"];
Placeholder.Flow={HORIZONTAL:"horizontal",VERTICAL:"vertical"}
})
}})
});
bea.wlp.disc.Module.create("bea.wlp.disc.context",{include:["_Mixin","Base","Window","Layout","Other"],initialize:function($){bea.wlp.disc._Object.each($,function(o){$[o].TYPE=o;
$[o].inject("getType",(function(type){return function(){return type
}
})(o));
$[o].inject("_getProps",(function(ctor){return function(){var props=this.sup?this.sup():{};
if(ctor._PROPS){for(var i=0;
i<ctor._PROPS.length;
i++){var name=ctor._PROPS[i];
if(this._props&&(this._props[name]!=null)){props[name]=this._props[name]
}}}return props
}
})($[o]));
if($[o].hasOwnProperty("_PROPS")){var names=$[o]._PROPS;
for(var p=0;
p<names.length;
p++){var getter="get"+names[p].substring(0,1).toUpperCase()+names[p].substring(1);
$[o].inject(getter,(function(name){return function(){return this._getPropValue(name)
}
})(names[p]))
}}if($[o].hasOwnProperty("_MIXINS")){var mixins=$[o]._MIXINS;
for(var m=0;
m<mixins.length;
m++){$[o].inject($._Mixin[mixins[m]])
}}},function(o){return $[o]._BEA_WLP_DISC_CONTEXT
})
}});
bea.wlp.disc.Module._include("Other","bea.wlp.disc.context",function(){bea.wlp.disc.Module.contribute({declare:function($){$.Header=$.PresentationContext.extend({});
$.Footer=$.PresentationContext.extend({});
$.Menu=$.PresentationContext.extend(function(Menu){this.getParentBook=$._Common.getParentBook;
Menu._PROPS=["markupName","menuItems"]
});
$.SingleLevelMenu=$.Menu.extend({});
$.MultiLevelMenu=$.Menu.extend({});
$.Application=$.MetaContext.extend(function(Application){Application._PROPS=["asyncModeEnabled","customizationEnabled","defaultLocale","desktopPath","dotPortal","dvtEnabled","localizationEnabled","portalPath","preferredLocales","productionModeEnabled","resourceProxyTargetUriParam","standalonePortlet","userName","webAppName"]
});
$.Desktop=$.MetaContext.extend(function(Desktop){this.getLabel=function(){return this.getDefinitionLabel()
};
Desktop._PROPS=["title","definitionLabel"]
});
$.LookAndFeel=$.MetaContext.extend(function(LookAndFeel){LookAndFeel._PROPS=["skin","skeleton","markupName"]
});
$.Shell=$.MetaContext.extend(function(Shell){Shell._PROPS=["markupName"]
});
$.Url=$.MetaContext.extend(function(Url){Url._PROPS=["pageUrlTemplate"]
})
}})
});
bea.wlp.disc.Module._include("Window","bea.wlp.disc.context",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util","bea.wlp.disc.xie._impl"],declare:function($){var $Disc=bea.wlp.disc;
var $Util=$Disc._util;
var $Xie=$Disc.xie;
$.Window=$.PresentationContext.extend(function(Window){this.getContentMarkupElement=function(){var element=this.getMarkupElement();
var contentElement=null;
$Util.Dom.eachDescendantRecursive(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){var type=ctx.getType();
if(type==$.Titlebar.TYPE){proceed=false
}else{if(type==$._Content.TYPE){contentElement=el;
proceed=false
}}}return proceed
},"div");
return contentElement
};
this.getLabel=function(){return this.getDefinitionLabel()
};
this.getParentPage=$._Common.getParentPage;
this.getParentTheme=function(deep){var element=this.getMarkupElement();
var theme=null;
$Util.Dom.eachAncestor(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){if(!deep&&(ctx instanceof $.Window)){proceed=false
}else{if(ctx.getType()==$.Theme.TYPE){theme=ctx;
proceed=false
}}}return proceed
});
return theme
};
this.getTitlebar=function(){var element=this.getMarkupElement();
var titlebar=null;
$Util.Dom.eachDescendantRecursive(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){var type=ctx.getType();
if(type==$._Content.TYPE){proceed=false
}else{if(type==$.Titlebar.TYPE){titlebar=ctx;
proceed=false
}}}return proceed
},"div");
return titlebar
};
Window.findByLabel=function(label){var context=null;
var all=this.getAll();
for(var i=0;
i<all.length;
i++){if(all[i].getLabel()==label){context=all[i];
break
}}return context
};
this.setTitle=function(title){this._props.title=title
};
Window._PROPS=["contentPresentationClass","contentPresentationStyle","definitionLabel","ajaxHookId","title","windowMode","windowState","updateable","removeable"]
});
$.Page=$.Window.extend(function(Page){this.getParentBook=$._Common.getParentBook;
this.getLayout=function(){var element=this.getMarkupElement();
var layout=null;
$Util.Dom.eachDescendantRecursive(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){if(ctx instanceof $.Window){proceed=false
}else{if(ctx instanceof $.Layout){layout=ctx;
proceed=false
}}}return proceed
},"div");
return layout
};
this.getPlaceables=$._Common.getPlaceables;
Page._PROPS=["activeImage","inactiveImage","rolloverImage"]
});
$.Book=$.Page.extend(function(Book){this.getMenu=function(){var element=this.getMarkupElement();
var menu=null;
$Util.Dom.eachDescendantRecursive(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){if(ctx instanceof $.Window){proceed=false
}else{if(ctx instanceof $.Menu){menu=ctx;
proceed=false
}}}return proceed
},"div");
return menu
};
this.getPage=function(){var element=this.getMarkupElement();
var page=null;
$Util.Dom.eachDescendantLinear(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx&&(ctx instanceof $.Page)){page=ctx;
proceed=false
}return proceed
},"div");
return page
};
this.setPlaceholderPosition=function(placeholderPosition){this._props.placeholderPosition=placeholderPosition
};
Book._MIXINS=["Placeable"];
Book._PROPS=["placeholderPosition","defaultPage"]
});
$.Portlet=$.Window.extend(function(Portlet){this.getLabel=function(){return this.getInstanceLabel()
};
this.setPlaceholderPosition=function(placeholderPosition){this._props.placeholderPosition=placeholderPosition
};
this.refresh=function(){$Xie._Service.update(this._props.windowURL)
};
Portlet._MIXINS=["Placeable"];
Portlet._PROPS=["asyncContent","instanceLabel","placeholderPosition","windowURL"]
});
$._Content=$.HookedContext.extend();
$.Theme=$.PresentationContext.extend(function(Theme){this.getWindow=function(){var element=this.getMarkupElement();
var theme=null;
$Util.Dom.eachDescendantLinear(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx&&(ctx instanceof $.Window)){theme=ctx;
proceed=false
}return proceed
},"div");
return theme
};
Theme._PROPS=["usingAltSkeleton","usingAltSkin","markupName","name"]
});
$.Titlebar=$.PresentationContext.extend(function(Titlebar){this.getButtons=function(){var element=this.getMarkupElement();
var buttons=[];
$Util.Dom.eachDescendantRecursive(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){if(ctx.getType()==$._Content.TYPE){proceed=false
}else{if(ctx instanceof $.AbstractButton){buttons.push(ctx)
}}}return proceed
},"div");
return buttons
};
Titlebar._PROPS=["icon"]
});
$.AbstractButton=$.PresentationContext.extend(function(AbstractButton){AbstractButton._PROPS=["altText","image","name","rolloverImage"]
});
$.Button=$.AbstractButton.extend({});
$.ToggleButton=$.AbstractButton.extend({})
}})
});
bea.wlp.disc.Module._include("_Mixin","bea.wlp.disc.context",function(){bea.wlp.disc.Module.contribute({require:["bea.wlp.disc._util"],declare:function($){var $Util=bea.wlp.disc._util;
$._Common={getParentPage:function(){var element=this.getMarkupElement();
var page=null;
$Util.Dom.eachAncestor(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx&&(ctx instanceof $.Page)){page=ctx;
proceed=false
}return proceed
});
return page
},getParentBook:function(){var element=this.getMarkupElement();
var book=null;
$Util.Dom.eachAncestor(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx&&(ctx instanceof $.Book)){book=ctx;
proceed=false
}return proceed
});
return book
},getParentPlaceholder:function(){var element=this.getMarkupElement();
var placeholder=null;
$Util.Dom.eachAncestor(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx){if((ctx instanceof $.Layout)||(ctx instanceof $.Window)){proceed=false
}else{if(ctx.getType()==$.Placeholder.TYPE){placeholder=ctx;
proceed=false
}}}return proceed
});
return placeholder
},getPlaceables:function(){var element=this.getMarkupElement();
var placeables=[];
$Util.Dom.eachDescendantRecursive(element,function(el){var ctx=el[$._HookContextProperty];
var proceed=true;
if(ctx&&((ctx.getType()==$.Book.TYPE)||(ctx.getType()==$.Portlet.TYPE))){placeables.push(ctx);
proceed=false
}return proceed
},"div");
return placeables
}};
$._Mixin={Placeable:{}};
$._Mixin.Placeable.getParentPlaceholder=$._Common.getParentPlaceholder
}})
})
});