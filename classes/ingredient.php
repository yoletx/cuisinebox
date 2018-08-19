<?php
require_once("classes/fenetre.php");

class Ingredient extends Fenetre
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
    $this->insert_data();
    $this->header  = $this->generate_header();
    $this->content = $this->generateContenu();
  }

  private function generate_header(){
    return '<script src="javascript/ingredient.js"></script>';
  }

  public function insert_data(){
    if(!empty($_POST['nom'])){
      if(isset($_POST['nom']) && isset($_POST['mesure'])){
        $mysqli_insert = Database::$mysqli->prepare("INSERT INTO ingredient(nom, mesure) VALUES (?,?)");
        $mysqli_insert->bind_param("ss",$_POST['nom'], $_POST['mesure']);
        $mysqli_insert->execute();
      }
    }
    else{
      echo 'impossible d inserer cet ingredient car doublon';
    }

  }

  public function delete_data($id){
    $mysqli_delete = Database::$mysqli->prepare("DELETE FROM ingredient WHERE id = ?");
    $mysqli_delete->bind_param("i",$id);
    if(!$mysqli_delete->execute()){
      if($mysqli_delete->errno==1451){
        echo "1451";
      }
    }

      $mysqli_delete->close();
  }

  public function generateFormulaire(){

     return '
      <form method="post">
        <p style="text-align: center;">
          <label for="nom">Nom de l\'ingredient </label> : <br/><br/><input type="text" name="nom" id="nom" placeholder="Ex : riz" size="20"/> <br/><br/>
          <span class="titre"> Type d\'ingredient :</span> <br /> <br />
          <input type="radio" name="mesure" id="U" value="U"/><label for="U">En unit&eacute; </label> <br/>
          <input type="radio" name="mesure" id="P" value="P"/><label for="P">En poids</label> <br/>
          <input type="radio" name="mesure" id="L" value="L"/><label for="L">En litres</label> <br/><br/>
          <input type="submit" name="valider">
        </p>
      </form>';
  }

  public function generateTableau(){
      $mysqli_selectAll = Database::$mysqli->prepare("SELECT id, nom, mesure from ingredient order by nom");
      $mysqli_selectAll->execute();
      $mysqli_selectAll->bind_result($out_id, $out_nom, $out_mesure);
      $html = '<table><caption>Ingredients :</caption>
        <thead>
          <tr>
            <th>NOM</th><th>TYPE</th><th>supprimer</th>
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
            <td style="text-align: center">'.$out_nom.'</td>
            <td style="text-align: center">'.$out_mesure.'</td>
            <td style="text-align: center"><img class="icon" src="images/delete.png" onclick="supprimer('.$out_id.');"</td>
          </tr>';
      }

      $html = $html.'</tbody>
      </table>';

    return $html;
  }

  public function generateContenu(){
    return '
      <div class="conteneur">
        <div class="sous_conteneur">'.$this->generateFormulaire().'</div>
        <div class="sous_conteneur">'.$this->generateTableau().'</div>
      </div>';
  }
}
?>