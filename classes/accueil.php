<?php
require_once("classes/fenetre.php");

class Accueil extends Fenetre
{
	public function __construct(){
		$this->content = $this->generateContent();
	}

	function generateContent(){
		$dejeuner = 0;
		$diner = 0;
		if(date('H') < 14){
			$dejeuner = 1;
		} else {
			$diner = 1;
		}

		//Récupère les ids de recettes élligibles
		$stm = Database::$mysqli->prepare("
			SELECT r_filtre.id
			FROM
			(
				SELECT ir.id_recette id, count(*) nb
				FROM ingredient_recette ir
				INNER JOIN frigo f ON ir.id_ingredient = f.id_ingredient
				INNER JOIN recette r ON ir.id_recette = r.id
				WHERE dejeuner >= ? AND diner >= ?
				  AND f.quantite >= ir.quantite
				GROUP BY ir.id_recette
			) r_filtre
			INNER JOIN (
				SELECT ir.id_recette id, count(*) nb
				FROM ingredient_recette ir
				INNER JOIN frigo f ON ir.id_ingredient = f.id_ingredient
				INNER JOIN recette r ON ir.id_recette = r.id
				GROUP BY ir.id_recette
			) r_all ON r_filtre.id = r_all.id AND r_filtre.nb = r_all.nb");
			$stm->bind_param("ii", $dejeuner, $diner);
			$stm->execute();
			$stm->bind_result($id_recette);

			$tabIdRecette = array();
			while($stm->fetch()){
				$tabIdRecette[] = $id_recette;
			}
			$stm->close();
			if(count($tabIdRecette) == 0){
				return $this->generatePasDeRecette();
			} else {
				shuffle($tabIdRecette);
				return $this->generateRecettePossible($tabIdRecette[0]);
			}

	}

	function generatePasDeRecette(){
		return "Pas de recette possible, il va falloir remplir le frigo !";
	}

	function generateRecettePossible($id_recette){
		$stm = Database::$mysqli->prepare("SELECT nom, descriptif FROM recette WHERE id = ?");
		$stm->bind_param("i", $id_recette);
		$stm->execute();
		$stm->bind_result($nom, $descriptif);
		$stm->fetch();

		if(date('H') < 14){
			$html = '<span>Recette proposée pour ce midi : </span>';
		} else {
			$html = '<span>Recette proposée pour ce soir : </span>';
		}

		$html.= "<p>".$nom."</p>";
		$html.= "<p>".$descriptif."</p>";

		$stm->close();
		return $html;
	}
}
?>