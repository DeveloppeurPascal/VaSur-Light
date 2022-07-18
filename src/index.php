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
	// (c) Patrick Prémartin / Olf Software 2009-2013
	//
	// Modifications :
	//	21/06/2009 : création de ce fichier
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
		$message_a_afficher .= "Pour vous connecter à votre poste de contrôle, utilisez ces paramètres :\n";
		$message_a_afficher .= "- adresse de connexion\n".fichier_lit("domaine.txt")."/admin/\n";
		$message_a_afficher .= "- utilisateur\n".$pseudo."\n";
		$message_a_afficher .= "- mot de passe\n".$motdepasse."\n";
		$message_a_afficher .= "\n";
		$modif_faite = true;
	}

	$version_en_cours = 1;
	if ($version < $version_en_cours)
	{
		// rien à faire de particulier pour la version 1 de l'application
		$version = $version_en_cours;
		fichier_ecrit("version.dat", $version);
		$modif_faite = true;
	}

	if (! $modif_faite)
	{
		header("location: http://www.vasur-lescript.fr");
		exit;
	}
	page_header("Application mise à jour");
?>
<p>Version <?php print($version); ?> install&eacute;e &agrave; la place de la version <?php print($ancienne_version); ?>.</p>
<?php
	if ("" != $message_a_afficher)
	{
		print("<p>".nl2br(htmlentities($message_a_afficher,ENT_COMPAT,"ISO8859-1"))."</p>");
	}
	page_footer();
?>