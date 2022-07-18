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
	// (c) Patrick Prémartin / Olf Software 2009-2014
	//
	// Modifications :
	//	21/06/2009 : création de ce fichier
	//	25/06/2009 : modification du footer des pages (pub sur la version light, retour au menu sur les pro et standard)
	//	01/09/2013 : adaptation des sources aux versions de PHP 5.3 et suivantes
	//	22/08/2014 : ajout d'un chmod() sur la création des dossiers afin d'éliminer les évneutels problèmes de droits d'accès selon les hébergeurs

	function nettoie_parametre($parametre) // => chaine
	{
		if (1 == ini_get("magic_quotes_gpc"))
		{
			return trim(strip_tags(stripslashes($parametre)));
		}
		else
		{
			return trim(strip_tags($parametre));
		}
	}

	function creer_dossier($nom_dossier)
	{
		if (! is_dir($nom_dossier)) {
			if ($n = strrpos($nom_dossier, "/")) {
				creer_dossier(substr($nom_dossier, 0, $n));
				mkdir($nom_dossier, 0777);
				chmod($nom_dossier, 0775);
			}
		}
	}

	function fichier_lit($nom_fichier) // => chaine
	{
		$result = "";
		if ((false === strpos($nom_fichier,"..")) && (false === strpos($nom_fichier,"*")) && (false === strpos($nom_fichier,"?")))
		{
			$ch = @file_get_contents(dirname(__FILE__)."/../data/".$nom_fichier);
			if (false !== $ch)
			{
				$result = $ch;
			}
		}
		return $result;
	}

	function fichier_ecrit($nom_fichier, $contenu) // => boolean
	{
		$ok = false;
		if ((false === strpos($nom_fichier,"..")) && (false === strpos($nom_fichier,"*")) && (false === strpos($nom_fichier,"?")))
		{
			if ($n = strrpos($nom_fichier, "/"))
			{
				creer_dossier(dirname(__FILE__)."/../data/".substr($nom_fichier, 0, $n));
			}
			if ($f = @fopen(dirname(__FILE__)."/../data/".$nom_fichier,"w"))
			{
				$nb = fwrite($f, $contenu);
				fclose($f);
				$ok = (false !== $nb);
			}
		}
		return $ok;
	}

	function fichier_supprime($nom_fichier) // => boolean
	{
		if ((false === strpos($nom_fichier,"..")) && (false === strpos($nom_fichier,"*")) && (false === strpos($nom_fichier,"?")))
		{
			return @unlink(dirname(__FILE__)."/../data/".$nom_fichier);
		}
		else
		{
			return false;
		}
	}

	function get_verif_code($op,$id,$avec_id_session=true)
	{
		if ($avec_id_session)
		{
			$result = md5($op.$id.session_id());
		}
		else
		{
			$result = md5($op.$id);
		}
		return substr(strtolower($result),3,15);
	}

	function check_verif_code($op,$id,$verif="",$avec_id_session=true)
	{
		if ("" != $verif)
		{
			return ($verif == get_verif_code($op,$id,$avec_id_session));
		}
		else
		{
			return ($_POST["verif"].$_GET["verif"] == get_verif_code($op,$id,$avec_id_session));
		}
	}

	function page_header($titre, $soustitre="")
	{
		print("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" /><title>".htmlentities($titre,ENT_COMPAT,"ISO8859-1")."</title>");
		if ((file_exists(dirname(__FILE__)."/../admin/standard.inc.php")) || (file_exists(dirname(__FILE__)."/../admin/pro.inc.php")))
		{
			print("<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"css/style.css\" />");
			print("<script type=\"text/javascript\" src=\"js/prototype.js\"></script>");
			print("<script type=\"text/javascript\" src=\"js/tablekit.js\"></script>");
		}
		print("</head><body><h1>".htmlentities($titre,ENT_COMPAT,"ISO8859-1")."</h1>");
		if ("" != $soustitre)
		{
			print("<h2>".htmlentities($soustitre,ENT_COMPAT,"ISO8859-1")."</h2>");
		}
		print("<form method=\"POST\" action=\"index.php\">");
	}

	function page_footer()
	{
		print("</form>");
		if (file_exists(dirname(__FILE__)."/../admin/light.inc.php"))
		{
			print("<hr align=\"center\" width=\"80%\" /><div align=\"center\"><script type=\"text/javascript\" src=\"http://adserver.ma-regie-publicitaire.com/publicite-js.php?c=262&i=8960203078&verif=e0fbc0148b01bdee90533bf4ee6b808b&t=_blank\"></script><br /><a href=\"http://www.ma-regie-publicitaire.com/louer-emplacement-262.html\" target=\"_blank\">Votre publicité ici et chez tous les utilisateurs de VaSur-LeScript Light</a></div>");
		}
		if ((file_exists(dirname(__FILE__)."/../admin/standard.inc.php")) || (file_exists(dirname(__FILE__)."/../admin/pro.inc.php")))
		{
			print("<p><a href=\"index.php\">Retour au menu</a></p>");
		}
		print("<hr width=\"100%\" /><div align=\"center\">Powered by <a href=\"http://www.vasur-lescript.fr/\" target=\"_blank\" title=\"Consulter le site officiel de ce logiciel (dans une nouvelle fenêtre)\">VaSur-LeScript</a> - Tous droits réservés - Reproduction et distribution interdites sans accord écrit préalable.<br />(c) <a href=\"http://patrick.premartin.nom.fr/\" target=\"_blank\" title=\"Consulter le site de l'auteur de ce logiciel (dans une nouvelle fenêtre)\">Patrick Prémartin</a> / <a href=\"http://www.olfsoftware.fr/\" target=\"_blank\" title=\"Consulter le site de l'éditeur et distributeur de ce logiciel (dans une nouvelle fenêtre)\">Olf Software</a> 2009-2013</div>");
		print("</body></html>");
	}

	function generer_identifiant($taille)
	{
		$id = "";
		for ($j = 0; $j < $taille/5; $j++)
		{
			$num = mt_rand (0,99999);
			for ($i = 0; $i < 5; $i++)
			{
				$id = ($num % 10).$id;
				$num = floor ($num / 10);
			}
		}
		return (substr ($id, 0, $taille));
	}
?>