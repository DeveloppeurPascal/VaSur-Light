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
	//	25/06/2009 : correction d'un bogue dans la modification des mots de passe

	require_once(dirname(__FILE__)."/fonctions.inc.php");

	function utilisateur_creer($pseudo,$motdepasse) // => boolean
	{
		$ok = false;
		if (("" != $pseudo) && ("" != $motdepasse))
		{
			$ch = fichier_lit("user.dat");
			$liste_utilisateurs = unserialize($ch);
			if ((! is_array($liste_utilisateurs)) || (! isset($liste_utilisateurs[$pseudo])))
			{
				$liste_utilisateurs[$pseudo] = md5($motdepasse);
				$ok = fichier_ecrit("user.dat",serialize($liste_utilisateurs));
			}
		}
		return $ok;
	}

	function utilisateur_verifier_mot_de_passe($pseudo,$motdepasse) // => boolean
	{
		$ok = false;
		if (("" != $pseudo) && ("" != $motdepasse))
		{
			$ch = fichier_lit("user.dat");
			$liste_utilisateurs = unserialize($ch);
			$ok = (is_array($liste_utilisateurs)) && (isset($liste_utilisateurs[$pseudo])) && (md5($motdepasse) == $liste_utilisateurs[$pseudo]);
		}
		return $ok;
	}

	function utilisateur_creermotdepasse() // => chaine
	{
		$motdepasse = "";
		$taille_mot_de_passe = 10;
		$caracteres_possibles = "0123456789abcdefghijklmnopqrstuvwxyz-_ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for ($i = 0; $i < $taille_mot_de_passe; $i++)
		{
			$motdepasse .= substr($caracteres_possibles,mt_rand(0,strlen($caracteres_possibles)),1);
		}
		return $motdepasse;
	}

	function utilisateur_supprimer($pseudo) // => boolean
	{
		$ok = false;
		if ("" != $pseudo)
		{
			$ch = fichier_lit("user.dat");
			$liste_utilisateurs = unserialize($ch);
			if ((is_array($liste_utilisateurs)) && (isset($liste_utilisateurs[$pseudo])))
			{
				unset($liste_utilisateurs[$pseudo]);
				if (1 > count($liste_utilisateurs))
				{
					$ok = fichier_supprime("user.dat") && fichier_supprime("masterpass.dat");
				}
				else
				{
					$ok = fichier_ecrit("user.dat",serialize($liste_utilisateurs));
				}
			}
		}
		return $ok;
	}

	function utilisateur_modifierpseudo($pseudo_ancien,$pseudo_nouveau) // => boolean
	{
		$ok = false;
		if (("" != $pseudo_ancien) && ("" != $pseudo_nouveau))
		{
			$ch = fichier_lit("user.dat");
			$liste_utilisateurs = unserialize($ch);
			if ((is_array($liste_utilisateurs)) && (isset($liste_utilisateurs[$pseudo_ancien])) && (! (isset($liste_utilisateurs[$pseudo_nouveau]))))
			{
				$liste_utilisateurs[$pseudo_nouveau] = $liste_utilisateurs[$pseudo_ancien];
				unset($liste_utilisateurs[$pseudo_ancien]);
				$ok = fichier_ecrit("user.dat",serialize($liste_utilisateurs));
			}
		}
		return $ok;
	}

	function utilisateur_modifiermotdepasse($pseudo,$motdepasse_nouveau) // => boolean
	{
		$ok = false;
		if (("" != $pseudo) && ("" != $motdepasse_nouveau))
		{
			$ch = fichier_lit("user.dat");
			$liste_utilisateurs = unserialize($ch);
			if ((is_array($liste_utilisateurs)) && (isset($liste_utilisateurs[$pseudo])))
			{
				$liste_utilisateurs[$pseudo] = md5($motdepasse_nouveau);
				$ok = fichier_ecrit("user.dat",serialize($liste_utilisateurs));
			}
		}
		return $ok;
	}

	function utilisateur_getliste() // => tableau des pseudos ou FALSE
	{
		$ch = fichier_lit("user.dat");
		$liste_utilisateurs = unserialize($ch);
		if (is_array($liste_utilisateurs))
		{
			unset($liste);
			reset($liste_utilisateurs);
			while (list($key,$value) = each($liste_utilisateurs))
			{
				$liste[$key] = $key;
			}
			return sort($liste);
		}
		else
		{
			return false;
		}
	}
?>