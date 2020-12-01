<?
// wyswietla tabelki z lista spotkan
function lista_spotkan()
{
    function karteczka($nazwa, $graczy, $status, $spotkan, $rozegrano, $co, $r_id)
    {
        $gra = DEFINIOWANA_GRA;
        echo "<div class=\"lista_div\">
			<div class=\"lista_rozgrywek_top\">{$nazwa}</div>
			<div class=\"lista_rozgrywek_tlo\">
				-" . M471 . ":<b>{$graczy}</b><br/>
				-" . M472 . ":<b>{$status}</b><br/>
				-" . M473 . ":<b>{$spotkan}/{$rozegrano}</b><br/>
				-" . M474 . ":{$gra}<br/>
				<i style=\"float:right\"><a style=\"color:white\" href=\"przelacz-{$co}-{$r_id}.htm\">" . M470 . "</a></i>
			</div>
		</div>";
    }

    $sql = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_LISTA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && status!='4'");
    while ($rek = mysql_fetch_array($sql)) {
        $rozegrano = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='{$rek['id']}' && status='3'"));
        karteczka($rek['nazwa'], policz_wszystko_r_id(TABELA_PUCHAR_DNIA_GRACZE, $rek['id']),
            zamien_status_rozgrywek($rek['status']), policz_wszystko_r_id(TABELA_PUCHAR_DNIA, $rek['id']), $rozegrano, 'puchar', $rek['id']);
        $jest = TRUE;
    }

    $sql = mysql_query("SELECT * FROM " . TABELA_TURNIEJ_LISTA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && status!='4'");
    while ($rek = mysql_fetch_array($sql)) {
        $rozegrano = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='{$rek['id']}' && status='3'"));
        karteczka($rek['nazwa'], policz_wszystko_r_id(TABELA_TURNIEJ_GRACZE, $rek['id']),
            zamien_status_rozgrywek($rek['status']), policz_wszystko_r_id(TABELA_TURNIEJ, $rek['id']), $rozegrano, 'turniej', $rek['id']);
        $jest = TRUE;
    }


    $sql2 = mysql_query("SELECT * FROM " . TABELA_LIGA_LISTA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && status!='4'");
    while ($rek2 = mysql_fetch_array($sql2)) {
        $rozegrano = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='{$rek2['id']}' && status='3'"));
        karteczka($rek2['nazwa'], policz_wszystko_r_id(TABELA_LIGA_GRACZE, $rek2['id']),
            zamien_status_rozgrywek($rek2['status']), policz_wszystko_r_id(TABELA_LIGA, $rek2['id']), $rozegrano, 'liga', $rek2['id']);
        $jest = TRUE;
    }
    if (empty($jest)) {
        note("Brak toczacych sie imprez!", "blad");
    }
} // wyswietla tabelki z lista spotkan - END
?>
