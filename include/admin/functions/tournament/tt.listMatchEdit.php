<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/funkcje/function.table.php');
function showMatchesTournamentEdit()
{
    $a1 = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' && status='3' order by rozegrano DESC;");
    $b = 1;
    while ($as2 = mysql_fetch_array($a1)) {
        $temp = TRUE;
        $arr[$b][] = $b++;
        $arr[$b][] = $as2['id'];
        $arr[$b][] = $as2['rozegrano'];
        $arr[$b][] = linkownik('profil', $as2['n1'], '');
        $arr[$b][] = mini_logo_druzyny($as2['klub_1']);
        $arr[$b][] = mini_logo_druzyny($as2['klub_2']);
        $arr[$b][] = linkownik('profil', $as2['n2'], '');
        $arr[$b][] = "{$as2['w1']}:{$as2['w2']}";
        $arr[$b][] = "{$as2['k_1']}:{$as2['k_2']}";
        $arr[$b][] = status_meczu($as2['status']);
        $arr[$b][] = "<a href=\"" . AKT . "&edycja={$as2['id']}\" class=\"i-edit\"></a>";
        $arr[$b][] = (str_replace(array('ele', 'tur', 'dra'), array(matchTypeT1, matchTypeT2, matchTypeT3), $as2['spotkanie']));
    }

    $table = new createTable('adminFullTable center', admsg_tablewithTmatch);
    $table->setTableHead(array('', admsg_id, admsg_rozegrano, admsg_gospodarz, '', '', admsg_gosc, admsg_wynik, admsg_punkty, admsg_status, admsg_edytuj, admsg_typ), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();
}

?>