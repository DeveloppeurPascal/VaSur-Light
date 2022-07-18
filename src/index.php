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
	// (c) Patrick Pr�martin / Olf Software 2009-2013
	//
	// Modifications :
	//	21/06/2009 : cr�ation de ce fichier
	//	01/09/2013 : adaptation des sources aux versions de PHP 5.3 et suivantes

	require_once(dirname(__FILE__)."/inc/fonctions.inc.php");
	$version = 1*fichier_lit("version.dat");
	$ancienne_version = $version;
	$modif_faite = false;
	$message_a_afficher = "";
	
	if ("" == fichier_lit("domaine.txt"))
	{
		if ($n = strrpos($_SERVER["REQUEST_URI"], "/"))
		{
			fichier_ecrit("domaine.txt","http://".$_SERVER["HTTP_HOST"].substr($_SERVER["REQUEST_URI"], 0, $n));
		}
		else
		{
			fichier_ecrit("domaine.txt","http://".$_SERVER["HTTP_HOST"]);
		}
		//$modif_faite = true;
	}
	
	if ("" == fichier_lit("masterpass.dat"))
	{
		require_once(dirname(__FILE__)."/inc/motdepasse.inc.php");
		$pseudo = "admin";
		$motdepasse = utilisateur_creermotdepasse();
		if (! utilisateur_modifiermotdepasse($pseudo,$motdepasse))
		{
			utilisateur_creer($pseudo,$motdepasse);
		}
		fichier_ecrit("masterpass.dat",md5(generer_identifiant(100)));
		$message_a_afficher .= "Pour vous connecter � votre poste de contr�le, utilisez ces param�tres :\n";
		$message_a_afficher .= "- adresse de connexion\n".fichier_lit("domaine.txt")."/admin/\n";
		$message_a_afficher .= "- utilisateur\n".$pseudo."\n";
		$message_a_afficher .= "- mot de passe\n".$motdepasse."\n";
		$message_a_afficher .= "\n";
		$modif_faite = true;
	}

	$version_en_cours = 1;
	if ($version < $version_en_cours)
	{
		// rien � faire de particulier pour la version 1 de l'application
		$version = $version_en_cours;
		fichier_ecrit("version.dat", $version);
		$modif_faite = true;
	}

	if (! $modif_faite)
	{
		header("location: http://www.vasur-lescript.fr");
		exit;
	}
	page_header("Application mise � jour");
?>
<p>Version <?php print($version); ?> install&eacute;e &agrave; la place de la version <?php print($ancienne_version); ?>.</p>
<?php
	if ("" != $message_a_afficher)
	{
		print("<p>".nl2br(htmlentities($message_a_afficher,ENT_COMPAT,"ISO8859-1"))."</p>");
	}
	page_footer();
?>