<?php
	require_once("classes/database.php");
	require_once("classes/accueil.php");
	require_once("classes/ingredient.php");
	require_once("classes/frigo.php");
	require_once("classes/recette.php");
	require_once("classes/recette_new.php");

	Database::connect();
?>
<html>
<head>
	<script src="javascript/main.js"></script>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<div class="main_grid">
		<div class="accueil"    onclick="goto('accueil');"    >Accueil</div>
		<div class="ingredient" onclick="goto('ingredient');" >Ingrédient</div>
		<div class="frigo"      onclick="goto('frigo');"      >Frigo</div>
		<div class="recette"    onclick="goto('recette');"    >Recette</div>
		<div class="content">
			<?php
				if(!isset($_GET["page"])){
					$page = "";
				} else {
					$page = $_GET["page"];
				}
				switch($page){
					case "ingredient":
						$fenetre = new Ingredient();
					break;
					case "frigo":
						$fenetre = new Frigo();
					break;
					case "recette":
						$fenetre = new Recette();
					break;
					case "recette_new":
						$fenetre = new RecetteNew();
					break;
					default:
						$fenetre = new Accueil();
					break;
				}
				$fenetre->afficher();
			?>

		</div>
		<div class="footer">Créé par Adelita et Yoyo</div>
	</div>
</body>
</html>

<?php
	Database::disconnect();
?>