<?php
require_once("classes/fenetre.php");

class Ingredient extends Fenetre
{

  public function __construct(){
    $this->content = $this->generateContenu();


  }

  public function generateFormulaire(){

     return '
      <form method="post">
        <p style="text-align: center;">
          <label for="nom">Nom de l\'ingredient </label> : <input type="text" name="nom" id="nom" placeholder="Ex : riz" size="30"/> <br/>
          <span class="titre"> Type d\'ingredient :</span> <br /> <br />
          <input type="radio" name="mesure" id="U" value="U"/><label for="U">En unit&eacute; </label> <br />
          <input type="radio" name="mesure" id="P" value="P"/><label for="P">En poids</label> <br />
          <input type="radio" name="mesure" id="L" value="L"/><label for="L">En litres</label>
        </p>
      </form>';
  }

  public function generateTableau(){
    return '
      <table><caption>Ingredients :</caption>
        <tbody>
          <tr>
            <th>NOM</th><th>TYPE</th>
          </tr>
          <tr>
            <td>riz</td>
            <td>en poids</td>
          </tr>
          <tr>
            <td>pates</td>
            <td>en poids</td>
          </tr>
        </tbody>
      </table>';
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