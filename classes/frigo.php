<?php
require_once("fenetre.php");

class frigo extends Fenetre {

	public function __construct(){
		$this->header = $this->generate_header();
		$this->content = $this->generate_content();
	}

	private function generate_header(){
		return '<script src="javascript/frigo.js"></script>';
	}

	private function generate_content(){
		return '
			<div class="conteneur">
				<div class="sous_conteneur">'.
					$this->genererTableauIngredients().'
				</div>
				<div class="sous_conteneur">'.
					$this->genererTableauFrigo().'
				</div>
			</div>
		';
	}

	private function genererTableauIngredients(){
		$res = 	Database::$mysqli->query("SELECT id, nom, mesure FROM ingredient ORDER BY nom");
		$html = "";
		$html .= "<table>";
		$html.= "	<thead>";
		$html.= "		<tr><th>Nom</th><th>Ajouter</th></tr>";
		$html.= "	</thead>";
		$html.= "	<tbody>";
		while($row = $res->fetch_assoc()){
			$html.= "	<tr>";
			$html.= "		<td>".$row["nom"]."</td>";
			$html.= "		<td style=\"text-align:center\"><img class=\"icon\" src=\"images/add.png\" onclick=\"ajouter(".$row["id"].",".$row["mesure"].");\"/></td>";
			$html.= "	</tr>";
		}
		$html.= "	</tbody>";
		$html.= "</table>";
		return $html;
	}

	private function genererTableauFrigo(){
		$res = 	Database::$mysqli->query("SELECT id, nom, mesure, quantite FROM frigo INNER JOIN ingredient ON id = id_ingredient ORDER BY nom");
		$html = "";
		$html.= "<table>";
		$html.= "	<thead>";
		$html.= "		<tr><th></th><th>Nom</th><th>Reste</th></tr>";
		$html.= "	</thead>";
		$html.= "	<tbody>";
		while($row = $res->fetch_assoc()){
			$html.= "	<tr>";
			$html.= "		<td style=\"text-align:center\"><img class=\"icon\" src=\"images/delete.png\" onclick=\"supprimer(".$row["id"].",".$row["quantite"].",".$row["mesure"].");\"/></td>";
			$html.= "		<td>".$row["nom"]."</td>";
			$html.= "		<td>".Util::formater_quantite($row["quantite"],$row["mesure"])."</td>";
			$html.= "	</tr>";
		}
		$html.= "	</tbody>";
		$html.= "</table>";
		return $html;
	}
}
?>