<?php
require_once("classes/fenetre.php");

class Recette extends Fenetre
{

	public function __construct(){
    if(isset($_GET["ajax_action"])){
      $this->trait_ajax();
    } else {
      $this->trait_html();
    }
  }

  public function trait_ajax(){
    switch ($_GET["ajax_action"]){
      case "supprimer":
        $this->content = $this->delete_data($_POST["id"]);
      break;
    }
  }
	public function trait_html(){
    $this->header  = $this->generate_header();
    $this->content = $this->generate_contenu();  //"page recette"
	}

  private function generate_header(){
    return '<script src="javascript/recette.js"></script>';
  }

  public function delete_data($id){
    $mysqli_delete = Database::$mysqli->prepare("DELETE FROM recette WHERE id = ?");
    $mysqli_delete->bind_param("i",$id);
    if(!$mysqli_delete->execute()){
      if($mysqli_delete->errno==1451){
        echo "1451";
      }
    }

      $mysqli_delete->close();
  }

  public function generate_contenu(){
      $mysqli_selectAll = Database::$mysqli->prepare("SELECT id, nom, descriptif from recette order by nom");
      $mysqli_selectAll->execute();
      $mysqli_selectAll->bind_result($id, $nom, $descriptif);
      $html = '<table style="margin: auto;"><caption>LISTE DE RECETTES:</caption>
        <thead>
          <tr>
            <th>NOM</th><th>DESCRIPTIF</th><th>supprimer</th><th>editer</th>
          </tr>
        </thead>
        <tbody>';
      $compteur=0;
      $col = "couleur_ligne";
      while ($mysqli_selectAll->fetch()) {
        $compteur++;
        if(($compteur % 2) == 1){ //Si impaire
          $col = 'couleur_ligne';
        } else {
          $col = '';
        }
        $html = $html.
          '<tr class='.$col.'>
            <td style="text-align: center">'.$nom.'</td>
            <td style="text-align: center">'.$descriptif.'</td>
            <td style="text-align: center"><img class="icon" src="images/delete.png" onclick="supprimer('.$id.');"</td>
            <td style="text-align:center"><img class="icon" src="images/edit.png" onclick="document.location.href = \'index.php?page=recette_new&id_recette='.$id.'\';"></td>
          </tr>';
      }

      $html = $html.'</tbody>
      </table>';

    return $html;
  }

}
?>