<?php

Class Calendrier{
	
	//string
	Private $_Libelle_Periode;
	Public $_Format_Date;
	Private $_CodeHTML;
	
	//int
	Private $_Nombre_Jours_Periode;
	Public $_Nombre_Mois_Ligne;
	Public const SEMAINE = 1;
	Public const MOIS = 2;
	Public const AN = 3;
	Public $_Periode_Affichee;
	Public const JOUR_SECONDE = 24*60*60;
	
	//Date
	Private $_Jour_Affichee;
	Public $_Jour_Debut_Periode;
	Public $_Jour_Fin_Periode;
	
	//array
	Public const TAB_MOIS = [1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
	Public const TAB_PERIODE = [1 => 'Semaine', 'Mois', 'Année'];
	Public $_Tab_Rdv;
	
	Public function __construct()
	{
		$this->_Format_Date = "Y-m-d";
		$this->_Nombre_Mois_Ligne = 4;
		$this->_Periode_Affichee = self::MOIS;
		$this->_Tab_Rdv = array();		
	}
	
	Public function __set($property,$value)
	{
		switch ($property)
		{
			Case "_Jour_Affichee":
				$this->_Jour_Affichee = $value;
				switch ($this->_Periode_Affichee)
				{
					Case self::SEMAINE:
						$this->_Nombre_Jours_Periode = 7;
						$this->_Jour_Debut_Periode = $this->_Jour_Affichee -((Date("N", $this->_Jour_Affichee) - 1) * self::JOUR_SECONDE);
						$this->_Jour_Fin_Periode = $this->_Jour_Debut_Periode + (($this->_Nombre_Jours_Periode - 1) * self::JOUR_SECONDE);
						$this->_Libelle_Periode = self::TAB_MOIS[Date("n", $this->_Jour_Debut_Periode)].' '.Date('Y', $this->_Jour_Debut_Periode).' - Semaine '.Date('W', $this->_Jour_Debut_Periode);
						Break;
					Case self::MOIS:
						$this->_Nombre_Jours_Periode = Date("t", $this->_Jour_Affichee);
						$this->_Jour_Debut_Periode =  $this->_Jour_Affichee - ((Date("j", $this->_Jour_Affichee) - 1) * self::JOUR_SECONDE);
						$this->_Jour_Fin_Periode = $this->_Jour_Debut_Periode + (($this->_Nombre_Jours_Periode - 1) * self::JOUR_SECONDE);
						$this->_Libelle_Periode = self::TAB_MOIS[Date("n", $this->_Jour_Affichee)].' '.Date('Y', $this->_Jour_Affichee);
						Break;
					Case self::AN:
						$this->_Nombre_Jours_Periode = 365+Date("L", $this->_Jour_Affichee);
						$this->_Jour_Debut_Periode = $this->_Jour_Affichee - (Date("z", $this->_Jour_Affichee) * self::JOUR_SECONDE);
						$this->_Jour_Fin_Periode = $this->_Jour_Debut_Periode + (($this->_Nombre_Jours_Periode - 1) * self::JOUR_SECONDE);
						$this->_Libelle_Periode = Date('Y', $this->_Jour_Affichee);
						Break;
				}
			Break;
		}
		
	}
	
	Public function Affiche()//: string
	{
		$Date = new DateTime();
		$Date = $this->_Jour_Debut_Periode; //date
		$this->_CodeHTML = "<link rel=\"stylesheet\" href=\"Calendrier.css\">".
							"<script type=\"text/javascript\" src=\"Calendrier.js\"></script>".
							"<form id=\"Form_Calendrier\" action=\"\" method=\"post\">".
							"<div id=\"CHP_PLN\">".					
								"<div id=\"RUB_PLN\" Class=\"Ligne\">".
									"<span id=\"BTN_HUI\" Class=\"Cellule\" onclick=\"document.forms['Form_Calendrier'].elements['Periode-affiche'].value = Math.trunc(Date.now()/1000); window.document.forms[0].submit()\">Aujourd'hui".
									"<input type=\"hidden\" value=\"{$this->_Jour_Affichee}\" name=\"Periode-affiche\"><input type=\"hidden\" value=\"0\" name=\"Periode-change\"></span>".
									"<span id=\"BTN_Periode_PREC\" Class=\"Cellule\" onclick=\"document.forms['Form_Calendrier'].elements['Periode-change'].value = -1; window.document.forms[0].submit()\"><</span>".
									"<span id=\"BTN_Periode_SUIV\" Class=\"Cellule\" onclick=\"document.forms['Form_Calendrier'].elements['Periode-change'].value = 1; window.document.forms[0].submit()\">></span>".
									"<span id=\"LIB_Periode\" Class=\"Cellule\">".
										"<label for=\"Periode-select\">Période :</label>{$this->_Libelle_Periode}</span>".
									"<span id=\"MEN_Periode\" Class=\"Cellule\">".
										"<label for=\"Periode-select\">Période :</label>".
										"<select onchange=\"window.document.forms[0].submit()\" name=\"Periode\" id=\"Periode-select\">".
											"<option value=\"".self::SEMAINE."\"".self::Selected(self::SEMAINE == $this->_Periode_Affichee).">".self::TAB_PERIODE[self::SEMAINE]."</option>".
											"<option value=\"".self::MOIS."\"".self::Selected(self::MOIS == $this->_Periode_Affichee).">".self::TAB_PERIODE[self::MOIS]."</option>".
											"<option value=\"".self::AN."\"".self::Selected(self::AN == $this->_Periode_Affichee).">".self::TAB_PERIODE[self::AN]."</option>".
										"</select>".
									"</span>".
								"</div>";
		switch ($this->_Periode_Affichee)
		{
			Case self::SEMAINE:
				$this->_CodeHTML .=	"<div id=\"RUB_TTR\" Class=\"Ligne\">".
										"<span id=\"Croix\" Class=\"Cellule\"></span>".
										"<span id=\"TTR_LUN\" Class=\"Cellule\">Lundi</span>".
										"<span id=\"TTR_MAR\" Class=\"Cellule\">Mardi</span>".
										"<span id=\"TTR_MER\" Class=\"Cellule\">Mercredi</span>".
										"<span id=\"TTR_JEU\" Class=\"Cellule\">Jeudi</span>".
										"<span id=\"TTR_VEN\" Class=\"Cellule\">Vendredi</span>".
										"<span id=\"TTR_SAM\" Class=\"Cellule\">Samedi</span>".
										"<span id=\"TTR_DIM\" Class=\"Cellule\">Dimanche</span>".
									"</div>".
									"<div id=\"LGN_SEM\" Class=\"Ligne\">".
										"<span id=\"LIB_nSemaine\" Class=\"Cellule\">".Date("W", $Date)."</span>";
				While ($Date <= $this->_Jour_Fin_Periode)
				{			
					$this->_CodeHTML .= "<span id=\"CEL_".Date("Ymd", $Date)."\" Class=\"Cellule".self::ClasseJour($Date)."\"><div>".Date("j", $Date)."</div>".self::ChercheRDV("Date", $Date)."</span>";
					$Date += self::JOUR_SECONDE;
				}			
				$this->_CodeHTML .= "</div>";
				Break;
			Case self::MOIS:
				$this->_CodeHTML .=	"<div id=\"RUB_TTR\" Class=\"Ligne\">".
										"<span id=\"Croix\" Class=\"Cellule\"></span>".
										"<span id=\"TTR_LUN\" Class=\"Cellule\">Lundi</span>".
										"<span id=\"TTR_MAR\" Class=\"Cellule\">Mardi</span>".
										"<span id=\"TTR_MER\" Class=\"Cellule\">Mercredi</span>".
										"<span id=\"TTR_JEU\" Class=\"Cellule\">Jeudi</span>".
										"<span id=\"TTR_VEN\" Class=\"Cellule\">Venvredi</span>".
										"<span id=\"TTR_SAM\" Class=\"Cellule\">Samedi</span>".
										"<span id=\"TTR_DIM\" Class=\"Cellule\">Dimanche</span>".
									"</div>".
									"<div id=\"LGN_SEM\" Class=\"Ligne\">".
										"<span id=\"LIB_nSemaine\" Class=\"Cellule\">".Date("W", $Date)."</span>";
				$i = 1;
				While ($i < Date("N", $Date))
				{
					$this->_CodeHTML .= "<span id=\"CEL_Vide\" Class=\"Cellule".self::ClasseJour($Date)."\"></span>";
					$i++;
				}
				While ($Date < $this->_Jour_Fin_Periode)
				{	
					$this->_CodeHTML .= "<span id=\"CEL_".Date("Ymd", $Date)."\" Class=\"Cellule".self::ClasseJour($Date)."\"><div>".Date("j", $Date)."</div>".self::ChercheRDV("Date", $Date)."</span>";
					$Date = $Date + self::JOUR_SECONDE;
					If (Date("N", $Date) == 1)
					{
						$this->_CodeHTML .= "</div>".
											 "<div id=\"LGN_SEM\" Class=\"Ligne\">".
												"<span id=\"LIB_nSemaine\" Class=\"Cellule\">".Date("W", $Date)."</span>";						
					}
				}
				$i = Date("N", $Date);
				While ($i <= 7)
				{
					$this->_CodeHTML .= "<span id=\"CEL_Vide\" Class=\"Cellule".self::ClasseJour($Date)."\"></span>";
					$i++;
				}
				$this->_CodeHTML .= "</div>";			
				Break;
			Case self::AN:
				$this->_CodeHTML .= "<div id=\"LGN_MOI\" class=\"Ligne\">";			
				For ($j = 1; $j <= 12; $j++)
				{
					$this->_CodeHTML .= "<div id=\"CEL_MOI\" class=\"Cellule\">".
											"<div id=\"RUB_TTR\" Class=\"Ligne\">".
												"<span id=\"Croix\" Class=\"Cellule\"></span>".
												"<span id=\"TTR_LUN\" Class=\"Cellule\">Lu</span>".
												"<span id=\"TTR_MAR\" Class=\"Cellule\">Ma</span>".
												"<span id=\"TTR_MER\" Class=\"Cellule\">Me</span>".
												"<span id=\"TTR_JEU\" Class=\"Cellule\">Je</span>".
												"<span id=\"TTR_VEN\" Class=\"Cellule\">Ve</span>".
												"<span id=\"TTR_SAM\" Class=\"Cellule\">Sa</span>".
												"<span id=\"TTR_DIM\" Class=\"Cellule\">Di</span>".
											"</div>".
											"<div id=\"LGN_SEM\" Class=\"Ligne\">".
												"<span id=\"LIB_nSemaine\" Class=\"Cellule\">".Date("W", $Date)."</span>";
					$i = 1;
					While ($i < Date("N", $Date))
					{
						$this->_CodeHTML .= "<span id=\"CEL_Vide\" Class=\"Cellule".self::ClasseJour($Date)."\"></span>";
						$i++;
					}
					$h = Date("n", $Date);
					While (Date("n", $Date) == $h)
					{	
						$this->_CodeHTML .= "<span id=\"CEL_".Date("Ymd", $Date)."\" Class=\"Cellule".self::ClasseJour($Date)."\"><div>".Date("j", $Date)."</div>".self::ChercheRDV("Date", $Date)."</span>";
						$Date = $Date + self::JOUR_SECONDE;
						If (Date("N", $Date) == 1 && Date("j", $Date) != 1)
						{
							$this->_CodeHTML .= "</div>".
												"<div id=\"LGN_SEM\" Class=\"Ligne\">".
													"<span id=\"LIB_nSemaine\" Class=\"Cellule\">".Date("W", $Date)."</span>";						
						}
					}
					$i = Date("N", $Date);
					While ($i <= 7 && $i <> 1)
					{
						$this->_CodeHTML .= "<span id=\"CEL_Vide\" Class=\"Cellule".self::ClasseJour($Date)."\"></span>";
						$i++;
					}
					$this->_CodeHTML .= "</div>".
								 "</div>";	
					If (($j % $this->_Nombre_Mois_Ligne) == 0 && $j <> 12)
					{
						$this->_CodeHTML .= "</div>".
									 "<div id=\"LGN_MOI\" class=\"Ligne\">";
					}
				}
				$this->_CodeHTML .= "</div>";
				Break;
			default:

		}
		$this->_CodeHTML .= "</div></form>";
		return $this->_CodeHTML;
	}
	
	Public Function DateHui()
	{
		Return Date($this->_Format_Date);
	}
	
	Private Function Selected($test)
	{
		If ($test)
		{
			return " selected";
		}
		Else
		{
			return "";
		}
	}
	
	Public Function Nombre_Jours_Periode()
	{
		return $this->_Nombre_Jours_Periode;
	}
	
	Private Function ChercheRDV($_Type, $_Valeur = '')
	{
		$CodexRDV = "<div class=\"CEL_RDV\">";
		foreach ($this->_Tab_Rdv as $Element) {
			switch ($_Type) {
				case "Date":
					if($Element->_Date_Debut <= $_Valeur && $_Valeur <= $Element->_Date_Fin)
					{
						$CodexRDV .= self::AfficheRDV($Element, $_Valeur);
					}
					break;
			}
		}
		$CodexRDV .= "</div>";
		
		Return $CodexRDV;
	}
	
	Private Function AfficheRDV($_RDV, $Date)
	{
		$CodexRDV = "<div id=\"RDV_ID{$_RDV->_Id}\" class=\"RDV\" onclick=\"hred('{$_RDV->_URL}')\" onmouseover=\"popup(event, '{$_RDV->_Nombre_Participant}', '{$_RDV->_Participant}', '{$_RDV->_Note}')\" onmouseout=\"killpopup();\" style=\"Background-color:{$_RDV->_Couleur_Fond}\">".
					"<div class=\"Titre\">{$_RDV->_Titre}</div>".
					"<div class=\"Corps\">{$_RDV->_Corps}</div>".
					"<Input name=\"RDV_ID".Date("Ymd", $Date)."\" type=\"hidden\" value=\"{$_RDV->_Lieu}\">".
					"</div>";
		Return $CodexRDV;
	}
	
	Private Function AjouterRdv($_RDV)
	{
		$this->_Tab_Rdv[] = $_RDV;
	}
	
	Private Function EnleverRdv($_Id)
	{
		unset($this->_Tab_Rdv[$_Id]);
		$this->_Tab_Rdv = array_values($this->_Tab_Rdv);
	}
	
	Public Function ChargementRDVSQL()
	{
		global $serveur;
		$HOST = "IP_Serveur";
		$username = "User_MYSQL";
		$PASSWORD = "******";
		$DATABASE = "BD_MYSQL";
		$serveur = mysqli_connect($HOST, $username, $PASSWORD, $DATABASE);
		$utf8 = mysqli_set_charset($serveur, "utf8");
	
		$Sql = "SELECT ID, LOGEMENT, DATEDEBUT, DATEFIN, TITRE, CORPS, COULEURFOND, NOTE, CATEGORIE, CREATEUR, ".
			   "PARTICIPANT, NOMBREPARTICIPANT ".
			   "FROM AGENDA ".
			   "WHERE ('".Date("Y-m-d", $this->_Jour_Debut_Periode)."' <= DATEDEBUT AND DATEDEBUT <= '".Date("Y-m-d", $this->_Jour_Fin_Periode)."') OR ".
			   "('".Date("Y-m-d", $this->_Jour_Debut_Periode)."' <= DATEFIN AND DATEFIN <= '".Date("Y-m-d", $this->_Jour_Fin_Periode)."') OR ".
			   "(DATEDEBUT <= '".Date("Y-m-d", $this->_Jour_Debut_Periode)."' AND '".Date("Y-m-d", $this->_Jour_Fin_Periode)."' <= DATEFIN)";
		$req = mysqli_query($serveur, $Sql);
		while ($row = mysqli_fetch_array($req, MYSQLI_NUM))
		{
			$_RDV = new Rendez_Vous($row[0], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], "", $row[1], $row[9], $row[10], $row[11]);
			self::AjouterRdv($_RDV);
		}
	}
	
	Public Function ClasseJour($Date)
	{
		if(Date("Ymd", time()) == Date("Ymd", $Date)){Return " Hui";}
		if($Date < time()){Return " Inactive";}
		if($Date > time()){Return " Active";}
		Return "";
	}
}

