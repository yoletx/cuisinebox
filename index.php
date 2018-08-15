<?php
	//require_once("classes/ingredient.php");
	require_once("classes/frigo.php");
	//require_once("classes/recette.php");
?>
<html>
<head>
	<script src="javascript/main.js"></script>
</head>
<body>
	<table>
		<tr><td onclick="goto('ingredient');">Ingredients</td><td onclick="goto('frigo');">Frigo</td><td onclick="goto('recette');">Recette</td></tr>
		<tr><td colspan="3">
			<?php
				if(!isset($_GET["page"])){
					$page = "";
				} else {
					$page = $_GET["page"];
				}
				switch($page){
					/*
					case "ingredient":
						$fenetre = new Ingredient();
					break;*/
					case "frigo":
						$fenetre = new Frigo();
					break;
					/*case "recette":
						$fenetre = new Recette();
					break;
					case "recette_new":
						$fenetre = new RecetteNew();
					break;
					default:
						$fenetre = new Accueil();
					break;*/
				}
				@$fenetre->afficher();
			?>
		</td></tr>
	</table>
</body>
</html>