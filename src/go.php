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

	require_once(dirname(__FILE__)."/inc/liencourt.inc.php");
	require_once(dirname(__FILE__)."/inc/fonctions.inc.php");
	$id = nettoie_parametre($_GET["id"]); // check code permettant d'�viter les abus d'url
	$url = lien_geturltogo($id);
	if ("" != $url)
	{
		lien_clicplusun($id);
		header("location: ".$url);
		exit;
	}
?><html><head><title>Adresse introuvable</title></head><body>D&eacute;sol&eacute;, ce lien n'existe plus.</body></html>