<?
if (!defined('PEBLOCK') || !PEBLOCK) {
 header('HTTP/1.1 301 Moved Permanently');
 header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
 exit;
}


if (eregi("^([0-9])+$", $podmenu) || empty($podmenu)) 
{

	$na_stronie_u=50;
	if (!$podmenu) { $podmenu=1; }
	$wszystkie_rekordy=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_GRACZE." where vliga='{$l_u}' && status='1';"));
	$wszystkie_strony = floor($wszystkie_rekordy/$na_stronie_u+1);  
	$start = ($podmenu-1) * $na_stronie_u;  
	$wyn=mysql_query("SELECT * FROM ".TABELA_GRACZE." where vliga='{$l_u}'  && status='1' order by 'ranking' DESC limit {$start},{$na_stronie_u};");
	naglowek_uzytkownicy();
		while ($rek=mysql_fetch_array($wyn)) 
		{
			print "<tr ".kolor($a++).">
			<td>".linkownik('profil',$rek['id_gracza'],'')."</tD>
			<td>".linkownik('gg',$rek['id_gracza'],'')."</a></tD>
			<td>".mini_logo($rek['id_gracza'])."</tD>
			<td>".sprawdz_zgode_na_host($rek['id'])."</tD>
			<td>".sprawdz_ranking_id($rek['id_gracza'],DEFINIOWANA_GRA)."</tD>
			<td width=\"40\">".linkownik('graj',$rek['id_gracza'],'')."</tD>
			</tr>";
		}
	end_table();
	$licz=mysql_num_rows(mysql_query("SELECT * FROM ".TABELA_GRACZE." WHERE vliga='{$l_u}' && status='1';"));
	if (empty($licz)) 
	{
		note(M325." <b>{$opis_gry[$l_u][0]}</b>".M326,"blad");
	} 
	else 
	{
		wyswietl_stronnicowanie($podmenu,$wszystkie_strony,'uzytkownicy-','.htm');
	}	
}
 
?>
