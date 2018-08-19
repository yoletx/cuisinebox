<?php
require_once("classes/fenetre.php");

class Ingredient extends Fenetre
{

  private $erreur = '';

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
      if(isset($_POST['nom']) && isset($_POST['mesure'])){  //est ce que nom et mesure existent?, ils sont été renseignées dans le form?
        $mysqli_select = Database::$mysqli->prepare("SELECT COUNT(*) FROM ingredient WHERE nom = ?");  //le moule est prêt
        $mysqli_select->bind_param("s",$_POST['nom']);  //on rempli le moule avec la variable -> nom rensiegné
        $mysqli_select->execute();                      //on mets le moule dans le four
        $mysqli_select->bind_result($nb_nom);           //on lie/associe
        $mysqli_select->fetch();                        //le curseur se place sur le premiere enregistrement
        $mysqli_select->close();
        if($nb_nom==1){
          $this->erreur = "impossible d'ajouter l'ingredient '".$_POST['nom']."' car il existe déjà";
        } else {
          $mysqli_insert = Database::$mysqli->prepare("INSERT INTO ingredient(nom, mesure) VALUES (?,?)");
          $mysqli_insert->bind_param("ss",$_POST['nom'], $_POST['mesure']);
          $mysqli_insert->execute();
        }

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
          <input type="submit" name="valider"> </br></br>
          <span class="erreur_doublon titre">'.$this->erreur.'</span>
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