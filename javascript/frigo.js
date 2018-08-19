function ajouter(id, mesure, nom){
	var quantite;
	switch(mesure){
		case 'U':
			quantite = prompt('Combien voulez-vous ajouter de ' + nom + ' ? ');
			break;
		case 'P':
			quantite = prompt('Combien voulez-vous ajouter de kilos de '+ nom + ' ?');
			break;
		case 'L':
			quantite = prompt('Combien voulez-vous ajouter de litres de ' + nom + ' ?');
			break;
	}

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

	xhr.open("POST", document.location.href + '&ajax_action=ajouter', true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	xhr.send("id="+id+"&quantite="+quantite);


}

function supprimer(){

}