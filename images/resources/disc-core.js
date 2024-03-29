(function(){var $=new function(){var $=this;
$.Config={_scriptPath:"framework/scripts/",_combine:function(a,b){return(a&&b?a+"/"+b:(a?a:b||"")).replace(/\/{2,}/,"/")
},setContextPath:function(contextPath){this._contextPath=contextPath
},getContextPath:function(){return(this._contextPath||null)
},getScriptPath:function(scriptName){return this._combine(this._contextPath,this._combine(this._scriptPath,scriptName))
}};
$._Object={mixin:function(dest,src){this.each(src,function(name,item){dest[name]=item
},function(name,item){return(dest[name]!=item)
});
return dest
},each:function(src,iterator,when){if(src){function it(name,item){if(!when||when(name,item)){iterator(name,item)
}}var ts="toString",vo="valueOf";
for(var name in src){if(src.hasOwnProperty(name)){ts=(name==ts?null:ts);
vo=(name==vo?null:vo);
it(name,src[name])
}}if(ts&&src.hasOwnProperty(ts)){it(ts,src[ts])
}if(vo&&src.hasOwnProperty(vo)){it(vo,src[vo])
}}},map:function(){var size=0;
var data={};
return{size:function(){return size
},put:function(key,value){var prev=data[key];
var prevExists=prev!=null;
var valueExists=value!=null;
valueExists?data[key]=value:delete data[key];
size+=(valueExists&&!prevExists?1:(!valueExists&&prevExists?-1:0));
return prev
},get:function(key){return data[key]
},remove:function(key){return this.put(key,null)
},each:function(iterator,when){$._Object.each(data,function(key){iterator(key,data[key])
},when)
}}
}};
$.Console=new function(){var listeners=[];
listeners.push(function(op,args){if(listeners.length==1){if(window.console&&console[op]){console[op].apply(console,args)
}else{if(op=="error"&&args.length>0){alert(op.toUpperCase()+": "+args.join(", "))
}}}});
this.addListener=function(listener){if(typeof listener=="function"){listeners.push(listener)
}};
this.removeListener=function(listener){for(var i=0,len=listeners.length;
i<len;
i++){if(listeners[i]===listener){listeners.splice(i,1);
break
}}};
var ops=["assert","count","debug","dir","dirxml","error","group","groupEnd","info","log","profile","profileEnd","time","timeEnd","trace","warn"];
var self=this;
for(var i=0;
i<ops.length;
i++){this[ops[i]]=function(op){return function(){var i,len,args=[];
for(i=0,len=arguments.length;
i<len;
i++){args.push(arguments[i])
}for(i=0,len=listeners.length;
i<len;
i++){listeners[i].call(self,op,args)
}}
}(ops[i])
}};
$.Class={create:function(decl,_statics){var ctor=$._Object.mixin(function(){if(typeof this.initialize=="function"){this.initialize.apply(this,arguments)
}},_statics);
decl=decl||{};
ctor.prototype=(typeof decl=="function")?new decl(ctor):decl;
$.Class._inherit(ctor.prototype,decl.prototype||{});
ctor.prototype.constructor=ctor;
$._Object.mixin(ctor,$.Class._mixin);
return ctor
},_inherit:function(self,parent){$._Object.each(self,function(p){self[p]=$.Class._wrap(self[p],p,parent)
},function(p){return(self[p]!==Object.prototype[p]&&self[p]!==parent[p]&&typeof self[p]=="function")
})
},_wrap:function(override,name,parent){var supRegExp=/\Wsup\s*(\(|[\'\"\[\.][^=]*?\()/;
return supRegExp.test(override)?function(){var saved=this.sup;
this.sup=parent[name];
try{return override.apply(this,arguments)
}finally{this.sup=saved
}}:override
},_mixin:{extend:function(subdecl){if(typeof subdecl!="function"){var obj=subdecl;
subdecl=function(){$._Object.mixin(this,obj)
}
}subdecl.prototype=this.prototype;
return $.Class.create(subdecl,this)
},inject:function(nameOrSrc,value){if(typeof nameOrSrc=="string"){var old=this.prototype[nameOrSrc];
this.prototype[nameOrSrc]=$.Class._wrap(value,"prop",{prop:old});
this.prototype[nameOrSrc]._base=old
}else{var self=this;
$._Object.each(nameOrSrc,function(prop){self.inject(prop,nameOrSrc[prop])
},function(prop){return(self[prop]!=nameOrSrc[prop])
})
}},remove:function(nameOrSrc){if(typeof nameOrSrc=="string"){var old=this.prototype[nameOrSrc]._base;
delete this.prototype[nameOrSrc];
if(old&&old!==this.prototype[nameOrSrc]){this.prototype[nameOrSrc]=old
}}else{var self=this;
$._Object.each(nameOrSrc,function(prop){self.remove(prop)
})
}}}};
$._Request=$.Class.create(function(_Request){var sources=[function(){return new XMLHttpRequest()
},function(){return new ActiveXObject("Msxml2.XMLHTTP")
},function(){return new ActiveXObject("Microsoft.XMLHTTP")
},function(){return new ActiveXObject("Msxml2.XMLHTTP.4.0")
}];
var index=(location.protocol=="file:"&&window.ActiveXObject?[1,0,2,3]:[0,1,2,3]);
this.initialize=function(method,uri,async){for(var i=0;
i<index.length;
i++){try{this._xhr=sources[index[i]]();
index.splice(0,index.length,index[i]);
break
}catch(ignore){}}this._xhr.open(method,uri,async);
this._async=async
};
this.send=function(callback,error,data){var called,doError,xhr=this._xhr;
function update(){if(xhr.readyState==4){called=true;
var s=xhr.status;
if(((s==0||s==undefined)&&location.protocol!="file:")||(s>1&&s<200)||(s>=300&&s!=304)){if(error){this._async?error(xhr):doError=true
}}else{if(callback){callback.call(xhr,xhr)
}}}}xhr.onreadystatechange=update;
try{xhr.send(data||null)
}catch(e){error(xhr,e)
}if(!this._async){if(!called){update()
}if(error&&doError){error(xhr)
}}}
});
$._Resource=$.Class.create(function(){this.initialize=function(uri){this._uri=uri
};
this.getText=function(){if(!this._text){var self=this;
new $._Request("GET",this._uri,false).send(function(xhr){if(xhr.readyState==4){self._text=xhr.responseText
}},function(xhr,e){var error;
if(e){error=new Error((e&&e.message)||"Unspecified error");
error.cause=e
}else{error=new Error(xhr.status+": "+self._uri)
}error.status=xhr.status;
error.statusText=xhr.statusText;
error.responseText=xhr.responseText;
throw error
})
}return this._text
};
this.evaluate=function(){if(this.getText()){return eval(this._text)
}}
});
$.Catalog=$.Class.create(function(Catalog){this.initialize=function(module){this._module=module;
this._lookup={}
};
this.load=function(l10nData){this._lookup=l10nData||{}
};
this.localize=function(msg){return(msg?Catalog._L(this).apply(this,arguments):null)
};
this._resolve=function(msg){return(this._lookup[msg]||(function(self){var parent=self._module.$meta.getParent();
var parentCat=parent.$meta&&parent.$meta.getCatalog();
return(parentCat&&parentCat._resolve(msg))
})(this)||msg)
};
Catalog._L=function(catalog){return function(){var args=arguments;
return(catalog._resolve(args[0])||"").replace(/\{(\d)\}/g,function(str,index){index=+index+1;
return(args.length>index?new String(args[index]):str)
})
}
}
});
$.Module=function(_path){var self=this;
this.$meta={_name:_path,_facets:[],_initializers:[],_includes:$._Object.map(),_tryRealized:function(){if(this._includes.size()==0&&this._initializers.length>0){var init;
while((init=this._initializers.shift())){init.call(self,self,$.Catalog._L(this._catalog))
}}},getName:function(sub){var prefix=this._name+".";
return(sub?(sub.indexOf(prefix)===0?sub:prefix+sub):this._name)
},getParent:function(){return this._parent
},getCatalog:function(){return this._catalog
},isDeclared:function(){return !!this._isDeclared
}};
this.toString=function(){return this.$meta.getName()
}
};
var registry={};
$.Module.find=function(name){var found=registry[name];
if(!found){var parts=name.split(".");
found=registry[parts.slice(0,-1).join(".")];
found=(found&&found[parts[parts.length-1]])
}return found
};
$.Module.isDefined=function(nameOrModule){var module=(typeof nameOrModule=="string"?registry[nameOrModule]:nameOrModule);
return !!(module&&module.$meta&&module.$meta._isDeclared)
};
$.Module.create=function(name,decl){var module=$.Module._create(name);
if(!module.$meta._isDeclared){module.$meta._catalog=new $.Catalog(module);
module.$meta._isDeclared=true;
var include=$.Module._include;
$.Module._include=function(facetName,host){module.$meta._includes.put(facetName,true);
arguments.callee.orig.call($.Module,facetName,(host||module))
};
$.Module._include.orig=(include.orig||include);
var initializer=$.Module._initializer;
$.Module._initializer=function(init,_module){arguments.callee.orig.call($.Module,init,(_module||module))
};
$.Module._initializer.orig=(initializer.orig||initializer);
var use=$.Module.use;
var modules;
if(!use.orig){modules=$._Object.map();
modules.put(name,module);
$.Module.use=function(name){modules.put(name,arguments.callee.orig.call($.Module,name));
return modules.get(name)
};
$.Module.use.orig=use
}try{$.Module._realize(module,decl)
}finally{$.Module._include=include;
$.Module._initializer=initializer;
if(!use.orig){$.Module.use=use;
modules.each(function(key,value){value.$meta._tryRealized()
})
}}}return module
};
$.Module.contribute=function(decl,_moduleName,_facetName){var module=$.Module.use(_moduleName);
if(!module.$meta._facets[_facetName]){delete decl.include;
$.Module._realize(module,decl);
module.$meta._facets[_facetName]=true
}};
$.Module.use=function(name){if(!$.Module.isDefined(name)){$.Module._load(name)
}return(registry[name]||$.Module._create(name))
};
$.Module._include=function(facetName,host,decl){host=(typeof host=="string"?$.Module.use(host):host);
if(!host.$meta._facets[facetName]){var contribute=$.Module.contribute;
$.Module.contribute=function(decl,moduleName,fName){var name=(moduleName||host.$meta._name);
contribute.call($.Module,decl,name,(fName||facetName))
};
try{decl?decl():$.Module._load(host.$meta._name,facetName)
}finally{$.Module.contribute=contribute
}host.$meta._includes.remove(facetName)
}};
$.Module._initializer=function(initializer,_module){if(typeof initializer=="function"){_module.$meta._initializers.push(initializer)
}};
$.Module._bundle=function(bundle){var i,len;
var uses=[];
var use=$.Module.use;
$.Module.use=function(name){uses.push(name);
return $.Module._create(name)
};
var includes={};
var include=$.Module._include;
$.Module._include=function(facetName,host,decl){includes[host+"|"+facetName]=decl
};
var creates={};
var createIncludes=[];
var create=$.Module.create;
$.Module.create=function(name,decl){var createInclude=$.Module._include;
$.Module._include=function(facetName,host){createIncludes.push([facetName,host])
};
try{create.call($.Module,name,decl);
creates[name]=true
}finally{$.Module._include=createInclude
}};
try{bundle()
}finally{$.Module.use=use;
$.Module._include=include;
$.Module.create=create
}for(i=0,len=createIncludes.length;
i<len;
i++){var decl=includes[createIncludes[i][1].$meta._name+"|"+createIncludes[i][0]];
createIncludes[i].push(decl);
var initializer=$.Module._initializer;
$.Module._initializer=(function(module){return function(init,_module){initializer.call($.Module,init,(_module||module))
}
})(createIncludes[i][1]);
try{include.apply($.Module,createIncludes[i])
}finally{$.Module._initializer=initializer
}}for(i=0,len=uses.length;
i<len;
i++){if(!creates[uses[i]]){$.Module.use(uses[i])
}}$._Object.each(creates,function(key){registry[key].$meta._tryRealized()
})
};
$.Module._detach=function(nameOrModule){var name=(nameOrModule instanceof $.Module?nameOrModule.$meta._name:nameOrModule);
if(registry[name]){$._Object.each(registry,function(mod){if(registry[mod] instanceof $.Module&&registry[mod].$meta.getName().indexOf(name+".")==0){delete registry[mod]
}});
delete registry[name];
eval("try { delete window."+name+"; } catch (e) { window[name] = undefined; }")
}};
$.Module._load=function(module,facet){var uri=module.replace(/\./g,"/")+"/"+(facet||"module")+".js";
uri=$.Config.getScriptPath(uri);
try{new $._Resource(uri).evaluate(true);
return true
}catch(e){}return false
};
$.Module._create=function(name){var module;
if(typeof name=="string"&&name.length>0){module=registry[name];
if(!module){module=(function(path){var module=registry[path];
if(!module){module=new $.Module(path);
registry[path]=module
}if(!module.$meta._parent){var parts=path.split(".");
path=parts.slice(0,parts.length-1).join(".");
module.$meta._parent=(path.length>0?arguments.callee(path):window);
module.$meta._parent[parts[parts.length-1]]=module
}return module
})(name)
}}return module
};
$.Module._realize=function(module,decl){if(module){if(decl&&(decl.require||decl.include||decl.declare||decl.initialize)){var i;
decl.require=(typeof decl.require=="string"?[decl.require]:decl.require);
for(i=0;
decl.require instanceof Array&&i<decl.require.length;
i++){$.Module.use(decl.require[i])
}decl.include=(typeof decl.include=="string"?[decl.include]:decl.include);
for(i=0;
decl.include instanceof Array&&i<decl.include.length;
i++){$.Module._include(decl.include[i])
}if(decl.initialize){$.Module._initializer(decl.initialize)
}if(typeof decl.declare=="function"){decl.declare.call(module,module,$.Catalog._L(module.$meta._catalog))
}else{$._Object.mixin(module,decl.declare)
}}else{$._Object.mixin(module,decl)
}}}
};
$.Module.create("bea.wlp.disc",$)
})();