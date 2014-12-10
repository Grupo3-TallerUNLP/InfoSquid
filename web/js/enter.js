var f = $('input');
f.on('keydown', function(event){
	if (event.keyCode == 13){
		event.preventDefault();
		event.stopPropagation();
}});
