function adminlogin(form)
{
	user = form.userid.value;
	pass = form.password.value;

	var error="";
	if(trim(user) == "")
	{
		error+="\t - Username\n";
		form.admin.focus();
	}
	if(trim(pass) == "")
	{
		error+="\t - Password";
	}
	if(error != "")
	{
		alert("Please enter the followings fields:\n"+"___________________________________________\n\n"+error+"\n___________________________________________");
		return false;
	}
}

// global variable
function trim(str, chars) 
{
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) 
{
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) 
{
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

var checked = false;
function checkedAll (frm) {
	if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < frm.elements.length; i++) {
		frm.elements[i].checked = checked;
	}
}

function emailvalidate(email) 
{
   return ((/^[A-Za-z]+[A-Za-z0-9_\.]*?\@[A-Za-z0-9]+((\.|-)[A-Za-z]+){1,2}$/).test(email));
}

function adminlogin(form)
{
	user = form.admin.value;
	pass = form.pass.value;

	var error="";
	if(trim(user) == "")
	{
		error+="\t - Username\n";
		form.admin.focus();
	}
	if(trim(pass) == "")
	{
		error+="\t - Password";
	}
	if(error != "")
	{
		alert("Please enter the followings fields:\n"+"___________________________________________\n\n"+error+"\n___________________________________________");
		return false;
	}
}

function forgotpassword(form)
{
	email = form.email.value;
	
	var error = "";
	if(trim(email) == "")
	{
		error+="\t - E-mail\n";
		form.email.focus();
	}
	if(trim(email) != "")
	{
		if(!emailvalidate(email))
		{
			error+="\t - E-mail\n";
			form.email.focus();
		}
	}
	if(error != "")
	{
		alert("Please enter the followings fields:\n"+"___________________________________________\n\n"+error+"\n___________________________________________");
		return false;
	}
}

function managepayment(form)
{
	packagename = form.pack_name.value;
	duration = form.duration.value;
	price = form.price.value;
	status=form.status.value;
	
	var error = "";
	if(trim(packagename) == "" || trim(packagename) == "0")
	{
		error+="\t - Package Name\n";
		form.pack_name.focus();
	}
	if(trim(duration) == "" || trim(duration) == "0")
	{
		error+="\t - Duration\n";
		form.duration.focus();
	}
	if(trim(price) == "" || trim(price) <= "0" || isNaN(price))
	{
		error+="\t - Price\n";
		form.price.focus();
	}
	if(error != "")
	{
		alert("Please enter/select the followings fields:\n"+"___________________________________________\n\n"+error+"\n___________________________________________");
		return false;
	}
}

function deleteAllCheckedPackages(frm)
{
	alert('a');
	return false;
	var field_name = '';	
	
	var count=0;
	for (var ctr=1; ctr < frm.length; ctr++)
	{
		if (frm.elements[ctr].checked)
		{
			field_name += frm.elements[ctr].value;
		}
		else
		{
			alert("Please select an Package");
			return false;
		}		
	}
	//window.location="../passnext.php?action=deleteall&type=1";
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checkAllCheckboxes(frm)
{ 

 var ctr=0;
 if(checked == false){checked = true}else{checked = false}
 for (var ctr= 0; ctr < frm.length; ctr++)
 {
  field_name = frm.elements[ctr].name;
  if (field_name.indexOf("chk") != -1)
  {
   if (frm.elements[ctr].checked == false)
    {
    frm.elements[ctr].checked = true;
    document.getElementById("check").style.display='none';
    document.getElementById("uncheck").style.display='block';
    
    }else{
    frm.elements[ctr].checked = true;
    document.getElementById("uncheck").style.display='none';
    document.getElementById("check").style.display='block';
    }
  }
 }

}

function uncheckAllCheckboxes(frm)
{ 
 
 for (ctr=0; ctr < frm.length; ctr++)
 {
  field_name = frm.elements[ctr].name;
  if (field_name.indexOf("chk") != -1)
  {
   //if (frm.elements[ctr].checked)
   //{
    frm.elements[ctr].checked = false;
   //}
  }
 }
 document.getElementById("uncheck").style.display='none';
 document.getElementById("check").style.display='block';
}


	function clickclear(thisfield, defaulttext) {
		if (thisfield.value == defaulttext) {
		thisfield.value = "";
		}
	}
	function clickrecall(thisfield, defaulttext) {
		if (thisfield.value == "") {
		thisfield.value = defaulttext;
		}
	}

