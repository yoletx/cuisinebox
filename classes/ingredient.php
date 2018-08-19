<?php
require_once("classes/fenetre.php");

class Ingredient extends Fenetre
{

  public function __construct(){
    $this->insert_data();
    $this->content = $this->generateContenu();



  }


  public function insert_data(){
    if(isset($_POST['nom']) && isset($_POST['mesure'])){
      $mysqli_insert = Database::$mysqli->prepare("INSERT INTO ingredient(nom, mesure) VALUES (?,?)");
      $mysqli_insert->bind_param("ss",$_POST['nom'], $_POST['mesure']);
      $mysqli_insert->execute();
    }
  }

  public function delete_data($nom, $mesure){
    $mysqli_delete = Database::$mysqli->prepare("DELETE FROM ingredient WHERE id = ?");
  }

  public function generateFormulaire(){

     return '
      <form method="post">
        <p style="text-align: center;">
          <label for="nom">Nom de l\'ingredient </label> : <input type="text" name="nom" id="nom" placeholder="Ex : riz" size="30"/> <br/>
          <span class="titre"> Type d\'ingredient :</span> <br /> <br />
          <input type="radio" name="mesure" id="U" value="U"/><label for="U">En unit&eacute; </label> <br />
          <input type="radio" name="mesure" id="P" value="P"/><label for="P">En poids</label> <br />
          <input type="radio" name="mesure" id="L" value="L"/><label for="L">En litres</label> <br />
          <input type="submit" name="valider">
        </p>
      </form>';
  }

  public function generateTableau(){
      $mysqli_selectAll = Database::$mysqli->prepare("SELECT nom, mesure from ingredient order by nom");
      $mysqli_selectAll->execute();
      $out_nom    = NULL;
      $out_mesure = NULL;
      $mysqli_selectAll->bind_result($out_nom, $out_mesure);
      $html = '<table><caption>Ingredients :</caption>
        <thead>
          <tr>
            <th>NOM</th><th>TYPE</th>
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
            <td>'.$out_nom.'</td>
            <td>'.$out_mesure.'</td>
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




