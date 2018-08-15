<?php
require_once("classes/fenetre.php");

class Ingredient extends Fenetre
{

	public function __construct(){
		$this->content = $this->generateFormulaire();



	}

	public function generateFormulaire(){

		 $formulaire = '<form method="post" action="traitement.php">
    	<p>
        <label for ="nom">Nom de l\'ingredient</label> : <input type="text" name="nom" id="nom" placeholder="Ex : riz" size="30" maxlenght="10"/>

        <br />

        <span class="titre"> Type d\'ingredient :</span>
					<div>
            <input type="radio" id="U" name="drone" checked />
            <label for="U">En unité</label>
       		</div>

        	<div>
            <input type="radio" id="P" name="drone" />
            <label for="P">En poids</label>
        	</div>

        	<div>
            <input type="radio" id="L" name="drone" />
            <label for="L">En litres</label>
    	</p>
			<table>
  			<tr>
					<td>Carmen</td>
					<td>33 ans</td>
					<td>Espagne</td>
   			</tr>
   			<tr>
					<td>Michelle</td>
					<td>26 ans</td>
					<td>États-Unis</td>
   			</tr>
			</table>

		</form>';



		return $formulaire;
	}

}
?>