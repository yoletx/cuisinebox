function ajouter(id, mesure){
	var xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function() {
	  if (xhr.readyState == XMLHttpRequest.DONE ) {
	    if(xhr.status == 200){
	      console.log(xhr.responseText);
	      document.location.href = document.location.href;
	    } else if(xhr.status == 400) {
	      console.log('There was an error 400');
	    } else {
	      console.log('something else other than 200 was returned');
	    }
	  }
	}

	xhr.open("POST", 'classes/frigo.php&ajax_action=ajouter', true);

	xhr.send("&id="+id+"&mesure="+mesure);


}

function supprimer(){

}