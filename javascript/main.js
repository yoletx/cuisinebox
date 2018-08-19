function goto(action, ev){
	document.location.href = 'index.php?page='+action;
	event.stopPropagation();
}