
var xmlHttp;

function getobject()
{
	var xmlHttp=null;
	try
	{
		xmlHttp=new XMLHttpRequest;
	}
	catch(e)
	{
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP")	;
		}	
	}
	return xmlHttp;
}


function changeStatus(divID , id , action)
{
 	xmlhttp=getobject();
	var query="id="+id+"&action="+action;
 	document.getElementById(divID).innerHTML='<img src="images/loading_icon.gif">';
	xmlhttp.onreadystatechange=function()
	{
		
		if (xmlhttp.readyState==4)
		{	
			//alert(xmlhttp.responseText);
			//alert("urvesh");
			//alert(divID);
			document.getElementById(divID).innerHTML = xmlhttp.responseText;
		}
	}

	xmlhttp.open("GET","pass.php?type=changestatus&"+query,true);
	xmlhttp.send(null);	
}


function takeBackup()
{
	xmlhttp=getobject();
	var query="";
	document.getElementById("showMsg").innerHTML='<img src="images/indicator.gif">';
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4)
		{							
			document.getElementById("showMsg").innerHTML = "<font size='3'>DATABASE BACKUP IS COMPLETED.</font><br><b>It is stored in -- "+xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","pass.php?action=backup&type=takeBackup&"+query,true);
	xmlhttp.send(null);	
}

function getAllProducts(keyword)
{	
	xmlhttp=getobject();
	var query="keyword="+keyword+"&action=coupon";
	document.getElementById("divAllProducts").innerHTML='<img src="images/loading_icon.gif">';
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4)
		{				
			document.getElementById("divAllProducts").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","pass.php?type=getAllProducts&"+query,true);
	xmlhttp.send(null);	
}
 
   function find_randomNumber(){
      
	 	  xmlhttp=getobject();
	 	  	var query="?action=managePsychologist&type=generateNumber";
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4)
		{							
			var chek = xmlhttp.responseText;			
			document.getElementById("Password").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","pass.php"+query,true);
	xmlhttp.send(null);	
	 
 }

function screenshot_del(id,tbl)
{	
	var r=confirm("Are you sure you want to delete Image");
	if (r==true)
		{
			$.get('pass.php?action=manageUser&type=screenshot_del',{imgid: id,table: tbl} , function(data) {	 																																														            		alert(data);	
					window.location.reload();																		  
			});				
		}
	else
		{
			alert("You pressed Cancel!");
		} 
	
}


function takeProduct(id)
{
	xmlhttp=getobject();
	var query="id="+id;
	
//	pid = (($("#product_created > option:selected").val() == "") ? 0 : $("#product_created > option:selected").val());
	pid = 0;
	cid = (($("#author_created > option:selected").val() == "") ? 0 : $("#author_created > option:selected").val());
	
	$("#author_hidden").val(cid);
	
	checkSelected = $('#product_created').attr('selected');
	
	if(checkSelected == undefined)
	{
		pname = '--No Product--';
	}
	else{
		pname = $("#product_created > option:selected").text();
	}
	
	$("#brand_txt").html('<img src="images/indicator.gif">');
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4)
		{						
			//alert(xmlhttp.responseText);
			var obj = jQuery.parseJSON(xmlhttp.responseText);
			
			var op = '';
			
			if(obj.bname == '' || obj.bname == null)
			{
				bname = '--No Brand--';	
			}
			else{
				bname = obj.bname;	
			}
			
			bid = ((obj.bid == "") ? 0 : obj.bid); 
			pid = ((obj.pid == "") ? 0 : obj.pid);
			
		//	var pname = obj.pname;
		
			if($.isArray(obj.op)) {			
				
				for(i=0; i<obj.op.length; i++) {
					op += obj.op[i];
				}
				
			} else {
				op = obj.op;
			}	
			
			
			$("#brand_txt").text(bname);			
			$("#product_hidden").val(pid);		
			$("#brand_hidden").val(bid);			
			$("#product_txt").text(pname);
			
			$("#brand_txt").attr('title',bname);
			$("#product_txt").attr('title',pname);
			
			if(pid == '' && pid == 'undefined')
			{
				$("#product_txt").text('--No Product--');
			}
			
			
			$('#brand_created > option').removeAttr('selected').each(function(idx, item) {  
				if($(this).val() == bid)
				{
					$(this).attr('selected', 'selected');
				}
			});
			
			document.getElementById("product_created").innerHTML = op;
		}
	}
	
	xmlhttp.open("GET","pass.php?action=admin&type=getProduct&"+query+"&pid="+pid,false);
	xmlhttp.send(null);	
}

function setCaution(id)
    {
        var checkValue = $('.enableCaution'+id).is(":checked");
      
        $('#cautionLoader'+id).html('<img src="images/loading_icon.gif" />');
        /* alert("checked value is: "+checkValue);
        return false; */
        $.ajax({
            url:"pass.php",
            data:{action:"setCaution", type:"", packId:id, status:checkValue},
            type:"POST",
            success:function(response)
            {
               $('#cautionLoader'+id).html('');
              
               location.reload();
                return true;
            }
            
        });
        
    }

function getOciFullData(action,ociId,certifytype)
    {
        var ociaction = action;
        var typeid = ociId;
        var certifyBy = certifytype;
          $.ajax({
                url: "pass.php",
                data: {action: ociaction, OciType: typeid, certifyType: certifyBy},
                type: "POST",
                success: function(response)
                {
                    $('table tbody').replaceWith(response); 
                }

            });
        
    }




