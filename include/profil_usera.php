<?
if (!defined('PEBLOCK') || !PEBLOCK) {
 header('HTTP/1.1 301 Moved Permanently');
 header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
 exit;
}	
$kogo=(int)$_GET['podmenu'];
if (empty($kogo)) 
{ $kogo=$id_zalogowanego_usera; } 
//$_SESSION['zalogowany'] = sprawdz_login_id($_GET['podmenu']);
$licz=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_UZYTKOWNICY." where id='".$kogo."';"));
require_once('include/functions/other/other.history.php');
require_once('include/functions/function.admin-druzyny.php');
	
if ($licz=='0') 
{
	note(M60,"blad");
} 
else 
{
	$wl=mysql_fetch_array(mysql_query("SELECT * FROM ".TABELA_GRACZE." where id_gracza='".$kogo."' && vliga='".$l_u."';"));			
	if ($wl['status']==1)
	{
	$id=$wyzwanie=(int)$_POST['wyzwanie'];
	if (czysta_zmienna_post($_POST['wyzwanie']))
	{
		$last_add=mysql_fetch_array(mysql_query("SELECT * FROM ".TABELA_WYZWANIA." where n1='".$id_zalogowanego_usera."' && vliga='".$l_u."' order by rozegrano desc;"));
		$wczesniej=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_WYZWANIA." where n1='".$id_zalogowanego_usera."' && vliga='".$l_u."';"));
		$czeka=poczekaj($last_add[8],60);
		$czy_masz_taka_gre=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_GRACZE." where vliga='".$l_u."' && id_gracza='".$id_zalogowanego_usera."';"));
		$czy_ma_taka_gre_2=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_GRACZE." where vliga='".$l_u."' && id_gracza='".$id."';"));
		$ileee=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_WYZWANIA." where n1='".$id_zalogowanego_usera."' && n2='".$id."' && status!='3' && vliga='".$l_u."';"));
		$klub_1=(int)czysta_zmienna_post(id_druzyny($_POST['klub_1']));
		$chec=(int)$_POST['zapis_do'];
		$mozliwe_checi=array('5');
		
		if ($czeka<"0" || $wczesniej=="0") 
		{
			if (!empty($id) && !empty($id)) 
			{
				if ($id!=$id_zalogowanego_usera) 
				{
					if ($czy_masz_taka_gre==1) 
					{
						if ($czy_ma_taka_gre_2==1) 
						{
							if ($ileee=='0') 
							{
								if (!empty($klub_1))
								{
									add_to_poker_rank($chec,$mozliwe_checi,$id_zalogowanego_usera);
									
									
									
									
									$wynik=mysql_query ("INSERT INTO ".TABELA_WYZWANIA." 
									values('','".$id_zalogowanego_usera."','".$id."','','','p','','','','".$l_u."','".$klub_1."','');");
									note(M24,"info");
								} else { note(M25,"blad"); }
							} else {note(M28,"blad"); }
						} else { note(M264,"blad"); }
					} else { note(M265,"blad"); }
				} else { note(M266,"blad"); }
			} else { note(M29,"blad"); }
		} else { note(M30,"blad"); }
	}	
	
	
										
	
	
	$a2=mysql_query("SELECT * FROM ".TABELA_UZYTKOWNICY." where id='".$kogo."';");
	$rek=mysql_fetch_array($a2);
	
	
	
	if ($a = mysql_query("SELECT * FROM ".TABELA_VIP." where id_gracza='".$kogo."';")){$vip=mysql_fetch_array($a);}
	
	
	
	
	$a4=mysql_query("SELECT * FROM ".TABELA_OSTRZEZENIA." where id_gracza='".$kogo."';");
	$dodatkowe=mysql_query("SELECT * FROM ".TABELA_DODATKOWE_PUNKTY." where id_gracza='".$kogo."';");
	$klub=mysql_fetch_array(mysql_query("SELECT nazwa FROM druzyny where id='".$rek['klub']."';"));
	$licz=mysql_num_rows($a4);
		$ostatnio=strtotime($rek['dolaczyl']);
		$teraz=strtotime("NOW"); 
		$dni = floor(($teraz-$ostatnio)/86400);
		
		$godzin=floor((($teraz-$ostatnio)%86400)/3600);
		$last_log=str_replace("0000-00-00 00:00:00","<i>".M72."</i>",$rek['last_log']);
		$pkt_za_mecze = sprawdz_ranking_id($kogo,DEFINIOWANA_GRA)-dodatkowe_punkty($kogo);
		$pkt_dodatkowe = dodatkowe_punkty($kogo);
		$gracz_po_id=sprawdz_login_id($kogo);
		$strzelone_bramki = strzelone_bramki($kogo,DEFINIOWANA_GRA);
		$stracone_bramki = stracone_bramki($kogo,DEFINIOWANA_GRA);
		$sumka = $wl['m_w']+$wl['m_p']+$wl['m_r'];
		
		$strzelone_bramki_srednio = round($strzelone_bramki/$sumka,2);
		$stracone_bramki_srednio = round($stracone_bramki/$sumka,2);
		
		$meczy_w = round($wl['m_w']/$sumka,2)*100 .'%';
		$meczy_p = round($wl['m_p']/$sumka,2)*100 .'%';
		$meczy_r = round($wl['m_r']/$sumka,2)*100 .'%';
		
		$meczy_na_dzien = round($sumka/$dni,2);

