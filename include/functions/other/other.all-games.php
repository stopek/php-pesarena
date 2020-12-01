<?
// wyswietla wszystkie wersje gier z opcja przelacz
function wyswietl_wersje_gier()
{
    print "<div id=\"profil_tlo\">";
    $sql = mysql_query("SELECT * FROM " . TABELA_GAME);
    while ($rek = mysql_fetch_array($sql)) {
        $value = $rek['skrot'];
        $w = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where status='3'  && vliga='{$value}';"));
        $l = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where status='3'  && vliga='{$value}';"));
        $p = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where status='3'  && vliga='{$value}';"));
        $t = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where status='3'  && vliga='{$value}';"));

        $wt = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where status!='3'  && vliga='{$value}';"));
        $lt = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where status!='3'  && vliga='{$value}';"));
        $pt = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where status!='3'  && vliga='{$value}';"));
        $tt = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where status!='3'  && vliga='{$value}';"));


        $s = $w + $l + $p + $t;
        $st = $wt + $lt + $pt + $tt;
        print "<div class=\"regal_prof\">
			<div class=\"tlo_profil_gra\"><br/>
				<img src=\"";
        if (file_exists($rek['logo'])) {
            print $rek['logo'];
        } else {
            print "grafiki/game/none.jpg";
        }
        print "\" alt=\"" . M316 . "\"/><br/>
				<a href=\"gra-" . $value . ".htm\"  title=\"" . M315 . " {$rek['nazwa']}\">
				<img src=\"img/przelacz_mnie.jpg\" alt=\"" . M315 . " {$rek['nazwa']}\"  title=\"" . M315 . " {$rek['nazwa']}\"/></a>
			</div>
			<div class=\"szczegoly_profilu\">
				<ul>
					<li><b>{$rek['nazwa']}</b></li>
					<li>" . M317 . " <b>{$w}/{$wt}</b></li>
					<li>" . M318 . " <b>{$l}/{$lt}</b></li>
					<li>" . M319 . " <b>{$p}/{$pt}</b></li>
					<li>" . M562 . ": <b>{$t}/{$tt}</b></li>
					<li>" . M320 . " <b>{$s}/{$st}</b></li>
					<li>" . M321 . " <b>" . mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_GRACZE . " where status='1'  && vliga='{$value}';")) . "/
					" . mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_GRACZE . " where status='2'  && vliga='{$value}';")) . "</b></li>
				<ul>
			</div>
		</div>";
    }
    print "</div>";
} // wyswietla wszystkie wersje gier z opcja przelacz - END
?>
