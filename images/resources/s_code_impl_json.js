var paramValues = new Array();
var s_account = "";
var ajaxURL = location.protocol+"//"+window.location.hostname;
//var isloggedin = getCookie("isloggedin");
var IsLoggedin = getCookie("IsLoggedin");
var numFiltersApplied = 0;
var _topUrl = window.location.search.substring(1);
var _topUrlFilter = false;

if((typeof(_appId) != "undefined") && _appId != "sp" && _appId != "eservice" && _appId != "amt" && _appId != "legacy"){
    if ((IsLoggedin != null && IsLoggedin != "")) {
    ajaxURL = ajaxURL.concat("/portal/osecure");
    }
    else
    {
    ajaxURL = ajaxURL.concat("/portal/opublic");
    }
    getOmJSON();
}

function getOmJSON()
{
$.ajax({
    type: "GET",
    url: ajaxURL,
    async: true,
    dataType: "json",
    success: function(data){
        if(data != undefined) {
            setOmnitureParams(data);
        }
    }
});
}

function setOmnitureParams(data)
{
    paramValues[0]= data.p0;
    paramValues[1]= data.p1;
    paramValues[2]= data.p2;
    paramValues[3]= data.p3;
    paramValues[4]= data.p4;
    paramValues[5]= data.p5;
    paramValues[6]= data.p6;
    paramValues[7]= data.p7;
    paramValues[8]= window.location.href;
    paramValues[9]= data.p9;
    paramValues[10]= data.p10;
    paramValues[11]= data.p11;
    paramValues[12]= data.p12;
    s_setUrl(); 
    sendOmnitureValues(); 
}


function sendOmnitureValues()
{
        $.getScript('/NOW/public/js/s_code.js', function() {
        submitOmnitureRequest();
    });
}

function submitOmnitureRequest()
{
    //alert("s_account="+s_account);
    var _appName = _appId;
    s.server = "NSS";
    s.pageType = "jsp";
    s.pageName = document.title;
    
    s.prop1 = getUrlContext();
    s.prop2 = getUrlContext2();
    s.prop20 = paramValues[1];
    s.prop3 = paramValues[2];
    s.prop4 = paramValues[1];
    s.prop25 = paramValues[6];
    
    s.eVar20 = s.prop20;
    s.eVar25 = s.prop25;
    s.eVar39 = s_account;

    if("es" == _appName)
    {
    	_topUrlFilter = false;
    	var reqflds = unescape(getQueryVariable("requiredfields"));
        if (("undefined" == reqflds) || ("" == reqflds))
        {
            s.prop6 = "No Content Filters Applied";
            s.prop7 = page_query+":"+ "No Content Filters Applied";
            s.prop8 = "No Product Filters Applied";
            s.prop9 = page_query+":"+ "No Product Filters Applied";
            numFiltersApplied = 0;
        }
        else
        {
            setSearchFilterOmnitureValues(reqflds);

        }
        s.channel = "Search";
        s.prop5 = page_query;
        s.prop22 = page_site;
	s.prop23 = numResults;
        s.eVar1 = page_query;
        s.eVar23 = s.prop22;
        s.eVar24 = "+1";
	s.eVar10 = numFiltersApplied +"";
        s.events = "event8,event18";
    } else { 
        if (typeof(_section) != "undefined"){    
            s.channel = _section;
        } else
            s.channel = "eservice";
        s.prop21 = paramValues[12];
        s.eVar21 = s.prop21;
        s.eVar22 = "+1";
    }
    s.t();
}

function setSearchFilterOmnitureValues(_requiredFieldsTokens)
{
    //parse filter tokens    
    numFiltersApplied = 0;
    if(_topUrlFilter == false){
	    if(_requiredFieldsTokens.indexOf("cot:") > -1 )
	    {
			_parseSearchFilterTypeValues (_requiredFieldsTokens);
	    }
	    else
	    {
			s.prop6 = "No Content Filters Applied";
		    s.prop7 = page_query+":"+ "No Content Filters Applied";
	    }

	    if(_requiredFieldsTokens.indexOf("ptnt:") > -1 )
	    {
			_parseSearchFilterProductValues (_requiredFieldsTokens);
	    }
	    else
	    {
            s.prop8 = "No Product Filters Applied";
            s.prop9 = page_query+":"+ "No Product Filters Applied";
	    }
	    //count the number of filters applied.
	    //s.eVar10 = numFiltersApplied;
	} else {
		_parseSearchFilterValuesFromUrl (_requiredFieldsTokens);
	}
}

