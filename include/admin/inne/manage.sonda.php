<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.sonda.php');
require_once('include/admin/funkcje/function.table.php');

if (!in_array('sonda_zarzadzanie', explode(',', POZIOM_U_A))) {
    return note(admsg_accessDenied, "blad");
} else { ###


    //pobranie danych do formularza
    $id = (int)$_GET['id'];
    if ($podmenu == 'edytuj') {
        $wynik = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_SONDA . " where id='{$id}';"));
        ankieta_formularz_edycja($wynik['pyt'], $wynik['odp1'], $wynik['odp2'], $wynik['odp3'], $wynik['odp4'], $wynik['odp5'], 'edytuj');
    } else {
        showForm('', '', '', '', '', '', 'dodaj');
    }

    //manager funkcji
    if (!empty($_POST['edytuj'])) {
        updateSonda($id);
    }
    if ($podmenu == 'glosowali') {
        showResult($id);
    }
    if ($podmenu == 'usun') {
        deleteSonda($id);
    }
    if (!empty($_POST['dodaj'])) {
        addSonda();
    }


    echo "<div class=\"jq_alert\"></div>";
    define('PANE', TRUE);


    $wynik = mysql_query("SELECT * FROM " . TABELA_SONDA . " order by id;");
    $b = 1;
    while ($r = mysql_fetch_array($wynik)) {
        $count = $r['glos1'] + $r['glos2'] + $r['glos3'] + $r['glos4'] + $r['glos5'];
        $arr[$b][] = $b++;
        $arr[$b][] = "{$r['pyt']}[{$count}]";
        $arr[$b][] = "{$r['odp1']}[{$r['glos1']}]";
        $arr[$b][] = "{$r['odp2']}[{$r['glos2']}]";
        $arr[$b][] = "" . (!empty($r['odp3']) ? "{$r['odp3']}[{$r['glos3']}]" : null) . "";
        $arr[$b][] = "" . (!empty($r['odp4']) ? "{$r['odp4']}[{$r['glos4']}]" : null) . "";
        $arr[$b][] = "" . (!empty($r['odp5']) ? "{$r['odp5']}[{$r['glos5']}]" : null) . "";
        $arr[$b][] = "<a href=\"" . AKT . "&podmenu=glosowali&id={$r['id']}\" class=\"i-show-sonda-results\"></a>";
        $arr[$b][] = "<a href=\"" . AKT . "&id={$r['id']}\" class=\"i-edit\"></a>";
        $arr[$b][] = "<a href=\"" . AKT . "&podmenu=usun&id={$r['id']}\" class=\"i-delete\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\"></a>";
    }


    $table = new createTable('adminFullTable');
    $table->setTableHead(array('id', admsg_pytanie, admsg_odpowiedz . ' 1', admsg_odpowiedz . ' 2', admsg_odpowiedz . ' 3', admsg_odpowiedz . ' 4', admsg_odpowiedz . ' 5', admsg_glosowali, admsg_edytuj, admsg_usun), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();

}###

?>
