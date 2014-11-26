var f = $('input');
f.on('keydown', function(e){
	if (event.keyCode == 13){
		e.preventDefault();
		e.stopPropagation();
}});