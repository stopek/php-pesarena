<?
require_once('include/admin/funkcje/function.table.php');
function showMatchListWoTournament($tablica)
{
    $a1 = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where vliga='" . WYBRANA_GRA . "' && status!='3' && r_id='" . R_ID . "';");
    $b = 1;
    while ($as2 = mysql_fetch_array($a1)) {
        $createOption = null;
        foreach ($tablica as $key => $value) {
            $createOption .= '<option value="' . $key . '">' . $value['gole'][0] . ' : ' . $value['gole'][1];
        }

        $arr[$b][] = $b++;
        $arr[$b][] = $as2['id'];
        $arr[$b][] = linkownik('profil', $as2['n1'], '');
        $arr[$b][] = mini_logo_druzyny($as2['klub_1']);
        $arr[$b][] = mini_logo_druzyny($as2['klub_2']);
        $arr[$b][] = linkownik('profil', $as2['n2'], '');
        $arr[$b][] = str_replace('_', '/', $as2[7]);
        $arr[$b][] = "
			<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"identyf\" value=\"{$as2[0]}\"/>
				<select name=\"zmienna\">{$createOption}</select>
				<input type=\"submit\" name=\"walkower\" value=\"" . admsg_wystaw . "\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\">
			</form>";
        $arr[$b][] = ($as2[5] == 2 ? "{$as2[3]} : {$as2[4]} <a href=\"" . AKT . "&zatwierdz_spotkanie={$as2['id']}\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\">" . admsg_zatwierdz . "</a>" : "
			<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"identyf\" value=\"{$as2[0]}\">
				<input type=\"text\" name=\"wynik_1_p\" size=\"1\" value=\"\"> : 
				<input type=\"text\" name=\"wynik_2_p\" size=\"1\" value=\"\">
				<input type=\"submit\" name=\"wpisz\" value=\"" . admsg_wystaw . "\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\">
			</form>
		");
        $arr[$b][] = $as2['kolejka'];
    }


    $table = new createTable('adminFullTable center');
    $table->setTableHead(array('', admsg_id, admsg_gospodarz, '', '', admsg_gosc, admsg_typ, admsg_wystaw_wo, admsg_aktualny . '/' . admsg_wpisz_wynik, admsg_kolejka), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();
}

?>