function _parseSearchFilterValuesFromUrl(_requiredFieldsTokens)
{
    //alert("Top URl Parsing=" + _requiredFieldsTokens);
    if(_requiredFieldsTokens != null && _requiredFieldsTokens.length > 0)
    {
    	var cotStr = "";
    	var cosStr = "";
    	//doc tab, bug too
    	if(_requiredFieldsTokens == "cot:product+documentation")
    	{
		cotStr = "product documentation|";
		s.prop6 = cotStr;
		s.prop7 = page_query+":"+cotStr;
		numFiltersApplied = 1;
		s.prop8 = "No Product Filters Applied";
		s.prop9 = page_query+":"+ "No Product Filters Applied";
    	
    	} else if(_requiredFieldsTokens == "cos:bug")
    	{
		cotStr = "troubleshooting and support|";
		costStr = "bug";
		s.prop6 = cotStr + costStr;
		s.prop7 = page_query+":"+ s.prop6;
		numFiltersApplied = 2;
		s.prop8 = "No Product Filters Applied";
		s.prop9 = page_query+":"+ "No Product Filters Applied";
    	} else {
    		//forward to regualr, to handle advanced..etc
    		_topUrlFilter = false;
    		setSearchFilterOmnitureValues(_requiredFieldsTokens);
    	}

    }
}

function _formatFilterProductValues(ptntFlds, _ptvrtFlds)
{

    //alert(ptntFlds.join('\n'))
    //alert(_ptvrtFlds.join('\n'))

    var prop8Str = "";
    var _numPtnt = 0;
    var _numPtvrt = 0;
    if(ptntFlds != null && ptntFlds.length > 0)
    {
        _numPtvrt = ptntFlds.length;
        for (var i = 0; i < _numPtvrt; i++) {
            var _ptnt = ptntFlds[i];
            var _ptvrtFound = false;
            if(_ptvrtFlds != null && _ptvrtFlds.length > 0)
            {
                _numPtnt = _ptvrtFlds.length;
                for(var j=0; j<_numPtnt; j++)
                {
                    var cos = _ptvrtFlds[j];
                    if (cos.indexOf(_ptnt) != -1)
                    {
                        prop8Str+= _ptnt+"|"+cos.substring(cos.indexOf("~")+1, cos.length)+";";
                        _ptvrtFound = true;
                    }
                }
            }
            if(_ptvrtFound == false)
            {
                //alert("no child node found for "+ _ptnt);
                prop8Str+=_ptnt+"|;";
            }

        }
        //remove the extra semicoln in the end
        if(prop8Str.indexOf(";", prop8Str.length - 1) !== -1)
        {
            prop8Str = prop8Str.substring(0, prop8Str.length - 1);
        }

        //set the filter properties
        //alert("prop8Str=" + prop8Str);
        s.prop8 = prop8Str;
        s.prop9 = page_query+":"+prop8Str;
        numFiltersApplied += _numPtvrt + _numPtnt;
    }
}

function _formatFilterTypeValues(_cotFlds, _cosFlds)
{

    //alert(_cotFlds.join('\n'))
    //alert(_cosFlds.join('\n'))

    var prop6Str = "";
    var _numCos = 0;
    var _numCot = 0;
    if(_cotFlds != null && _cotFlds.length > 0)
    {
        _numCot = _cotFlds.length;
        for (var i = 0; i < _numCot; i++) {
            var cot = _cotFlds[i];
            var cosFound = false;
            if(_cosFlds != null && _cosFlds.length > 0)
            {
                _numCos = _cosFlds.length;
                for(var j=0; j<_numCos; j++)
                {
                    var cos = _cosFlds[j];
                    if (cos.indexOf(cot) != -1)
                    {
                        prop6Str+= cot+"|"+cos.substring(cos.indexOf("~")+1, cos.length)+";";
                        cosFound = true;
                    }
                }
            }
            if(cosFound == false)
            {
                //alert("no child node found for "+ cot);
                prop6Str+=cot+"|;";
            }

        }
        //remove the extra semicoln in the end
        if(prop6Str.indexOf(";", prop6Str.length - 1) !== -1)
        {
            prop6Str = prop6Str.substring(0, prop6Str.length - 1);
        }

        //set the filter properties
        //alert("prop6Str=" + prop6Str);
        s.prop6 = prop6Str;
        s.prop7 = page_query+":"+prop6Str;
        numFiltersApplied += _numCot + _numCos;
    }
}

