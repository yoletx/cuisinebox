<?php
require_once("fenetre.php");

class frigo extends Fenetre {

  public function __construct(){
    if(isset($_GET["ajax_action"])){
      $this->trait_ajax();
    } else {
      $this->trait_html();
    }

  }

  //-------------------------
  //-----------AJAX----------
  //-------------------------
  private function trait_ajax(){

    switch($_GET["ajax_action"]){
      case "ajouter":
        $id = $_POST["id"];
        $quantite = $_POST["quantite"];

        $stm = Database::$mysqli->prepare("SELECT count(*) FROM frigo WHERE id_ingredient = ?");
        $stm->bind_param("i",$id);
        $stm->execute();
        $stm->bind_result($cnt);
        $stm->fetch();
        $stm->close();
        if($cnt == 1){ //Si on a déjà cet ingrédient dans le frigo alors on lui ajoute de la quantité
          $stm = Database::$mysqli->prepare("SELECT quantite FROM frigo WHERE id_ingredient = ?");
          $stm->bind_param("i",$id);
          $stm->execute();
          $stm->bind_result($quantite_old);
          $stm->fetch();
          $stm->close();


          $stm = Database::$mysqli->prepare("UPDATE frigo SET quantite = ? WHERE id_ingredient = ?");
          $nouvelle_quantite = $quantite+$quantite_old;
          $stm->bind_param("di",$nouvelle_quantite,$id);
          $stm->execute();
          $stm->close();
        } else { //Sinon on l'ajoute avec la valeur entrée
          $stm = Database::$mysqli->prepare("INSERT INTO frigo (id_ingredient,quantite) VALUES (?,?)");
          $stm->bind_param("id",$id,$quantite);
          $stm->execute();
          $stm->close();
        }
        break;
      case "supprimer":
        $id = $_POST["id"];

        $stm = Database::$mysqli->prepare("DELETE FROM frigo WHERE id_ingredient = ?");
        $stm->bind_param("i",$id);
        $stm->execute();
        $stm->close();
        break;
      case "modifier":
        $id = $_POST["id"];
        $quantite = $_POST["quantite"];

        $stm = Database::$mysqli->prepare("UPDATE frigo SET quantite = ? WHERE id_ingredient = ?");
        $stm->bind_param("di",$quantite,$id);
        $stm->execute();
        $stm->fetch();
        $stm->close();
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
    $res =  Database::$mysqli->query("SELECT id, nom, mesure FROM ingredient ORDER BY nom");
    $html = "";
    $html .= "<table>";
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

  private function genererTableauFrigo(){
    $res =  Database::$mysqli->query("SELECT id, nom, mesure, quantite FROM frigo INNER JOIN ingredient ON id = id_ingredient ORDER BY nom");
    $html = "";
    $html.= "<table>";
    $html.= " <thead>";
    $html.= "   <tr><th></th><th>Nom</th><th>Reste</th><th></th></tr>";
    $html.= " </thead>";
    $html.= " <tbody>";
    while($row = $res->fetch_assoc()){
      $html.= " <tr>";
      $html.= "   <td style=\"text-align:center\"><img class=\"icon\" src=\"images/delete.png\" onclick=\"supprimer(".$row["id"].");\"/></td>";
      $html.= "   <td>".$row["nom"]."</td>";
      $html.= "   <td>".Util::formater_quantite($row["quantite"],$row["mesure"])."</td>";
      $html.= "   <td style=\"text-align:center\"><img class=\"icon\" src=\"images/edit.png\" onclick=\"edit(".$row["id"].",'".$row["mesure"]."','".$row["nom"]."');\"/></td>";
      $html.= " </tr>";
    }
    $html.= " </tbody>";
    $html.= "</table>";
    return $html;
  }
}
?>