function jbLightAlert(ttl, msg, drag) {
	drag = typeof(drag) != 'undefined' ? drag : false;
	 	box = new LightFace({
	 		title: ttl,
	 		width: 400,
	 		draggable: drag,
	 		content: msg,
			buttons: [
						{
							title: Joomla.JText._('COM_JBLANCE_CLOSE'),
							event: function() { this.close(); }
						}
					]
	 	}).open();
}

function confirmAction(ttl, msg, dellink) {
 	box = new LightFace({
 		title: ttl,
 		width: 400,
 		content: msg,
		buttons: [
					{ 
						title: Joomla.JText._('COM_JBLANCE_YES'), 
						event: function() {  
							box.fade(0.5);
			 				location.href=dellink;
						},
						color: 'blue'
					},
					{
						title: Joomla.JText._('COM_JBLANCE_CLOSE'),
						event: function() { this.close(); }
					}
				]
 	}).open();
 }

function insufficientFund(msg) {
 	box = new LightFace({
 		title: Joomla.JText._('COM_JBLANCE_INSUFFICIENT_FUND'), 
 		width: 400,
 		content: msg,
		buttons: [
					{ 
						title: Joomla.JText._('COM_JBLANCE_DEPOSIT_FUNDS'), 
						event: function() {  
							box.fade(0.5);
							location.href = link_deposit;
						},
						color: 'blue'
					},
					{
						title: Joomla.JText._('COM_JBLANCE_CLOSE'),
						event: function() { this.close(); }
					}
				]
 	}).open();
 }