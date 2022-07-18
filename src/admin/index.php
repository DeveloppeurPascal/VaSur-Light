<?php
	// Vasur-LeScript.fr
	//
	// Cette application permet de g�rer des liens courts selon
	// trois versions : Light, Standard et Professionnelle.
	// Chacune a des options et fonctionnalit�s diff�rentes.
	//
	// Bien qu'�tant open source, ce logiciel n'est pas un
	// logiciel libre. Il n'est pas distribuable sans accord �crit
	// pr�alable de son auteur.
	//
	// Si vous d�sirez vous procurer la derni�re version ou
	// vous enregistrer, passez par http://www.vasur-lescript.fr
	//
	// (c) Patrick Pr�martin / Olf Software 2009
	//
	// Modifications :
	//	21/06/2009 : cr�ation de ce fichier

	require_once(dirname(__FILE__)."/../inc/session.inc.php");
	require_once(dirname(__FILE__)."/../inc/fonctions.inc.php");

	if (session_ouvrir())
	{
		if (file_exists(dirname(__FILE__)."/pro.inc.php"))
		{ // version professionnelle de l'application install�e
			require_once(dirname(__FILE__)."/pro.inc.php");
			exit;
		}
		else if (file_exists(dirname(__FILE__)."/standard.inc.php"))
		{ // version standard de l'application install�e
			require_once(dirname(__FILE__)."/standard.inc.php");
			exit;
		}
		else if (file_exists(dirname(__FILE__)."/light.inc.php"))
		{ // version light de l'application install�e
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