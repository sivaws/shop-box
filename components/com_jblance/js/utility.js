function checkAvailable(el){
	var inputstr = el.value;
	var name = el.name;
	var length = inputstr.length;
	if(inputstr.length > 0){
		var myRequest = new Request({
			url: 'index.php?option=com_jblance&task=guest.checkuser',
            method: 'post',
			data: {'inputstr':inputstr, 'name':name},
			onRequest: function(){ $('status_'+name).empty().removeProperty('class'); $('status_'+name).addClass('jbloading dis-inl-blk'); },
			onSuccess: function(response) {
				if(response == 'OK'){
					$('status_'+name).removeClass('jbloading').addClass('successbg');
					$('status_'+name).set('html', Joomla.JText._('COM_JBLANCE_AVAILABLE'));
				} 
				else {
					$('status_'+name).removeClass('jbloading').addClass('failurebg');
					$('status_'+name).set('html', response);
				}
           }
		});
		myRequest.send();
	}
}

function createUploadButton(userid, task){
	var uploader = document.getElementById('photoupload');
	upclick({
		element: uploader,
		action: 'index.php?option=com_jblance&task='+task,
		dataname: 'photo', 
		action_params: {'userid': userid},
		onstart: 
			function(filename){ $('ajax-container').empty().removeProperty('class').addClass('jbloading'); },
		oncomplete:
		function(response){ //alert(response);
			var resp = JSON.decode(response);
			if(resp['result'] == 'OK'){
				//document.location.href = resp['return'];
				//set the picture
				target = $('divpicture');
				target.set('html', '<img src='+resp['image']+'>');
				//set the thumb
				target = $('divthumb');
				target.set('html', '<img src='+resp['thumb']+'>');
				//set the crop image
				if($('cropframe')){
					$('cropframe').setStyle('background-image', 'url('+resp['image']+')');
					$('imglayer').setStyles({
						'background-image': 'url('+resp['image']+')',
					    width: resp['width'],
					    height: resp['height']
					});
					$('imgname').set('value', resp['imgname']);
					$('tmbname').set('value', resp['tmbname']);
				}
				$('ajax-container').removeProperty('class').addClass('successbg');
				$('ajax-container').set('html', resp['msg']);
			}
			else if(resp['result'] == 'NO'){
				$('ajax-container').removeProperty('class').addClass('failurebg');
				$('ajax-container').set('html', resp['msg']);
			}
		}
	});
}

function attachFile(elementID, task){
	var uploader = document.getElementById(elementID);
	
	upclick({
		element: uploader,
		action: 'index.php?option=com_jblance&task='+task,
		dataname: elementID, 
		action_params: {'elementID': elementID},
		onstart: function(filename){ $('ajax-container-'+elementID).empty().removeProperty('class'); $('ajax-container-'+elementID).addClass('jbloading'); },
		oncomplete:
			function(response){ //alert(response);
			var resp = JSON.decode(response);
			
			if(resp['result'] == 'OK'){
				$(elementID).setStyle('display', 'none');
				$('ajax-container-'+elementID).removeClass('jbloading').addClass('successbg');
				$('ajax-container-'+elementID).set('html', resp['msg']);
				var html = "<input type='checkbox' name='chk-"+elementID+"' checked value='1' />"+resp['attachname']+"<input type='hidden' name='attached-file-"+elementID+"' value='"+resp['attachvalue']+"'>";
				$('file-attached-'+elementID).set('html', html);
			}
			else if(resp['result'] == 'NO'){
				$('ajax-container-'+elementID).removeClass('jbloading').addClass('failurebg');
				$('ajax-container-'+elementID).set('html', resp['msg']);
			}
		}
	});
}

function removePicture(userid, task){
	var myRequest = new Request({
		url: 'index.php?option=com_jblance&task='+task,
		method: 'post',
		data: {'userid': userid },
		onRequest: function(){  $('ajax-container').empty().removeProperty('class');$('ajax-container').addClass('jbloading'); },
		onSuccess: function(responseText, responseXML){
			 var resp = JSON.decode(responseText);
		       
	          if(resp['result'] == 'OK'){
	          	  $('ajax-container').removeClass('jbloading').addClass('successbg');
	          	  $('ajax-container').set('html', resp['msg']);
	          }
	          else if(resp['result'] == 'NO'){
	          	  $('ajax-container').removeClass('jbloading').addClass('failurebg');
	          	  $('ajax-container').set('html', resp['msg']);
	          }
		}
	});
	myRequest.send();
}

