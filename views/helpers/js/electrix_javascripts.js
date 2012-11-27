// JavaScript Document
function postComment(itemid)
{
	arr_itemid = itemid.split("_");
	
	ip_elementid = 'postCommentFor_'+itemid;
	comment = document.getElementById(ip_elementid).value;
	
	if(comment == 'Posting comment... Please wait...')
		return false;
	
	//change the value and disable the comment box
	document.getElementById(ip_elementid).value = 'Posting comment... Please wait...';
	document.getElementById(ip_elementid).disabled=true;
	
	if(comment.length==0)
		return;
		
	var xmlhttp;
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			op_elementId = 'commentsFor_'+itemid;
			document.getElementById(op_elementId).innerHTML += xmlhttp.responseText;
			document.getElementById(ip_elementid).value = "";
			
			//enabling the comment box again and reset the value
			document.getElementById(ip_elementid).disabled=false;
			document.getElementById(ip_elementid).value="";
		}
	}
	
	xmlhttp.open("POST","AJAX_postComment.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("item_id="+itemid+"&comment="+comment);
	
	return false;
}

function deleteComment(commentid)
{
	op_elementid = 'comment'+commentid;
	
	linkElementId = 'deleteLink'+commentid;
	document.getElementById(linkElementId).style.display = "none";
	deleteStatusId = 'deleteStatus'+commentid;
	document.getElementById(deleteStatusId).innerHTML = "<span class='alert' style='font-size:80%'>Deleting...</span>";
	
	var xmlhttp;
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if(xmlhttp.responseText != "Error!")
				document.getElementById(op_elementid).style.display = "none";
			else
				document.getElementById(deleteStatusId).innerHTML = "<span class='alert' style='font-size:80%'>Error!</span>"
		}
	}
	
	xmlhttp.open("POST","AJAX_postComment.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("comment_id="+commentid);
	
	return false;
}

function like(itemType, itemId)
{
	op_elementId = 'like_'+itemType+itemId;
	
	//change the value and disable the comment box
	document.getElementById(op_elementId).innerHTML = 'Unlike';
	document.getElementById(op_elementId).setAttribute('onclick' , 'unlike('+itemType+', '+itemId+')');
	
	var xmlhttp;
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
                                    xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if('like_success' != xmlhttp.responseText)
			{
				document.getElementById(op_elementId).innerHTML = 'Like';
				document.getElementById(op_elementId).setAttribute('onclick' , 'like('+itemType+', '+itemId+')');
			}
		}
	}
	
	xmlhttp.open("POST","AJAX_like.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("item_id="+itemId+"&item_type="+itemType+"&like_type=like");
	
	return false;
}

function unlike(itemType, itemId)
{
	op_elementId = 'like_'+itemType+itemId;
	
	//change the value and disable the comment box
	document.getElementById(op_elementId).innerHTML = 'Like';
	document.getElementById(op_elementId).setAttribute('onclick' , 'like('+itemType+', '+itemId+')');
	
	var xmlhttp;
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			if('like_success' != xmlhttp.responseText)
			{
				document.getElementById(op_elementId).innerHTML = 'Unike';
				document.getElementById(op_elementId).setAttribute('onclick' , 'unlike('+itemType+', '+itemId+')');
			}
		}
	}
	
	xmlhttp.open("POST","AJAX_like.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("item_id="+itemId+"&item_type="+itemType+"&like_type=unlike");
	
	return false;
}


function createSelection(field, start, end)
{
        if( field.createTextRange )
		{
            var selRange = field.createTextRange();
            selRange.collapse(true);
            selRange.moveStart('character', start);
            selRange.moveEnd('character', end);
            selRange.select();
        }
		else if( field.setSelectionRange )
		{
            field.setSelectionRange(start, end);
        }
		else if( field.selectionStart )
		{
            field.selectionStart = start;
            field.selectionEnd = end;
        }
        field.focus();
}

function changeLabel(formid, buttonid)
{
	document.getElementById(buttonid).value = 'Please wait';
	document.getElementById(buttonid).disabled = 'disabled';
	document.getElementById(formid).submit();
}

function sendNewsletter()
{
	document.getElementById("send_nl_button").disabled = "disabled";
	var nl_from = document.getElementById("nl_from").value;
	var nl_subject = document.getElementById("nl_subject").value;
	var nl_message = document.getElementById("nl_message").value;
	
	var op_elementId = "nl_status";
	
	if(nl_from.length == 0 || nl_subject.length == 0 || nl_message == 0)
	{
		document.getElementById(op_elementId).value = "<span class='alert'>Cannot send incomplete message!</span>";
		return false;
	}
	
	var xmlhttp;
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			nl_status_msg = xmlhttp.responseText;
			document.getElementById(op_elementId).innerHTML = nl_status_msg;
			document.getElementById("send_nl_button").disabled = false;
		}
	}
	
	document.getElementById(op_elementId).innerHTML = "Sending... Please wait...";
	xmlhttp.open("GET","AJAX_send_newsletter.php?displayname="+nl_from+"&subject="+nl_subject+"&news_message="+nl_message,true);
	xmlhttp.send();
	
	return false;
}

function confirmDeleteMsgs(formId)
{
	result = confirm("Are you sure you want to delete the selected messages?");
	if(result)
		document.getElementById(formId).submit();
}

function sendPrivateMessage()
{
	msg_to = document.getElementById('msg_to').value;
	msg_subject = document.getElementById('msg_subject').value;
	msg_body = document.getElementById('msg_body').value;
	
	if(msg_to.length == 0 || msg_subject.length == 0 || msg_body.length == 0)
	{
		alert("You cannot send an empty message!");
		return false;
	}
	if(msg_body.length > 200)
	{
		alert("Write a message in less than 200 letters!");
		return false;
	}
	document.getElementById('msg_send').value = 'Please wait';
	document.getElementById('msg_send').disabled = 'disabled';
	document.getElementById('compose_msg').submit();
}

function selectAllMsgs(checkbox_name)
{
	checkBoxes = document.getElementsByName(checkbox_name);
	count = checkBoxes.length;
	for(i=0;i<count;i++)
	{
		checkBoxes.item(i).checked = document.getElementById('select_all_mailbox').checked;
	}
}

function isValidEmail(email)
{
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	if(reg.test(email) == false)
		return false;
	else
		return true;
}

function showSuggestionBox()
{
    var offset = $('#msg_to').offset();
        
    var suggest_el = '<ul class="autoSuggest" style="left: '+offset.left+'px;"></ul>';

    $('#msg_to').parent('td').append(suggest_el);
}

function removeSuggestionBox()
{
    $('ul.autoSuggest').remove();
}