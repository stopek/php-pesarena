<?


function statystyki()
{


    $w = mysql_fetch_array(mysql_query("SELECT 
		(SELECT count(id) FROM " . TABELA_WYZWANIA . " WHERE status!=3) ,
		(SELECT count(id) FROM " . TABELA_WYZWANIA . " WHERE status=3), count(id)
	FROM " . TABELA_WYZWANIA . ""));

    $p = mysql_fetch_array(mysql_query("SELECT 
		(SELECT count(id) FROM " . TABELA_PUCHAR_DNIA . " WHERE status!=3) ,
		(SELECT count(id) FROM " . TABELA_PUCHAR_DNIA . " WHERE status=3), count(id)
	FROM " . TABELA_PUCHAR_DNIA . ""));


    $t = mysql_fetch_array(mysql_query("SELECT 
		(SELECT count(id) FROM " . TABELA_TURNIEJ . " WHERE status!=3) ,
		(SELECT count(id) FROM " . TABELA_TURNIEJ . " WHERE status=3), count(id)
	FROM " . TABELA_TURNIEJ . ""));

    $l = mysql_fetch_array(mysql_query("SELECT 
		(SELECT count(id) FROM " . TABELA_LIGA . " WHERE status!=3) ,
		(SELECT count(id) FROM " . TABELA_LIGA . " WHERE status=3), count(id)
	FROM " . TABELA_LIGA . ""));


    $n = mysql_fetch_array(mysql_query("SELECT 
		(SELECT count(id) FROM " . TABELA_KOMENTARZE_NEWS . ") , count(id)
	FROM " . TABELA_NEWS . ""));

    $u = mysql_fetch_array(mysql_query("SELECT 
		count(id), 
		(SELECT count(id) FROM " . TABELA_UZYTKOWNICY . " WHERE status=1) ,
		(SELECT count(id) FROM " . TABELA_UZYTKOWNICY . " WHERE status=2), 
		(SELECT count(id) FROM " . TABELA_UZYTKOWNICY . " WHERE status=3)
	FROM " . TABELA_UZYTKOWNICY . ""));

    $rp = mysql_fetch_array(mysql_query("SELECT 
		count(id), 
		(SELECT count(id) FROM " . TABELA_LIGA_LISTA . " WHERE status=4) ,
		(SELECT count(id) FROM " . TABELA_TURNIEJ_LISTA . " WHERE status=4)
	FROM " . TABELA_PUCHAR_DNIA_LISTA . " WHERE status=4"));


    $o = mysql_fetch_array(mysql_query("SELECT 
		count(id), 
		(SELECT max(id) FROM " . TABELA_ONLINE . ")
	FROM " . TABELA_ONLINE . ""));


    $c = mysql_fetch_array(mysql_query("SELECT 
		count(id), 
		(SELECT count(id) FROM " . TABELA_ZGODA_HOST . " WHERE status='+'),
		(SELECT count(id) FROM " . TABELA_ZGODA_HOST . " WHERE status='-')
	FROM " . TABELA_ZGODA_HOST . ""));

    $s = mysql_fetch_array(mysql_query("SELECT 
		count(id)
	FROM " . TABELA_OSTRZEZENIA . ""));

    $max_o = mysql_fetch_array(mysql_query("SELECT 
		count(id_gracza) as maxx,id_gracza
	FROM " . TABELA_OSTRZEZENIA . " GROUP BY id_gracza ORDER BY maxx DESC"));


    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">Statystyki</li>
		<li class=\"glowne_bloki_zawartosc\">
			<ul class=\"statystyki\">
				<li>Wyzwan w bazie: <strong>{$w[2]}</strong></li>
				<li>Wyzwan rozegranych: <strong>{$w[1]}</strong></li>
				<li>Wyzwan w trakcie: <strong>{$w[0]}</strong></li>
				<li class=\"clear\"></li>
				<li>Pucharowych w bazie: <strong>{$p[2]}</strong></li>
				<li>Pucharowych rozegranych: <strong>{$p[1]}</strong></li>
				<li>Pucharowych w trakcie: <strong>{$p[0]}</strong></li>
				<li class=\"clear\"></li>
				<li>Turniejowych w bazie: <strong>{$t[2]}</strong></li>
				<li>Turniejowych rozegranych: <strong>{$t[1]}</strong></li>
				<li>Turniejowych w trakcie: <strong>{$t[0]}</strong></li>
				<li class=\"clear\"></li>
				<li>Ligowych w bazie: <strong>{$l[2]}</strong></li>
				<li>Ligowych rozegranych: <strong>{$l[1]}</strong></li>
				<li>Ligowych w trakcie: <strong>{$l[0]}</strong></li>
				<li class=\"clear\"></li>
				<li>Newsow w bazie: <strong>{$n[1]}</strong></li>
				<li>Oddanych komentarzy: <strong>{$n[0]}</strong></li>
				<li class=\"clear\"></li>
				<li>Userow w bazie: <strong>{$u[0]}</strong></li>
				<li>Aktywowanych: <strong>{$u[2]}</strong></li>
				<li>Nieaktywowanych: <strong>{$u[1]}</strong></li>
				<li>Zbanowanych: <strong>{$u[3]}</strong></li>
				<li class=\"clear\"></li>
				<li>Rozegranych imprez pucharowych: <strong>{$rp[0]}</strong></li>
				<li>Rozegranych imprez turniejowych: <strong>{$rp[2]}</strong></li>
				<li>Rozegranych imprez ligowych: <strong>{$rp[1]}</strong></li>	
				<li class=\"clear\"></li>
				<li>Lacznie odwiedzin: <strong>{$o[1]}</strong></li>
				<li>Online: <strong>{$o[0]}</strong></li>	
				<li class=\"clear\"></li>
				<li>Ocenionych hostow: <strong>{$c[0]}</strong></li>
				<li>Przyznanych + <strong>{$c[1]}</strong></li>
				<li>Przyznanych - <strong>{$c[2]}</strong></li>
				<li class=\"clear\"></li>
				<li>Wystawionych ostrzezen: <strong>{$s[0]}</strong>, najwiecej dostal: <strong>" . sprawdz_login_id($max_o[1]) . "</strong> lacznie: <strong>{$max_o[0]}</strong></li>
			</ul>
			
		
		
		
		
			

		
		
		</li>
	</ul>";
    /*
    <!-- stat.4u.pl NiE KaSoWaC -->
    <a target=_top href="http://stat.4u.pl/?pesarenastopek"><img alt="statystyka" src="http://adstat.4u.pl/s4u.gif" border="0"></a>
    <script language="JavaScript" type="text/javascript">
    <!--
    function s4upl() { return "&amp;r=er";}
    //-->
    </script>
    <script language="JavaScript" type="text/javascript" src="http://adstat.4u.pl/s.js?pesarenastopek"></script>
    <script language="JavaScript" type="text/javascript">
    <!--
    s4uext=s4upl();
    document.write('<img alt="statystyka" src="http://stat.4u.pl/cgi-bin/s.cgi?i=pesarenastopek'+s4uext+'" width="1" height="1">')
    //-->
    </script>
    <noscript><img alt="statystyki" src="http://stat.4u.pl/cgi-bin/s.cgi?i=pesarenastopek&amp;r=ns" width="1" height="1"></noscript>
    <!-- stat.4u.pl KoNiEc -->
    */

}

?>