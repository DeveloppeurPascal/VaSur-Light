<?php
	// Vasur-LeScript.fr
	//
	// Cette application permet de gérer des liens courts selon
	// trois versions : Light, Standard et Professionnelle.
	// Chacune a des options et fonctionnalités différentes.
	//
	// Bien qu'étant open source, ce logiciel n'est pas un
	// logiciel libre. Il n'est pas distribuable sans accord écrit
	// préalable de son auteur.
	//
	// Si vous désirez vous procurer la dernière version ou
	// vous enregistrer, passez par http://www.vasur-lescript.fr
	//
	// (c) Patrick Prémartin / Olf Software 2009
	//
	// Modifications :
	//	21/06/2009 : création de ce fichier

	require_once(dirname(__FILE__)."/../inc/session.inc.php");
	require_once(dirname(__FILE__)."/../inc/fonctions.inc.php");

	if (session_ouvrir())
	{
		if (file_exists(dirname(__FILE__)."/pro.inc.php"))
		{ // version professionnelle de l'application installée
			require_once(dirname(__FILE__)."/pro.inc.php");
			exit;
		}
		else if (file_exists(dirname(__FILE__)."/standard.inc.php"))
		{ // version standard de l'application installée
			require_once(dirname(__FILE__)."/standard.inc.php");
			exit;
		}
		else if (file_exists(dirname(__FILE__)."/light.inc.php"))
		{ // version light de l'application installée
			require_once(dirname(__FILE__)."/light.inc.php");
			exit;
		}
	}
	else
	{
		exit;
	}

	page_header("Erreur : application absente");
?>
<p>Erreur : il vous manque le programme de gestion de la base de liens.</p>
<?php
	page_footer();
?>