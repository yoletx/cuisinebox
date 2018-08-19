<?php
	require_once("classes/database.php");
	require_once("classes/util.php");
	require_once("classes/accueil.php");
	require_once("classes/ingredient.php");
	require_once("classes/frigo.php");
	require_once("classes/recette.php");
	require_once("classes/recette_new.php");

	Database::connect();
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

	if(!isset($_GET["ajax_action"])){
		echo '
		<html>
			<head>
				<script src="javascript/main.js"></script>
				<link rel="stylesheet" type="text/css" href="css/main.css">';
				$fenetre->afficher_header();
		echo '
			</head>
			<body>
				<div class="main_grid">
					<div class="accueil"    onclick="goto(\'accueil\', event);"    >Accueil</div>
					<div class="ingredient" onclick="goto(\'ingredient\', event);" >Ingrédient</div>
					<div class="frigo"      onclick="goto(\'frigo\', event);"      >Frigo</div>
					<div class="recette"    onclick="goto(\'recette\', event);"    >Recette <img class="icon" src="images/add.png" onclick="goto(\'recette_new\', event); return false;"/></div>
					<div class="content">';
						$fenetre->afficher();
		echo '</div>
					<div class="footer">Créé par Adelita et Yoyo</div>
				</div>
			</body>
		</html>
		';
	};

	Database::disconnect();
?>