function updateThumbnail(task){

	$('editthumb').setStyle('display', '');
	
	var ch = new CwCrop({
   	    minsize: {x: 64, y: 64},
   	    maxratio: {x: 2, y: 1},
   	    fixedratio: false,
   	 	onCrop: function(values){

   			var myRequest = new Request({
   			url: 'index.php?option=com_jblance&task='+task,
   			method: 'post',
   			data: {'cropW': values.w, 'cropH': values.h, 'cropX': values.x, 'cropY': values.y, 'imgLoc': $('imgname').get('value'), 'tmbLoc': $('tmbname').get('value')},
   			onRequest: function(){  $('tmb-container').empty().removeProperty('class');$('tmb-container').addClass('jbloading'); },
   			onSuccess: function(response){
   				var resp = JSON.decode(response);
   			    
				if(resp['result'] == 'OK'){
					//document.location.href = resp['return'];
   		          	$('tmb-container').removeClass('jbloading').addClass('successbg');
   		          	$('tmb-container').set('html', resp['msg']);
   		          }
   		          else if(resp['result'] == 'NO'){
   		          	  $('tmb-container').removeClass('jbloading').addClass('failurebg');
   		          	  $('tmb-container').set('html', resp['msg']);
   		          }
   			}
   		});
   		myRequest.send();
   	    }
   	});
}

function checkUsername(el){
	var inputstr = el.value;
	var name = el.name;
	var length = inputstr.length;
	if(inputstr.length > 0){
		var myRequest = new Request({
			url: 'index.php?option=com_jblance&task=membership.checkuser',
            method: 'post',
			data: {'inputstr':inputstr, 'name':name},
			onRequest: function(){ $('status_'+name).empty().removeProperty('class'); $('status_'+name).addClass('jbloading dis-inl-blk'); },
			onSuccess: function(response) {
				var resp = JSON.decode(response);
				if(resp['result'] == 'OK'){
					$('status_'+name).removeClass('jbloading').addClass('successbg');
					$('status_'+name).set('html', resp['msg']);
				} 
				else {
					$('status_'+name).removeClass('jbloading').addClass('failurebg');
					$('status_'+name).set('html', resp['msg']);
				}
           }
		});
		myRequest.send();
	}
}

function fillProjectInfo(){
	var project_id = $('project_id').value;
	var myRequest = new Request({
		url: 'index.php?option=com_jblance&task=membership.fillprojectinfo',
		method: 'post',
		data: {'project_id': project_id },
		onSuccess: function(responseText, responseXML){
			 var resp = JSON.decode(responseText);
	          if(resp['result'] == 'OK'){
	          	  $('recipient').set('value', resp['assignedto']);
	          	  $('proj_balance_div').set('html', resp['proj_balance_html']);
	          	  //if full payment is checked, set amount to bid amount. if payment is partial, set amount to balance amount
	          	  if($('full_payment_option').checked){
	          		  $('amount').set('value', resp['bidamount']);
	          	  }
	          	  else if($('partial_payment_option').checked){
	          		$('amount').set('value', resp['proj_balance']);
	          	  }
	          	$('proj_balance').set('value', resp['proj_balance']);
	          }
	          else if(resp['result'] == 'NO'){
	          	  //$('ajax-container').removeClass('jbloading').addClass('failurebg');
	          	 // $('ajax-container').set('html', resp['msg']);
	          }
		}
	});
	myRequest.send();
	
}

function processMessage(msgid, task){
	var myRequest = new Request({
		url: 'index.php?option=com_jblance&task='+task,
		method: 'post',
		data: {'msgid':msgid}, 
		onRequest: function(){ $('feed_hide_'+msgid).empty().addClass('jbloading'); },
		onComplete: function(response){
			if(response == 'OK'){
				$('jbl_feed_item_'+msgid).hide();
			} 
		}
	});
	myRequest.send();
}

function processFeed(userid, activityid, type){
	var myRequest = new Request({
		url: 'index.php?option=com_jblance&task=user.processfeed',
		method: 'post',
		data: {'userid':userid, 'activityid':activityid, 'type':type}, 
		onRequest: function(){ $('feed_hide_'+activityid).empty().addClass('jbloading'); },
		onComplete: function(response){
			if(response == 'OK'){
				$('jbl_feed_item_'+activityid).hide();
			} 
		}
	});
	myRequest.send();
}

function processForum(forumid, task){
	var myRequest = new Request({
		url: 'index.php?option=com_jblance&task='+task,
		method: 'post',
		data: {'forumid':forumid}, 
		//onRequest: function(){ $('tr_forum_'+forumid).empty().addClass('jbloading'); },
		onComplete: function(response){
			if(response == 'OK'){
				$('tr_forum_'+forumid).dispose();
			} 
		}
	});
	myRequest.send();
}