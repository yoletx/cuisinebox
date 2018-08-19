function supprimer(id){

	var xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function() {
	  if (xhr.readyState == XMLHttpRequest.DONE ) {
	    if(xhr.status == 200){
	    	if(xhr.responseText == 1451){
	    		alert("Impossible de supprimer cet ingrédient il est déjà présent dans le frigo");
	    	} else {
	    		document.location.href = document.location.href;
	    	}
	    } else if(xhr.status == 400) {
	      console.log('There was an error 400');
	    } else {
	      console.log('something else other than 200 was returned');
	    }
	  }
	}

	xhr.open("POST", document.location.href + '&ajax_action=supprimer', true); //Ici on passe l'url avec les paramètres GET
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	xhr.send("id="+id); //Ici on passe les paramètres POST


}