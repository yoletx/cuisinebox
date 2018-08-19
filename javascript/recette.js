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

	xhr.open("POST", document.location.href, true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

	xhr.send("nom="+nom+"&descriptif="+descriptif);