function _parseSearchFilterProductValues(_requiredFieldsTokens)
{
    //alert(requiredFieldsTokens);
    var _ptvrtFlds = new Array();
    var _ptntFlds = new Array();

    _requiredFieldsTokens = _requiredFieldsTokens.replace(/\(/g, "");
    _requiredFieldsTokens = _requiredFieldsTokens.replace(/\)/g, "");
    _requiredFieldsTokens = _requiredFieldsTokens.substring(_requiredFieldsTokens.indexOf("ptnt:"), _requiredFieldsTokens.length);

    //alert(_requiredFieldsTokens);
    var _reqFldsAndArr = _requiredFieldsTokens.split(".pt");
    if(_reqFldsAndArr != null && _reqFldsAndArr.length > 0)
    {
        //displayElements(_reqFldsAndArr);
        var _currentProduct = "";
        $.each(_reqFldsAndArr, function(index, _metaTagAndSplit){
            if(_metaTagAndSplit.indexOf("|") > -1 ){
                var _metaTagOrSplit = _metaTagAndSplit.split("|");
                $.each(_metaTagOrSplit, function(index, _metaTag){
                    var _decodedMetaTag = utf8Decode(unescape(_metaTag));
                    //alert("*IF _decodedMetaTag= "+ _decodedMetaTag);
                    //if content type
                    if(_decodedMetaTag.indexOf("ptnt:") != -1)
                    {
                        _currentProduct = _decodedMetaTag.substring(5,_decodedMetaTag.length);
                        _ptntFlds.push(_currentProduct);
                    }
                    else if(_decodedMetaTag.indexOf("ptvrt:") != -1)
                    {
                        //if sub type
                        _ptvrtFlds.push(_currentProduct+"~"+_decodedMetaTag.substring(6,_decodedMetaTag.length));
                    }
                    else if(_decodedMetaTag.indexOf("vrt:") != -1)
                    {
                        //if sub type
                        _ptvrtFlds.push(_currentProduct+"~"+_decodedMetaTag.substring(4,_decodedMetaTag.length));
                    }
                    else
                    {
                        //alert("If Never here "+ _decodedMetaTag);
                    }
                });
            }else{
                    var _decodedMetaTag = utf8Decode(unescape(_metaTagAndSplit));
                    //alert("*Else _decodedMetaTag= "+ _decodedMetaTag);
                    if(_decodedMetaTag.indexOf("ptnt:") != -1)
                    {
                        _currentProduct = _decodedMetaTag.substring(5,_decodedMetaTag.length);
                        _ptntFlds.push(_currentProduct);
                    }
                    else if(_decodedMetaTag.indexOf("ptvrt:") != -1)
                    {
                        //if sub type
                        _ptvrtFlds.push(_currentProduct+"~"+_decodedMetaTag.substring(6,_decodedMetaTag.length));
                    }
                    else if(_decodedMetaTag.indexOf("vrt:") != -1)
                    {
                        //if sub type
                        _ptvrtFlds.push(_currentProduct+"~"+_decodedMetaTag.substring(4,_decodedMetaTag.length));
                    }
                    else
                    {
                        //alert("Else Never here "+ _decodedMetaTag);
                    }
            }
        });
    }
    else{
        //alert("product string not found");
    }

    if(_ptntFlds != null && _ptntFlds.length > 0)
    {
        _formatFilterProductValues(_ptntFlds, _ptvrtFlds);
    }
}

function _parseSearchFilterTypeValues(_requiredFieldsTokens)
{
    var _cosFlds = new Array();
    var _cotFlds = new Array();

    _requiredFieldsTokens = _requiredFieldsTokens.replace(/\(/g, "");
    _requiredFieldsTokens = _requiredFieldsTokens.replace(/\)/g, "");

    if(_requiredFieldsTokens.indexOf("ptnt:") > 0)
    {
        //-1 is to remove the extra . AND
        _requiredFieldsTokens = _requiredFieldsTokens.substring(0, (_requiredFieldsTokens.indexOf("ptnt:") - 1));
    }

    //alert(_requiredFieldsTokens);
    var _reqFldsAndArr = _requiredFieldsTokens.split(".");
    if(_reqFldsAndArr != null && _reqFldsAndArr.length > 0){
        var _currentCot = "";
        $.each(_reqFldsAndArr, function(index, _metaTagAndSplit){
            if(_metaTagAndSplit.indexOf("|") > -1 ){
                var _metaTagOrSplit = _metaTagAndSplit.split("|");
                $.each(_metaTagOrSplit, function(index, _metaTag){
                    var _decodedMetaTag = utf8Decode(unescape(_metaTag));
                    //_cotFlds[index] = _decodedMetaTag;
                    //if content type
                    if(_decodedMetaTag.indexOf("cot:") != -1)
                    {
                        _currentCot = _decodedMetaTag.substring(4,_decodedMetaTag.length);
                        _cotFlds.push(_currentCot);
                        //_cosFlds.push(_currentCot);
                    }
                    else if(_decodedMetaTag.indexOf("cos:") != -1)
                    {
                        //if sub type
                        _cosFlds.push(_currentCot+"~"+_decodedMetaTag.substring(4,_decodedMetaTag.length));
                    }
                });
            }else{
                    var _decodedMetaTag = utf8Decode(unescape(_metaTagAndSplit));
                    if(_decodedMetaTag.indexOf("cot:") != -1)
                    {
                        _currentCot = _decodedMetaTag.substring(4,_decodedMetaTag.length);
                        _cotFlds.push(_currentCot);
                        //_cosFlds.push(_currentCot);
                    }
                    else if(_decodedMetaTag.indexOf("cos:") != -1)
                    {
                        //if sub type
                        _cosFlds.push(_currentCot+"~"+_decodedMetaTag.substring(4,_decodedMetaTag.length));
                    }
            }
        });
    }

    if(_cotFlds != null && _cotFlds.length > 0)
    {
        _formatFilterTypeValues(_cotFlds, _cosFlds);
    }
}


