<?php
require_once("classes/fenetre.php");

class RecetteNew extends Fenetre
{
	private $reloadString = '';

	public function __construct(){
		if(isset($_POST["nom"]) && isset($_POST["descriptif"])){
			$this->enregistrerRecette();
		}


	  if(isset($_GET["ajax_action"])){
      $this->trait_ajax();
    } else {
      $this->trait_html();
    }
	}

	function enregistrerRecette(){
		$stm = Database::$mysqli->prepare("SELECT count(*) FROM recette WHERE nom=?");
		$stm->bind_param('s',$_POST["nom"]);
		$stm->execute();
		$stm->bind_result($cnt);
		$stm->close();
		if($cnt == 0){ //Si pas de recette avec ce nom, on va la créer
			$stm = Database::$mysqli->prepare("INSERT INTO recette (nom, descriptif, dejeuner, diner) VALUES (?,?,?,?)");
			$dejeuner = 0;
			if(isset($_POST["dejeuner"])){
				$dejeuner = 1;
			}
			$diner = 0;
			if(isset($_POST["diner"])){
				$diner = 1;
			}

			$stm->bind_param("ssii",$_POST["nom"], $_POST["descriptif"], $dejeuner,$diner);
			$stm->execute();
			$id = $stm->insert_id;
			$stm->close();
		}
		$this->reloadString = '<script>document.location.href=\'index.php?page=recette_new&id_recette='.$id.'\'</script>';
	}

  //-------------------------
  //-----------AJAX----------
  //-------------------------
  private function trait_ajax(){

    switch($_GET["ajax_action"]){
      case "ajouter":
        $id = $_POST["id"];
        $quantite = $_POST["quantite"];
        $id_recette = $_GET["id_recette"];

        $stm = Database::$mysqli->prepare("SELECT count(*) FROM ingredient_recette WHERE id_recette = ? AND id_ingredient = ?");
        $stm->bind_param("ii",$id_recette, $id);
        $stm->execute();
        $stm->bind_result($cnt);
        $stm->fetch();
        $stm->close();
        if($cnt == 1){ //Si on a déjà cet ingrédient dans la recette alors on lui ajoute de la quantité
          $stm = Database::$mysqli->prepare("SELECT quantite FROM ingredient_recette WHERE id_recette = ? AND id_ingredient = ?");
        	$stm->bind_param("ii",$id_recette, $id);
          $stm->execute();
          $stm->bind_result($quantite_old);
          $stm->fetch();
          $stm->close();

          $stm = Database::$mysqli->prepare("UPDATE ingredient_recette SET quantite = ? WHERE id_recette = ? AND id_ingredient = ?");
          $nouvelle_quantite = $quantite+$quantite_old;
          $stm->bind_param("dii", $nouvelle_quantite, $id_recette, $id);
          $stm->execute();
          $stm->close();
        } else { //Sinon on l'ajoute avec la valeur entrée
          $stm = Database::$mysqli->prepare("INSERT INTO ingredient_recette (id_recette,id_ingredient,quantite) VALUES (?,?,?)");
          $stm->bind_param("iid", $id_recette, $id, $quantite);
          $stm->execute();
          $stm->close();
        }
        break;
      case "supprimer":
        $id = $_POST["id"];
        $id_recette = $_GET["id_recette"];

        $stm = Database::$mysqli->prepare("DELETE FROM ingredient_recette WHERE id_recette = ? AND id_ingredient = ?");
        $stm->bind_param("ii",$id_recette, $id);
        $stm->execute();
        $stm->close();
        break;
      case "modifier":
      echo "aaa";
        $id = $_POST["id"];
        $quantite = $_POST["quantite"];
        $id_recette = $_GET["id_recette"];

        $stm = Database::$mysqli->prepare("UPDATE ingredient_recette SET quantite = ? WHERE id_recette = ? AND id_ingredient = ?");
        $stm->bind_param("dii",$quantite,$id_recette, $id);
        $stm->execute();
        $stm->fetch();
        $stm->close();
        echo "quanttie = ".$quantite;
        echo "idrecette = ".$id_recette;
        echo "id = ".$id;
        break;
    }
  }

  //-------------------------
  //-----------HTML----------
  //-------------------------

  private function trait_html(){
    $this->header  = $this->generate_header();
    $this->content = $this->generate_content();
  }
  private function generate_header(){
    return '<script src="javascript/recette_new.js"></script>';
  }

