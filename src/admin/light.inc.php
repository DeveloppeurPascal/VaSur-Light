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

	require_once(dirname(__FILE__)."/../inc/liencourt.inc.php");

	$affichage = false;
	$ecran = 0;
	$erreur = "";
	$url = "";

	$op = (isset($_POST["op"]))?trim(strip_tags(stripslashes($_POST["op"]))):((isset($_GET["op"]))?trim(strip_tags(stripslashes($_GET["op"]))):"");
	if ("reduire1" == $op)
	{
		$affichage = true;
		$ecran = 1;
	}
	else if ("reduire2" == $op)
	{
		$url = trim(strip_tags(stripslashes($_POST["url"])));
		if ("" != $url)
		{
			$id = lien_creer(date("YmdHi"), $url);
			$affichage = true;
			$ecran = 2;
		}
		else
		{
			$affichage = true;
			$ecran = 1;
			$erreur .= "Veuillez indiquer l'adresse Internet à raccourcir.\n";
		}
	}
	else
	{
		$affichage = true;
		$ecran = 1;
	}
	
	if ($affichage)
	{
		switch ($ecran)
		{
			case 1 : // formulaire de demande d'URL
				page_header("Ajout d'un nouveau raccourci");
?>
<input type="hidden" name="op" id="op" value="reduire2" />
<p>Pour raccourcir une URL, veuillez en indiquer l'adresse ci-dessous.</p>
<?php
	if ("" != $erreur)
	{
		print("<p><font color=\"#FF0000\">".nl2br(htmlentities($erreur,ENT_COMPAT,"ISO8859-1"))."</font></p>");
	}
?>
<fieldset>
	<legend>Raccourcir une adresse</legend>
	<label for="url">Adresse à raccourcir</label><br />
	<input type="text" name="url" id="url" value="<?php print(htmlentities($url,ENT_COMPAT,"ISO8859-1")); ?>" size="100" /><br />
	<input type="submit" name="btnenvoi" id="btnenvoi" value="Raccourcir" />
</fieldset>
<?php
				page_footer();
				break;
			case 2 : // URL raccourcie ajoutée, affichage du résultat
				page_header("Ajout d'un nouveau raccourci : résultat");
?>
<input type="hidden" name="op" id="op" value="reduire1" />
<p>Le site &agrave; l'adresse<br />
<?php print("<a href=\"".$url."\" target=\"_blank\">".$url."</a>"); ?></p>
<p>est d&eacute;sormais disponible &agrave; l'adresse<br />
<?php $url_courte = fichier_lit("domaine.txt")."/".$id; print("<a href=\"".$url_courte."\" target=\"_blank\">".$url_courte."</a>"); ?></p>
<input type="submit" name="btnenvoi" id="btnenvoi" value="En raccourcir une autre" />
<?php
				page_footer();
				break;
			default :
				page_header("Erreur : code page non gérée");
?>
<p>Impossible d'afficher la page demand&eacute;e.</p>
<?php
				page_footer();
		}
	}
?>