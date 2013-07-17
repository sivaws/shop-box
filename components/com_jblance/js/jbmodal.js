function modalAlert(ttl, msg, drag) {
	drag = typeof(drag) != 'undefined' ? drag : false;
	
	var mbm = new MoobooModal({"btn_ok": Joomla.JText._('COM_JBLANCE_CLOSE')});
	mbm.show({
		"title": ttl,
		"contents": msg
	});
}

function modalConfirm(ttl, msg, dellink) {
	var mbm = new MoobooModal({"btn_ok": Joomla.JText._('COM_JBLANCE_YES')});
	mbm.addButton(Joomla.JText._('COM_JBLANCE_YES'), "btn btn-primary", function(){
		location.href = dellink;
		this.hide();
	});
	mbm.addButton(Joomla.JText._('COM_JBLANCE_CLOSE'), "btn");
	mbm.show({
		"model":"modal",
		"title": ttl,
		"contents": msg
	});
}