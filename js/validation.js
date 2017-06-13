function hrefBack() {
	history.go(-1);	
}

function displayCursor()
{
	document.frmLogin.userName.focus();
}	

function validationLogin()
{
	if(document.getElementById('userName').value=="")
	{
		alert("Please enter username");
		document.getElementById('userName').focus();
		return false;
	}
		
	if(document.getElementById('userPassword').value=="")
	{
		alert("Please enter valid password");
		document.getElementById('userPassword').focus();
		return false;
	}
}


function validationAddAdmin()
{ 
	document.getElementById('chk_userName').innerHTML = '';
	document.getElementById('chk_userEmail').innerHTML = '';
	document.getElementById('chk_password').innerHTML = '';
	
	if(document.frmUser.userName.value=="")
	{
		document.getElementById('chk_userName').innerHTML = 'Please enter username';
		document.frmUser.userName.focus();
		return false;
	} 

	if(document.frmUser.userEmail.value=="")
	{
		document.getElementById('chk_userEmail').innerHTML = 'Please enter email Id';
		document.frmUser.userEmail.focus();
		return false;
	}

	var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	str = document.getElementById('userEmail').value;
	if(!str.match(emailRegEx)){
		document.getElementById('chk_userEmail').innerHTML = 'Please enter valid email Id';
		document.frmUser.userEmail.focus();
		return false;		
	}
	
	if(document.frmUser.password.value=="")
	{
		document.getElementById('chk_password').innerHTML = 'Please enter password';
		document.frmUser.password.focus();
		return false;
	}
		
	if(document.frmUser.password.value.length<6)
	{
		document.getElementById('chk_password').innerHTML = "It should atleast 6 character";
		document.frmUser.password.select();
		document.frmUser.password.focus();
		return false;
	}
}

function validationEditAdmin()
{ 
	document.getElementById('chk_userName').innerHTML = '';
	document.getElementById('chk_userEmail').innerHTML = '';
	document.getElementById('chk_password').innerHTML = '';
	
	if(document.frmUser.userName.value=="")
	{
		document.getElementById('chk_userName').innerHTML = 'Please enter username';
		document.frmUser.userName.focus();
		return false;
	} 

	if(document.frmUser.userEmail.value=="")
	{
		document.getElementById('chk_userEmail').innerHTML = 'Please enter email Id';
		document.frmUser.userEmail.focus();
		return false;
	}

	var emailRegEx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	str = document.getElementById('userEmail').value;
	if(!str.match(emailRegEx)){
		document.getElementById('chk_userEmail').innerHTML = 'Please enter valid email Id';
		document.frmUser.userEmail.focus();
		return false;		
	}
			
	if(document.frmUser.password.value!="" && document.frmUser.password.value.length<6)
	{
		document.getElementById('chk_password').innerHTML = "It should atleast 6 character";
		document.frmUser.password.select();
		document.frmUser.password.focus();
		return false;
	}
}


function validationCategory()
{
	if(document.getElementById('categoryName').value=="")
	{
		document.getElementById('chk_categoryName').innerHTML = "Please enter category name";
		document.getElementById('categoryName').focus();
		return false;
	}		
}