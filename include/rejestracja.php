<?if (!defined('PEBLOCK') || !PEBLOCK) {
 header('HTTP/1.1 301 Moved Permanently');
 header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
 exit;
}
require_once('include/functions/function.admin-druzyny.php');
if (empty($zalogowany))
{
// deklarowanie zmiennych i czyszczenie 
$login=czysta_zmienna_post($_POST['login']);
$gadu=(int)czysta_zmienna_post($_POST['gadu']);
$mail=czysta_zmienna_post($_POST['mail']);

$ex = explode('-',$_POST['klub']);
$klub=(int)$ex[0]; 

$haslo=czysta_zmienna_post($_POST['haslo']);
$pseudo=czysta_zmienna_post($_POST['pseudo']);
$phaslo=czysta_zmienna_post($_POST['phaslo']);
$versja=czysta_zmienna_post($_POST['versja']);
$ip=$_SERVER['REMOTE_ADDR'];
$host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
$przegladarka=nazwij_przegladarke($_SERVER['HTTP_USER_AGENT']);

$login=czysta_zmienna_post($_POST['login']);
$zakoncz=czysta_zmienna_post($_POST['zakoncz']);

if (!empty($zakoncz) && $zakoncz=='przejdz') 
{
	// deklarowanie zmiennych na wypadek bledow
		$vlogin=$_POST['login'];
		$vpseudo=$_POST['pseudo'];
		$vmail=$_POST['mail'];
		$vgadu=$_POST['gadu'];
		$vversja=$_POST['versja'];

	// warunki ze zmienne musza istniec 


		 
	if (
		!empty($login) && 
		!empty($pseudo) && 
		!empty($haslo) && 
		!empty($phaslo) && 
		!empty($mail) && 
		isset($gadu) && 
		!empty($mail) && 
		!empty($versja)) 
	{

		 

		 // sprawdzanie i kodowanie  
				$haslo=kodowanie_hasla($haslo);
				$phaslo=kodowanie_hasla($phaslo);
				$login=kodowanie_loginu($login);
				$czylogin=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_UZYTKOWNICY." WHERE login='{$login}';"));
				$czypseudo=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_UZYTKOWNICY." WHERE pseudo='{$pseudo}';"));
				$mailof=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_UZYTKOWNICY." WHERE mail='{$mail}';"));
				$sploginow=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_UZYTKOWNICY." WHERE login='{$login}';"));
				$spgg=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_UZYTKOWNICY." WHERE gadu='{$gadu}';"));
				$spip=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_UZYTKOWNICY." WHERE ip='{$ip}';"));
		// sprawdzanie poprawnosci 
		if (poprawnosc_rejestracja($login) && 
		poprawnosc_rejestracja($pseudo) && 
		sprawdz_mail($mail) && 
		sprawdz_gadu($gadu) &&
		empty($mailof) && 
		empty($sploginow) && 
		empty($spgg) && 
		(empty($spip) || gracze_uprzywilejowani($login,$ip)) && 
		poprawnosc_rejestracja($haslo) && 
		$haslo==$phaslo) {
			// dodanie nowego uzytkownika 
			$zapytanie=mysql_query("INSERT INTO ".TABELA_UZYTKOWNICY." values('','{$pseudo}',
			'{$gadu}','{$mail}','{$klub}','{$haslo}','{$login}','1',NOW(),'{$versja}','',
			'0','{$ip}','{$host}','{$przegladarka}');");
			$wynik2=mysql_query("INSERT INTO ".TABELA_GRACZE." values('','".sprawdz_id_login($pseudo)."','','','','','','','','{$versja}','1','0','','');");
			note(M400."<br/>
			".linkownik('gg','31','')." (MIT)<br/>
			".linkownik('gg','792','')." (Polana)<br/>

			","info");
			
		// czyszczenie zmiennych od bledow
			unset($vlogin);
			unset($vpseudo);
			unset($vmail);
			unset($vgadu);
			unset($vdzien);
			unset($vmiesiac);
			unset($vrok);
			unset($vversja);
		}  else {
			$bledy=1;
			if(empty($gadu)) { $skladanka.="-".M399."<br/>";	$b_gg = TRUE;}
			if (!empty($czypseudo))    {						$skladanka.="-".M187."<br/>";	$vpseudo=''; $b_pseudo = TRUE;	} 
			if (!empty($mailof))      	{						$skladanka.="-".M188."<br/>";	$vmail=''; $b_mail = TRUE;	} 
			if (!empty($sploginow))      	{					$skladanka.="-".M553."<br/>";	$vmail=''; $b_mail = TRUE;	} 
			if (!empty($spgg))      	{						$skladanka.="-".M332."<br/>";	$vgadu=''; $b_gg = TRUE;	} 
			if (!empty($spip))      	{						$skladanka.="<b>-".M398."</b><br/>";} 
			if ($haslo!=$phaslo){								$skladanka.="-".M189."<br/>";      $b_haslo = TRUE;         	}
			if (sprawdz_mail($mail)=='0' && !empty($mail)) {	$skladanka.="-".M190."<br/>";   $vmail='';  $b_mail = TRUE;	}
			if (!in_array($versja,$wersje_gry))       {			$skladanka.="-".M191."<br/>";     		$b_versja = TRUE;		}
			if (!poprawnosc_rejestracja($pseudo)) {				$skladanka.="-".M395."<br/>";   $b_pseudo = TRUE;  				}
			if (!poprawnosc_rejestracja($_POST['login'])) {		$skladanka.="-".M396."<br/>";     	$b_login = TRUE;			}
			if (!poprawnosc_rejestracja($_POST['haslo'])) {		$skladanka.="-".M397."<br/>";     	$b_haslo = TRUE;			}
			note("<u>".M401."</u><br/>".$skladanka,"blad");
		}
	} 
	else  
	{
		note(M192,"blad");
	}
}
	$regulamin=(int)$_POST['regulamin'];
	$etap2=(int)$_POST['etap2'];
	if (!empty($etap2) || !empty($zakoncz))  
	{
		if ($regulamin=="1" || !empty($zakoncz)) {
		?>
		
		<form method="post" action="" id="formularz_rejestracji">	
		<input type="hidden" name="zakoncz" value="przejdz">
		<div class="rejestracja_naglowki"><?=M199?></div>
		<div id="rejestracja">
			<div class="regal">
				<div class="rejestracja_lewa"><?=M97?></div>
				<div class="rejestracja_prawa"><input type="text" name="login" value="<?=$vlogin?>" />
				<?=(!empty($b_login) ? "<img src=\"img/error.png\" alt=\"\"/>" : "")?>
				</div>
			</div>
			<div class="regal">
				<div class="rejestracja_lewa"><?=M98?></div>
				<div class="rejestracja_prawa"><input type="password" name="haslo"/>
				<?=(!empty($b_haslo) ? "<img src=\"img/error.png\" alt=\"\"/>" : "")?>
				</div>
			</div>
			<div class="regal">
				<div class="rejestracja_lewa"><?=M198?></div>
				<div class="rejestracja_prawa"><input type="password" name="phaslo"/>
				<?=(!empty($b_haslo) ? "<img src=\"img/error.png\" alt=\"\"/>" : "")?>
				</div>
			</div>
			<div class="regal">
				<div class="rejestracja_lewa"><?=M197?></div>
					<div class="rejestracja_prawa"><input type="text" name="pseudo" value="<?=$vpseudo?>"/>
					<?=(!empty($b_pseudo) ? "<img src=\"img/error.png\" alt=\"\"/>" : "")?>
					</div>
				</div>
			<div class="regal">
				<div class="rejestracja_lewa"><?=M82?></div>
				<div class="rejestracja_prawa"><input type="text" name="mail" value="<?=$vmail?>"/>
				<?=(!empty($b_mail) ? "<img src=\"img/error.png\" alt=\"\"/>" : "")?>
				</div>
				</div>
			<div class="regal">
				<div class="rejestracja_lewa"><?=M81?></div>
				<div class="rejestracja_prawa"><input type="text" name="gadu" value="<?=$vgadu?>"/>
				<?=(!empty($b_gg) ? "<img src=\"img/error.png\" alt=\"\"/>" : "")?>
				</div>
			</div>
			<div class="regal">
				<div class="rejestracja_lewa"><?=M26?></div>
				<div class="rejestracja_prawa"><?wybor_klubu('klub');?></div>
			</div>
			<div class="regal">
				<div class="rejestracja_lewa"><?=M195?></div>
				<div class="rejestracja_prawa">
					<select name="versja">
						<option value="">
						<?foreach ($wersje_gry as $value)	{	print "<option value=\"{$value}\" ".($vversja==$value ? 'selected=selected' : '').">".$opis_gry[$value][0];	}?>
					</select>
				</div>
			</div>
			<div class="text_center"><input type="image" src="img/zakoncz.jpg"/></div>
		</div>
		<div class="rejestracja_naglowki"><?=M333?></div>
		</form><br/>	
		<?
		
		} else {
			note(M193,'blad');
		}
	} 
	else 
	{
		if (empty($etap2) && empty($zakoncz)) 
		{
			?>
			<form method="post" action="">
			<input type="hidden" name="etap2" value="1">
			<iframe width="100%" height="300" src="doc/regulamin_strony.php" frameborder="no"></iframe><br/>
			<input type="checkbox" name="regulamin" value="1"/><?=M194?><br/>
			<input type="image" src="img/etap2.jpg" title="<?=M334?>" alt="<?=M334?>"/>
			</form><br/>
			<?
		}
	}
}
else
{
	include("include/profil_usera.php");
}
?>