function getQueryVariable(variable) {
    var query = "";
    if(typeof(ajaxUrl) != "undefined" && ajaxUrl != null && ajaxUrl.length > 0){
    	query = ajaxUrl;
    }else {
    	query = _topUrl;
    	_topUrlFilter = true;
    }
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == variable) {
            //alert("Found="+decodeURIComponent(pair[1]));
            return decodeURIComponent(pair[1]);
        }
    }
}


function s_setUrl(){
  var url,userType,customerType,agreementtype;
  var isEmployee=false,partner=false;
     url = paramValues[8];
     userType = paramValues[9];

    if (userType !=null)
    {
        userType = userType.toLowerCase();
    }
    
     customerType = paramValues[10];
    if (customerType != null)
    {
        customerType = customerType.toLowerCase();
    }    

    agreementtype =  paramValues[11];
    if (agreementtype != null)
    {
        agreementtype = agreementtype.toLowerCase();
    }
     
     // start modification araghave -04212009 (from Internal to internal)
    if (userType != null && userType.indexOf('ou=internal')>-1) {
        isEmployee = true;
    }
    else {
        if((typeof(customerType) != "undefined" && customerType.indexOf('reseller')>-1) ||
            ((typeof(agreementtype) != "undefined" && agreementtype.indexOf('rsp company')>-1)) ||
        ((typeof(agreementtype) != "undefined" && agreementtype.indexOf('asp company')>-1)))        {
    partner = true;
     }
    }
    
    //from tag manager params are coming empty, so add default url
    if((typeof(url) == "undefined") || "" == url)
    {
        url = window.location.href;
    }
    
    if(isEmployee) {
     if(url != null && (url.indexOf('/support.netapp.com')>-1))  {
        s_account = "networkapplsupport-employee,networkapplsupport-global";
      }   else  {
        s_account = "networkapplsupport-employee-dev,networkapplsupport-global-dev";
      }
    } else {
      if(url != null && (url.indexOf('/support.netapp.com')>-1))  {
         if(partner)   {
           s_account = "networkapplsupport-partner,networkapplsupport-global";
           }  else   {
        s_account = "networkapplsupport-customer,networkapplsupport-global";
           }
       }  else  {
        if(partner)     {
          s_account ="networkapplsupport-partner-dev,networkapplsupport-global-dev";
         }    else     {
         s_account = "networkapplsupport-customer-dev,networkapplsupport-global-dev";
        }  
       } 
      }
}

function getUrlContext()
{
    var url = window.location.href;
    var urlcontext = "";
    var pos = url.indexOf("com/");
    if(pos > -1)
    {
    var remurl = url.substring(pos+4, url.length);
    var posend = remurl.indexOf("/");
    urlcontext = remurl.substring(0, posend);
    }
   return urlcontext;
}

function getUrlContext2()
{
    var url = window.location.href;
        var urlcontext = "";
        var pos = url.indexOf("com/");
        if(pos > -1)
        {
        var remurl = url.substring(pos+4, url.length);
        var posend = remurl.indexOf("/");
        urlcontext = remurl.substring(0,remurl.length);
    var posquestion = urlcontext.indexOf("?");
    if(posquestion == -1)
    {
      posquestion = remurl.length;
    }
    urlcontext = remurl.substring(0,posquestion); 
        }
   return urlcontext;
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