if ($kogo == 31) { echo "<center><img src=\"grafiki/vip/vipMit.jpg\"/></center>"; }

		?>
		
		
		
		
		
		<div id="dodatkowe" class="display_none">
			<fieldset><legend><?M403?><b><?=$gracz_po_id?></b></legend>
				<table border="1" width="100%" frame="void">
					<tr class="naglowek_czerwony">
						<td>Nr.</td>
						<td>Punkty</td>
						<td>Opis</td>
						<td>Wystawiono</td>
					</tr>

						<?
						while($reko=mysql_fetch_array($dodatkowe))
						{	$a++;
							echo "<tr".kolor($a)."><td></td><td>{$reko['punkty']}</td><td>{$reko['opis']}</td><td>".formatuj_date($reko['wystawiono'])."</td></tr>";
						} if (empty($a))  note("<b>{$gracz_po_id}</b>".M404,"blad"); 
						?>
				</table>
			</fieldset>
		</div>
		<?=reklamy_adkontekst();?>
			
			
			
			
			
			
			
			
			
			
			
		<fieldset><legend><?=M405?><b><?=$gracz_po_id?></b> w gre: <b><?=$opis_gry[$l_u][0]?></b></legend>
		<table width="100%" border="0">
			<tr>
			<td valign="top"  width=283>
				
				
				
				<table border="1" width="100%" frame="void">
					<tr class="naglowek_czerwony">
						<td colspan="2"><?=M71?></td>
					</tr>
					<tr bgcolor="#ececec">
						<td  width="143"><?=M61?></td>
						<td><?=$rek['pseudo']?></td>
					</tr>
					<tr>
						<td><?=M62?></td>
						<td><?=linkownik('profil_druzyny',$rek['klub'],$klub['nazwa'])?></td>
					</tr>
					<tr bgcolor="#ececec">
						<td><?=M63?></td>
						<td><?=$opis_gry[$rek['vliga']][0]?></td>
					</tr>
					<tr>
						<td><?=M64?></td>
						<td><a href="javascript:rozwin('kartki');" title="<?=M390?>"><?=M390?> (<?=$licz?>)</div></td>
					</tr>
					<tr bgcolor="#ececec">
						<td><?=M65?></td>
						<td><?=formatuj_date($rek['last_log'])?></td>
					</tr>
					<tr>
						<td><?=M66?></td>
						<td><?=formatuj_date($rek['dolaczyl'])?></td>
					</tr>
					<tr bgcolor="#ececec">
						<td><?=M67?></td>
						<td><?=$rek['counter']?></td>
					</tr>
					<tr>
						<td><?=M68?></td>
						<td><b><?=$dni?></b> <?=M406?>, <b><?=$godzin?></b> <?=M70?></td>
					</tr>
					<tr bgcolor="#ececec">
						<td  width="143">Gadu</td>
						<td><a href="gg:<?=$rek['gadu']?>"><?=$rek['gadu']?></a></td>
					</tr>
				</table>
				
	
				<table  border="1" width="100%" frame="void">
					<tr class="naglowek_czerwony"><td colspan="2"><?=M83?></td></tr>
					<tr  bgcolor="#ececec">
						<td width="143"><?=M407?></td>
						<td><?=sprawdz_zgode_na_host($rek['id'])?></td>
					</tr>
			
					<tr  bgcolor="#ececec">
						<td width="143"><?=M86?></td>
						<td><?=$rek['przegladarka']?></td>
					</tr>
				</table>
							
				<table  border="1" width="100%" frame="void">
					<tr class="naglowek_czerwony"><td colspan="2"><?=M408?></td></tr>
					<tr bgcolor="#ececec">
						<td width="143"><?=M409?></td>
						<td><?=licz_komentarze_gracza($kogo,'+')?></td>
					</tr>
					<tr>
						<td  width="143"><?=M410?></td>
						<td><?=licz_komentarze_gracza($kogo,'-')?></td>
					</tr>
					<tr  bgcolor="#ececec">
						<td  width="143"><?=M411?></td>
						<td><?=licz_komentarze_gracza($kogo,'0')?></td>
					</tr>
				</table>
				
				
				
				
				
				
			</td>
			<td valign="top" width=283>

				<!--  -->
				<table  border="1" width="100%" frame="void">
				<tr class="naglowek_czerwony"><td colspan="2"><?=M416?></td></tr>
				<tr  bgcolor="#ececec">
					<td  width="143"><?=M412?></td>
					<td><?=$pkt_za_mecze?></td>
				</tr>
				<tr  bgcolor="#ececec">
					<td  width="143"><?=M414?></td>
					<td><?=$pkt_dodatkowe?> (<a href="javascript:rozwin('dodatkowe');" title=""><?=M413?></a>)</td>
				</tr>
				<tr  bgcolor="#ececec">
					<td  width="143"><?=M415?></td>
					<td><?=sprawdz_ranking_id($kogo,DEFINIOWANA_GRA)?></td>
				</tr>
				<tr bgcolor="#ececec">
					<td><?=M78?></td>
					<td><?=miejsce_w_rankingu(DEFINIOWANA_GRA,$kogo)?></td>
				</tr>
				</table>
				
				
				
				
				
				
				
				
				
				
				<table border="1" width="100%" frame="void">
					<tr class="naglowek_czerwony"><td colspan="2"><?=M73?></td></tr>
					<tr bgcolor="#ececec">
						<td width="143"><?=M74?></td>
						<td><?=$sumka?></td>
					</tr>
					<tr>
						<td  width="143"><?=M75?></td>
						<td><?=$wl['m_w']?></td>
					</tr>
					<tr bgcolor="#ececec">
						<td  width="143"><?=M76?></td>
						<td><?=$wl['m_p']?></td>
					</tr>
					<tr>
						<td  width="143"><?=M77?></td>
						<td><?=$wl['m_r']?></td>
					</tr>
					<tr>
						<td  width="143"><?=M417?> +/-</td>
						<td><?=$strzelone_bramki?> / <?=$stracone_bramki?></td>
					</tr>
				</table>
				
				
				
				<table border="1" width="100%" frame="void">
					<tr class="naglowek_czerwony"><td colspan="2"><?=M418?></td></tr>
					<tr bgcolor="#ececec">
						<td width="143"><?=M421?> +/-</td>
						<td><?=$strzelone_bramki_srednio?> / <?=$stracone_bramki_srednio?></td>
					</tr>
					<tr>
						<td  width="143"><?=M419?></td>
						<td><?=$meczy_na_dzien?></td>
					</tr>
					<tr bgcolor="#ececec">
						<td  width="143"><?=M420?> + / - / 0 </td>
						<td><?=$meczy_w?>/<?=$meczy_p?>/<?=$meczy_r?></td>
					</tr>
				</table>
			



				<a href="javascript:rozwin('graj');" title="<?=M270?>"><div id="zagraj"></div></a>
				<div id="graj">
					<form method="post" action="">
						<?=check_player_poker_rank($id_zalogowanego_usera)?>
						<fieldset>
							<input type="hidden"  name="wyzwanie" value="<?=$kogo?>"/>
							<div style="float:left;"><?=wybor_klubu_czysto('klub_1','')?></div>
							
							<div class="float:right; margin-left:10px;"> 
							<input  type="image" src="img/rzuc_wyzwanie.jpg" title="<?M59?>" alt="<?M59?>"/>
							</div>
							
						</fieldset>
					</form>
				</div>
				
			</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="kartki" class="text_center">
						<fieldset><?
							while($card=mysql_fetch_array($a4))
							{
								echo "<div class=\"kartka\"><i>{$card['data']}</i><br/>{$card['opis']}</div>";
							}
							if (empty($licz)) { note(M389,'blad'); }?>
						</fieldset>
					</div>
				</td>
			</tr>
			
		</table>
		</fieldset>
		
		<?
			$cup = mysql_query("
			SELECT count(*),MAX(id),rozegrano,r_id
			FROM ".TABELA_PUCHAR_DNIA." WHERE (n1='{$kogo}' ||  n2='{$kogo}') 
			&& vliga='".DEFINIOWANA_GRA."' GROUP BY r_id ORDER BY id DESC"); $b=1;

			while ($rek = mysql_fetch_array($cup))
			{
				$rdd = mysql_fetch_array(mysql_query("SELECT spotkanie FROM ".TABELA_PUCHAR_DNIA." WHERE id='{$rek[1]}'"));
					$bramki = mysql_fetch_array(mysql_query("SELECT sum(w1),
					(SELECT sum(w2) FROM ".TABELA_PUCHAR_DNIA." WHERE n2='{$kogo}'  && vliga='".DEFINIOWANA_GRA."' && r_id='{$rek[3]}')
					FROM ".TABELA_PUCHAR_DNIA." WHERE n1='{$kogo}' && vliga='".DEFINIOWANA_GRA."' && r_id='{$rek[3]}'"));
					
					$arr[$b][] = $b++;
					$arr[$b][] = $rek[2];
					$arr[$b][] = $rek[0];
					$arr[$b][] = str_replace(array('_','ele','1/1'),array('/','eliminacje','Final'),$rdd[0]);
					$arr[$b][] = $bramki[0]+$bramki[1];
					
			}
	
	
	require_once('include/admin/funkcje/function.table.php');	

	

	
	$table = new createTable('adminFullTable center',"Udzia³ w pucharach dnia");
	$table -> setTableHead(array('','data','rozegral meczy','doszedl do','bramek'),'adminHeadersClass');	
	$table -> setTableBody($arr);
	echo "<fieldset><legend><a href=\"javascript:rozwin('c');\">[pokaz liste pucharow]</a></legend><div id=\"c\"  class=\"display_none\">{$table->getTable()}</div></fieldset>";
	
	//licze mecze pucharowe
	$a = mysql_fetch_array(mysql_query("SELECT count(*)
			FROM ".TABELA_PUCHAR_DNIA." WHERE (n1='{$kogo}' ||  n2='{$kogo}') 
			&& vliga='".DEFINIOWANA_GRA."'")); $v[0] = $a[0];
		

	//licze wygrane 
	$a = mysql_fetch_array(mysql_query("SELECT count(*)
			FROM ".TABELA_PUCHAR_DNIA." WHERE ((n1='{$kogo}' && w1>w2)   || (n2='{$kogo}' && w1<w2)) 
			&& vliga='".DEFINIOWANA_GRA."'")); $v[1] = $a[0];
	
	//licze przegrane 
	$a = mysql_fetch_array(mysql_query("SELECT count(*)
			FROM ".TABELA_PUCHAR_DNIA." WHERE ((n1='{$kogo}' && w1<w2)   || (n2='{$kogo}' && w1>w2)) 
			&& vliga='".DEFINIOWANA_GRA."'")); $v[2] = $a[0];
	
	//licze remisy
	$a = mysql_fetch_array(mysql_query("SELECT count(*)
		FROM ".TABELA_PUCHAR_DNIA." WHERE (n1='{$kogo}' || n2='{$kogo}') && w1=w2
		&& vliga='".DEFINIOWANA_GRA."'")); $v[3] = $a[0];
	
	
	
	
	
			
	//licze wygrane finaly - miejsce 1
	$a = mysql_fetch_array(mysql_query("SELECT count(*)
			FROM ".TABELA_PUCHAR_DNIA." WHERE ((n1='{$kogo}' && w1>w2)   || (n2='{$kogo}' && w1<w2))  && spotkanie = '1_1'
			&& vliga='".DEFINIOWANA_GRA."'")); $v[4] = $a[0];
	
	//licze przegrane finaly - miejsce 2
	$a = mysql_fetch_array(mysql_query("SELECT count(*)
			FROM ".TABELA_PUCHAR_DNIA." WHERE ((n1='{$kogo}' && w1<w2)   || (n2='{$kogo}' && w1>w2))  && spotkanie = '1_1'
			&& vliga='".DEFINIOWANA_GRA."'")); $v[5] = $a[0];
	
	//licze wygrane polfinaly - miejsce 3
	$a = mysql_fetch_array(mysql_query("SELECT count(*)
		FROM ".TABELA_PUCHAR_DNIA." WHERE ((n1='{$kogo}' && w1>w2)   || (n2='{$kogo}' && w1<w2))  && spotkanie = '1_2'
		&& vliga='".DEFINIOWANA_GRA."'")); $v[6] = $a[0];
	
	
	
	echo "<fieldset><legend><a href=\"javascript:rozwin('sp');\">[pokaz statystyki pucharowe]</a></legend><div id=\"sp\"  class=\"display_none\">
		rozegranych meczy pucharowych: {$v[0]}<br/>
		wygranych meczy pucharowych: {$v[1]}<br/>
		przegranych meczy pucharowych: {$v[2]}<br/>
		zremisowanych meczy pucharowych: {$v[3]}<br/>
		
		<br/>
		miejsc I w pucharach: {$v[4]}<br/>
		miejsc II w pucharach: {$v[5]}<br/>
		<br/>
		miejsc 3 w pucharach: {$v[6]} <small>*</small><br/>
		
		<i><small>*</small>osoby, ktore przegraly w polfinalach maja 3cie miejsce</i>
	
	</div></fieldset>";
		
		
		?>
		
		
		
		<div class="text_center"><?=(!empty($vip['id_gracza']) ? "<img src=\"{$vip['img']}\" alt=\"\"/>" : null)?></div>
		<?
			historia_meczy($kogo,TABELA_WYZWANIA);
			historia_meczy($kogo,TABELA_PUCHAR_DNIA);	
			historia_meczy($kogo,TABELA_LIGA);	
			historia_meczy($kogo,TABELA_TURNIEJ);	
		}
		else
		{
			note(M264." <b>{$opis_gry[$l_u][0]}</b>","blad");
		}
}
?>
