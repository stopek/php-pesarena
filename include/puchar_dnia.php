<?
if (!defined('PEBLOCK') || !PEBLOCK) {
 header('HTTP/1.1 301 Moved Permanently');
 header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
 exit;
}

require_once('include/functions/function.puchar.php');
require_once('include/functions/function.host.php');
require_once('include/functions/function.admin-druzyny.php');
require_once('include/functions/other/other.history.php');

$wlaczona_gra=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_PUCHAR_DNIA_LISTA." where vliga='{$l_u}' && status!='4' && id='".R_ID_P."';"));
$status_pucharu=mysql_fetch_array(mysql_query("SELECT * FROM ".TABELA_PUCHAR_DNIA_LISTA." where vliga='{$l_u}' && status!='4' && id='".R_ID_P."';"));
$id=(int)$_GET['podopcja'];	
$glos_k_ = $_POST['glos_k_'];
$komentarze_opcje = array('0','+','-');

if (!empty($sesja_puchar))
{
	// menu bez znaczenia na status pucharu 
	switch ($podmenu)
	{
		//pokazujemy zwyciezcow pucharow dnia
		case 'zwyciezcy': 	
			zwyciezcy_pucharu_dnia($l_u);				
		break;
		//pokazujemy zwyciezcow pucharow dnia - END

		//pokazujemy tabele glowne pucharu
		case 'glowny': 
			pokaz_terminarz_pucharu_dnia($l_u,'1_1');
			pokaz_terminarz_pucharu_dnia($l_u,'1_2');
			pokaz_terminarz_pucharu_dnia($l_u,'1_4');
			pokaz_terminarz_pucharu_dnia($l_u,'1_8');
			pokaz_terminarz_pucharu_dnia($l_u,'1_16');
		break;
		//pokazujemy tabele glowne pucharu - END
		
		//pokazujemy wyniki pucharowe
		case 'wyniki':				
			echo "<div id=\"zakladka_1_pu\" class=\"display_none\">"; 
				pokaz_mecze_eliminacji($id_zalogowanego_usera,'this'); 
			echo "</div>
			<div id=\"zakladka_2_pu\" class=\"display_none\">"; 
				pokaz_mecze_eliminacji($id_zalogowanego_usera,'all'); 
			echo "</div>";
		break;
		//pokazujemy wyniki pucharowe - END
		
		//pokazujemy wszystkie mecze pucharowe
		case 'historia': 
			historia_wszystkich_meczy(TABELA_PUCHAR_DNIA,DEFINIOWANA_GRA); 
		break;
		//pokazujemy wszystkie mecze pucharowe - END
		
		//pokazujemy tabele i teraminarz eliminacji jesli status 1
		case 'eliminacje':
			if ($status_pucharu['status']=='1')
			{
				pokaz_terminarz_eliminacji($l_u); 	
				pokaz_tabele_eliminacji($l_u,'dla_usera',R_ID_P);
			} 
			else
			{
				note(M263,"info");
			}
		break;	
		//pokazujemy tabele i teraminarz eliminacji jesli status 1 - END
	} // menu gdy nie ma pucharu 


	if (!empty($wlaczona_gra)) 
	{
		switch ($podmenu) 
		{
		
			//konczymy spotkanie, oddajemy glos za ocene hostu
			case 'zakoncz,spotkanie': 	
				if (isset($glos_k_) && in_array($glos_k_,$komentarze_opcje))  { zakoncz_spotkanie($id,$id_zalogowanego_usera,TABELA_PUCHAR_DNIA); }
				else 
				{
					reklamy_adkontekst();
					if (!empty($glos_k_) && !in_array($glos_k_,$komentarze_opcje)) { note("Ocen host swojego przeciwnika","blad"); }
					formularz_komentarz();
				}
			break;
			//konczymy spotkanie, oddajemy glos za ocene hostu - END
			
			//odrzucamy wynik
			case 'odrzuc,wynik': 		
				odrzuc_wynik((int)$_GET['podopcja'],$id_zalogowanego_usera,TABELA_PUCHAR_DNIA); 
			break;
			//odrzucamy wynik - END
			
			//pokazujemy uczestnikow gry
			case 'uczestnicy':		
				pokaz_uczestnikow_gry($l_u,TABELA_PUCHAR_DNIA_GRACZE,R_ID_P);
			break;
			//pokazujemy uczestnikow gry - END
		 
			//podajemy wynik
			case 'podaj,wynik':
				if (!empty($_POST['wynik_spotkania'])) 
				{
					if (!in_array($glos_k_,$komentarze_opcje)) 
					{ 
						podaj_wynik((int)$_POST['przykladowy_wynik_1'],(int)$_POST['przykladowy_wynik_2'],(int)$_POST['id_spotkania'],$id_zalogowanego_usera,TABELA_PUCHAR_DNIA);
					}
					else
					{
						note("Ocen host swojego przeciwnika","blad"); 
					}
				} 
				else
				{
					puchar_podaj_wynik($id,$id_zalogowanego_usera);
				}
			break;
			//podajemy wynik - END
		}
	}
	else
	{
		note("Ten puchar  jest juz zakonczony","blad");
	} 
}
else
{
	switch ($podmenu)
	{
		case 'historia': 
			historia_wszystkich_meczy(TABELA_PUCHAR_DNIA,DEFINIOWANA_GRA); 
		break;
		case 'zwyciezcy': 	
			zwyciezcy_pucharu_dnia($l_u);				
		break;
		default: note(M402,"blad"); break;
	}
	
}	

// glowne menu pucharu dnia --> koniec//
?>
