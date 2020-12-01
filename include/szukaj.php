<?
if (!defined('PEBLOCK') || !PEBLOCK) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
}


$zawodnika = czysta_zmienna_post($_POST['zawodnika']);
if (!empty($zawodnika)) {
    $nazwa_szukanego = czysta_zmienna_post($_POST['nazwa_szukanego']);
    if (!empty($nazwa_szukanego) && $nazwa_szukanego != '') {
        $m = mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " where pseudo LIKE '%{$nazwa_szukanego}%' && pseudo!='{$id_zalogowanego_usera}' && vliga='{$l_u}';");
        $ile = mysql_num_rows($m);
        if (!empty($ile) && $ile <= 50) {
            echo "<fieldset><legend>" . M15 . " <b>{$nazwa_szukanego}</b></legend>";
            naglowek_znalezionych_userow();
            while ($as1 = mysql_fetch_array($m)) {
                print "
				<tr" . kolor($a++) . ">
					<td>{$a}</td>
					<td>" . linkownik('profil', $as1['id'], '') . "</td>
					<td>" . linkownik('gg', $as1['id'], '') . "</td>
					<td>" . mini_logo($as1['id']) . "</td>
					<td>" . sprawdz_ranking_id($as1['id'], $l_u) . "</td>
					<td>" . linkownik('graj', $as1['id'], '') . "</td>
				</tr>";
            }
            end_table();
            echo "</fieldset>";
        } elseif ($ile > 50) {
            note(M16, "blad");
        } elseif (empty($ile)) {
            note(M17, "blad");
        }
    } else {
        note(M18, "blad");
    }
}


print "<fieldset><legend>" . M19 . " <b>{$opis_gry[$l_u][0]}/" . licz_userow_ligi($l_u) . "</b></legend>
<form method=\"post\" action=\"\"><input type=\"hidden\" name=\"zawodnika\" value=\"szukaj\"/>
<input type=\"text\" name=\"nazwa_szukanego\"/><br>" . M20 . "<br>
<input type=\"image\" src=\"img/szukaj.jpg\" alt=\"" . M343 . "\" title=\"" . M343 . "\"/><br>
</form></fieldset>";
?>
