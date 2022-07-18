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
	//	25/06/2009 : ajout de la fonction session_getpseudo
	//	01/09/2013 : adaptation des sources aux versions de PHP 5.3 et suivantes

	require_once(dirname(__FILE__)."/fonctions.inc.php");
	session_start();

	function session_ouvrir() // => boolean
	{
		if (! session_est_ouverte())
		{
			// contrôle des paramètres du programme
			require_once(dirname(__FILE__)."/motdepasse.inc.php");
			$pseudo = "";
			$motdepasse = "";
			$op = (isset($_POST["op"]))?trim(strip_tags(stripslashes($_POST["op"]))):"";
			$erreur = "";
			$affichage = true;
			if ("connect" == $op)
			{
				$pseudo = trim(strip_tags(stripslashes($_POST["pseudo"])));
				$motdepasse = trim(strip_tags(stripslashes($_POST["motdepasse"])));
				if ("" == $pseudo)
				{
					$erreur .= "Veuillez saisir votre nom d'utilisateur.\n";
				}
				if ("" == $motdepasse)
				{
					$erreur .= "Veuillez saisir votre mot de passe.\n";
				}
				if (("" == $erreur) && (! utilisateur_verifier_mot_de_passe($pseudo,$motdepasse)))
				{
					$erreur .= "Connexion impossible : soit cet utilisateur n'existe pas, soit ce mot de passe n'est pas le bon.\n";
				}
				$affichage = ("" != $erreur);
			}
			if ($affichage)
			{
				page_header("Connexion");
?>
<input type="hidden" name="op" id="op" value="connect" />
<p>Pour vous connecter, veuillez indiquer vos identifiants dans ce formulaire.</p>
<?php
	if ("" != $erreur)
	{
		print("<p><font color=\"#FF0000\">".nl2br(htmlentities($erreur,ENT_COMPAT,"ISO8859-1"))."</font></p>");
	}
?>
<fieldset>
	<legend>Identifiants</legend>
	<label for="pseudo">Votre pseudo</label><br />
	<input type="text" name="pseudo" id="pseudo" value="<?php print(htmlentities($pseudo,ENT_COMPAT,"ISO8859-1")); ?>" /><br />
	<label for="motdepasse">Votre mot de passe</label><br />
	<input type="password" name="motdepasse" id="motdepasse" value="<?php print(htmlentities($motdepasse,ENT_COMPAT,"ISO8859-1")); ?>" /><br />
	<input type="submit" name="btnenvoi" id="btnenvoi" value="Me connecter" />
</fieldset>
<?php
				page_footer();
				return false;
			}
			else
			{
				$_SESSION["pseudo"] = $pseudo;
				$_SESSION["verif"] = get_verif_code("VaSur",$pseudo,true);
				return true;
			}
		}
		else
		{
			return true;
		}
	}

	function session_fermer() // => boolean
	{
		$_SESSION["pseudo"] = "";
		$_SESSION["verif"] = "";
		if (session_destroy())
		{
			session_start();
		}
	}

	function session_est_ouverte() // => boolean
	{
		return ((isset($_SESSION["pseudo"])) && ("" != $_SESSION["pseudo"]) && (isset($_SESSION["verif"])) && ("" != $_SESSION["verif"]) && (check_verif_code("VaSur",$_SESSION["pseudo"],$_SESSION["verif"],true)));
	}

	function session_getpseudo() // => $pseudo
	{
		return $_SESSION["pseudo"];
	}
?>