	function generate_content(){
		$html = '
			<div class="recette_main">
				<div class="recette_form">'.$this->generate_form().'</div>';

		if(isset($_GET["id_recette"])){
			$html .= '
				<div class="recette_all_ingredients">'.$this->generateAllIngr().'</div>
				<div class="recette_selected_ingredients">'.$this->generateSelectedIngr().'</div>';
		}

		$html .= '</div>';
		return $html.$this->reloadString;
	}

	function generate_form(){
		$nom = '';
		$descriptif = '';
		$dejeuner = '';
		$diner = '';
		if(isset($_GET["id_recette"])){
			$stm = Database::$mysqli->prepare("SELECT nom, descriptif, dejeuner, diner FROM recette WHERE id=?");
			$stm->bind_param('i', $_GET["id_recette"]);
			$stm->execute();
			$stm->bind_result($nom, $descriptif,$dejeuner, $diner);
			$stm->fetch();
			$stm->close();
		}

		$dejeunerCheck = "";
		$dinerCheck = "";
		if($dejeuner==1){
			$dejeunerCheck = "checked=\"checked\"";
		}
		if($diner==1){
			$dinerCheck = "checked=\"checked\"";
		}


		return '
			<form method="post">
				<label for="nom">Recette : </label><input type="text" id="nom" name="nom" placeholder="Nom recette" value="'.$nom.'"/><br/>
				<textarea name="descriptif" placeholder="Descriptif" style="width:500px;height:100px">'.$descriptif.'</textarea><br/>
				<label for="dejeuner">Disponible pour déjeuner</label><input type="checkbox" id="dejeuner" name="dejeuner" '.$dejeunerCheck.'><br/>
				<label for="diner">Disponible pour diner</label><input type="checkbox" id="diner" name="diner" '.$dinerCheck.'>
				<input type="submit" value="Valider">
			</form>';
	}

  private function generateAllIngr(){
    $res =  Database::$mysqli->query("SELECT id, nom, mesure FROM ingredient ORDER BY nom");
    $html = "<table>";
    $html.= " <thead>";
    $html.= "   <tr><th>Nom</th><th>Ajouter</th></tr>";
    $html.= " </thead>";
    $html.= " <tbody>";
    while($row = $res->fetch_assoc()){
      $html.= " <tr>";
      $html.= "   <td>".$row["nom"]."</td>";
      $html.= "   <td style=\"text-align:center\"><img class=\"icon\" src=\"images/add.png\" onclick=\"ajouter(".$row["id"].",'".$row["mesure"]."','".$row["nom"]."');\"/></td>";
      $html.= " </tr>";
    }
    $html.= " </tbody>";
    $html.= "</table>";
    return $html;
  }

  private function generateSelectedIngr(){
    $stm =  Database::$mysqli->prepare("
    		SELECT
    			ingredient.id AS id,
    			ingredient.nom AS nom,
    			ingredient.mesure AS mesure,
    			recette.id AS id_recette,
    			ingredient_recette.quantite AS quantite
    		FROM ingredient_recette
    		INNER JOIN recette ON recette.id = id_recette
    		INNER JOIN ingredient ON id_ingredient = ingredient.id
    		WHERE recette.id = ?
    		ORDER BY nom");

   	$stm->bind_param("i",$_GET["id_recette"]);
   	$stm->execute();
   	$stm->bind_result($id, $nom, $mesure, $id_recette, $quantite);

    $html = "<table>";
    $html.= " <thead>";
    $html.= "   <tr><th></th><th>Nom</th><th>Quantité</th><th></th></tr>";
    $html.= " </thead>";
    $html.= " <tbody>";
    while($stm->fetch()){
      $html.= " <tr>";
      $html.= "   <td style=\"text-align:center\"><img class=\"icon\" src=\"images/delete.png\" onclick=\"supprimer(".$id.");\"/></td>";
      $html.= "   <td>".$nom."</td>";
      $html.= "   <td>".Util::formater_quantite($quantite,$mesure)."</td>";
      $html.= "   <td style=\"text-align:center\"><img class=\"icon\" src=\"images/edit.png\" onclick=\"edit(".$id.",'".$mesure."','".$nom."');\"/></td>";
      $html.= " </tr>";
    }
    $html.= " </tbody>";
    $html.= "</table>";
    return $html;
  }

}
?>