Class Rendez_Vous{	
	
	//string
	Public $_Titre;
	Public $_Corps;
	Public $_Note;
	Public $_Categorie;
	Public $_URL;
	Public $_Lieu;
	Public $_Createur;
	Public $_Participant;
	
	//Date
	Public $_Date_Debut;
	Public $_Date_Fin;
	
	//int
	Public $_Duree_Jours;
	Public $_Id;
	Public $_Nombre_Participant;
	Public const JOUR_SECONDE = 24*60*60;
	
	//Couleur
	Public $_Couleur_Fond;
	
	Public Function __construct($Id = 0, $DateDebut = "", $DateFin = "", $Titre = "", $Corps = "", $Couleur_Fond = "", $Note = "", $Categorie = "", $Url = "", $Lieu = "", $Createur = "", $Participant = "", $Nombre_Participant = 0)
	{
		//string
		$this->_Titre = $Titre;
		$this->_Corps = $Corps;
		$this->_Note = $Note;
		$this->_Categorie = $Categorie;
		$this->_URL = $Url;
		$this->_Lieu = $Lieu;
		$this->_Createur = $Createur;
		$this->_Participant = $Participant;
		
		//Date
		$this->_Date_Debut = strtotime($DateDebut);
		if ($DateDebut = ""){$this->_Date_Debut = time();}
		$this->_Date_Fin = strtotime($DateFin);
		if ($DateFin = ""){$this->_Date_Fin = time();}
		
		//int
		$this->_Duree_Jours = ($this->_Date_Fin - $this->_Date_Debut) / self::JOUR_SECONDE;
		$this->_Id = $Id;
		$this->_Nombre_Participant = $Nombre_Participant;
		
		//Couleur
		$this->_Couleur_Fond = $Couleur_Fond;
	}
}

$Calendrier = new Calendrier;
if(isset($_POST['Periode']))
{
	$Calendrier->_Periode_Affichee = $_POST['Periode'];
}
Else
{
	$Calendrier->_Periode_Affichee = $Calendrier::AN; 
}
$Calendrier->_Jour_Affichee = time();
if(isset($_POST['Periode-change']) && isset($_POST['Periode-affiche']))
{
	$Calendrier->_Jour_Affichee = $_POST['Periode-affiche'] + ($Calendrier->Nombre_Jours_Periode() * $_POST['Periode-change'] * $Calendrier::JOUR_SECONDE);
}
$Calendrier->ChargementRDVSQL();
echo $Calendrier->Affiche();


?>