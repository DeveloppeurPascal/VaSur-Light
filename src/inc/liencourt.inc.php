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
	//	25/06/2009 : modifications liées à la mise en place de la version standard
	//	01/09/2013 : modifications liées à la mise en place de la version pro
	
	function lien_creer_id($longueur = 5) // => chaine
	{
		$id = "";
		$taille_id = $longueur;
		$caracteres_possibles = "0123456789abcdefghijklmnopqrstuvwxyz";
		for ($i = 0; $i < $taille_id; $i++)
		{
			$id .= substr($caracteres_possibles,mt_rand(0,strlen($caracteres_possibles)),1);
		}
		return $id;
	}

	function lien_creer($libelle, $url, $longueur=5, $id="")	// => $id
	{
		if (("" != $libelle) && ("" != $url))
		{
			if ($id <> "")
			{
				$liste = lien_getliste();
				if (is_array($liste))
				{
					if (isset($liste[$id]))
					{
						$id = "";
					}
				}
			}
			else
			{
				$id = lien_creer_id($longueur);
				$liste = lien_getliste();
				if (is_array($liste))
				{
					$nb_essai = 0;
					while (isset($liste[$id]))
					{
						$id = lien_creer_id($longueur);
						$nb_essai++;
						if ($nb_essai > 5)
						{
							$longueur++;
						}
					}
				}
			}
			if ("" != $id)
			{
				$liste[$id] = $url;
				lien_saveliste($liste);
				fichier_ecrit(lien_nomdossier($id)."nomlien.txt",$libelle);
				fichier_ecrit(lien_nomdossier($id)."url.txt",$url);
			}
		}
		return $id;
	}

	function lien_modifier($id,$libelle,$url,$id2="") // => boolean
	{
		$ok = false;
		$liste = lien_getliste();
		if ((is_array($liste)) && (isset($liste[$id])))
		{
			if (("" == $id2) || ($id2 == $id))
			{
				$ok = fichier_ecrit(lien_nomdossier($id)."nomlien.txt",$libelle) && fichier_ecrit(lien_nomdossier($id)."url.txt",$url);
			}
			else if (! isset($liste[$id2]))
			{
				if ($id2 == lien_creer($libelle, $url, 0, $id2))
				{
					/* traiter le déplacement des statistiques du lien */
					$ok = lien_supprimer($id);
				}
			}
		}
		return $ok;
	}

	function lien_supprimer($id) // => boolean
	{
		$ok = false;
		$liste = lien_getliste();
		if ((is_array($liste)) && (isset($liste[$id])))
		{
			unset($liste[$id]);
			$ok = lien_saveliste($liste);
		}
		return $ok;
	}

	function lien_getlibelle($id) // => $libelle
	{
		return fichier_lit(lien_nomdossier($id)."nomlien.txt");
	}

	function lien_geturl($id) // => $url
	{
		return fichier_lit(lien_nomdossier($id)."url.txt");
	}

	function lien_geturltogo($id) // => $url
	{
		return lien_geturl($id);
	}

	function lien_geturlraccourcie($id) // => $url raccourcie
	{
		return fichier_lit("domaine.txt")."/".$id;
	}

	function lien_getliste() // => tableau de $id->$url
	{
		$ch = fichier_lit("liste.dat");
		$liste_id = unserialize($ch);
		if (is_array($liste_id))
		{
			return $liste_id;
		}
		else
		{
			return false;
		}
	}

	function lien_saveliste($liste) // boolean
	{
		if (is_array($liste))
		{
			return fichier_ecrit("liste.dat",serialize($liste));
		}
		else
		{
			return fichier_supprime("liste.dat");
		}
	}

	function lien_clicplusun($id) // => boolean
	{
		$timestamp=time();
		$ok = fichier_ecrit("clic.txt",1+1*fichier_lit("clic.txt"));
		$ok = $ok && fichier_ecrit(lien_nomdossier($id)."clic.txt",1+1*fichier_lit(lien_nomdossier($id)."clic.txt"));
		$ok = $ok && fichier_ecrit(date("Y",$timestamp)."/clic.txt",1+1*fichier_lit(date("Y",$timestamp)."/clic.txt"));
		$ok = $ok && fichier_ecrit(lien_nomdossier($id).date("Y",$timestamp)."/clic.txt",1+1*fichier_lit(lien_nomdossier($id).date("Y",$timestamp)."/clic.txt"));
		$ok = $ok && fichier_ecrit(date("Y",$timestamp)."/".date("m",$timestamp)."/clic.txt",1+1*fichier_lit(date("Y",$timestamp)."/".date("m",$timestamp)."/clic.txt"));
		$ok = $ok && fichier_ecrit(lien_nomdossier($id).date("Y",$timestamp)."/".date("m",$timestamp)."/clic.txt",1+1*fichier_lit(lien_nomdossier($id).date("Y",$timestamp)."/".date("m",$timestamp)."/clic.txt"));
		$ok = $ok && fichier_ecrit(date("Y",$timestamp)."/".date("m",$timestamp)."/".date("d",$timestamp)."/clic.txt",1+1*fichier_lit(date("Y",$timestamp)."/".date("m",$timestamp)."/".date("d",$timestamp)."/clic.txt"));
		$ok = $ok && fichier_ecrit(lien_nomdossier($id).date("Y",$timestamp)."/".date("m",$timestamp)."/".date("d",$timestamp)."/clic.txt",1+1*fichier_lit(lien_nomdossier($id).date("Y",$timestamp)."/".date("m",$timestamp)."/".date("d",$timestamp)."/clic.txt"));
		return $ok;
	}

	function lien_nomdossier($id) // => chaine
	{
		$dossier = "";
		for ($i = 0; $i < strlen($id); $i++)
		{
			$dossier .= substr($id,$i,1)."/";
		}
		return $dossier;
	}

	function lien_nettoyerID($id)
	{
		$res = "";
		for ($i=0; $i < strlen($id); $i++)
		{
			$c = substr($id,$i,1);
			if ((($c >= "a") && ($c <= "z")) || (($c >= "A") && ($c <= "Z")) || (($c >= "0") && ($c <= "9")) || ($c == "-") || ($c == "_") || ($c == "."))
			{
				$res .= $c;
			}
		}
		return $res;
	}
?>