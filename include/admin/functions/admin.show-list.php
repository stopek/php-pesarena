<?


function form_type_wo($tablica)
{

    echo "
		<div class=\"smallField\">
			<div>
				<fieldset><legend>szukaj meczu(id)</legend>
					<form method=\"post\" action =\"\" class=\"smallInputs\">
					<ul>
						<li><input type=\"text\" name=\"searchIdMatch\"/></li>
						<li><input type=\"submit\" name=\"search\" value=\"szukaj\"/></li>
					</ul>
					</form>
				</fieldset>
			</div>
			<div>
				<fieldset><legend>szukaj ligi/grupy</legend>
					<form method=\"post\" action =\"\" class=\"smallInputs\">
					<ul>
						<li><input type=\"text\" name=\"searchLeagueMatch\"/></li>
						<li><input type=\"submit\" name=\"search\" value=\"szukaj\"/></li>
					</ul>
					</form>
				</fieldset>
			</div>

			<div>
				<fieldset><legend>typ grupowego WO</legend>
				---
				</fieldset>
			</div>
		</div>
	";


}


function showMatchListWoCup($tablica)
{
    $a1 = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " WHERE vliga='" . WYBRANA_GRA . "' && status!='3' && r_id='" . R_ID . "';");
    $b = 1;
    while ($as2 = mysql_fetch_array($a1)) {
        $createFormWo = "
			<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"identyf\" value=\"{$as2['id']}\"/>
				<select name=\"zmienna\">
					<option value=\"for1\">3 - 0
					<option value=\"for2\">0 - 3
				</select>
				<input type=\"submit\" name=\"walkower\" value=\"" . admsg_wystaw . "\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\"/>
			</form>";
        $createFormResult = "
			<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"identyf\" value=\"{$as2[0]}\"/>
				<input type=\"text\" name=\"wynik_1_p\" size=\"1\" value=\"\"/> : 
				<input type=\"text\" name=\"wynik_2_p\" size=\"1\" value=\"\"/>
				<input type=\"submit\" name=\"wpisz\" value=\"" . admsg_wpisz . "\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\"/>
			</form>";
        $createAcceptResultLink = "
			{$as2[3]} : {$as2[4]}
			<a href=\"" . AKT . "&zatwierdz_spotkanie={$as2[0]}\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\">" . admsg_zatwierdz . "</a>";
        $arr[$b][] = $b++;
        $arr[$b][] = $as2['id'];
        $arr[$b][] = linkownik('profil', $as2['n1'], '');
        $arr[$b][] = linkownik('profil', $as2['n2'], '');
        $arr[$b][] = str_replace('_', '/', $as2['spotkanie']);
        $arr[$b][] = $createFormWo;
        $arr[$b][] = ($as2['status'] == 1 ? $createFormResult : $createAcceptResultLink);
    }


    $table = new createTable('adminFullTable center');
    $table->setTableHead(array('', admsg_id, admsg_gospodarz, admsg_gosc, admsg_spotkanie, admsg_wystaw_wo, admsg_wpisz_wynik), 'adminHeadersClass');
    $table->setTableBody($arr);
    echo $table->getTable();


}


function showMatchListWoLeague($tablica)
{

    //wyszukiwarka
    $allowLeague = array('1', '2', '3', '4', '5', 'a', 'b', 'c', 'd', 'e');
    $search = $_POST['search'];
    $searchLeagueMatch = strtolower($_POST['searchLeagueMatch']);
    $searchIdMatch = (int)$_POST['searchIdMatch'];
    if (!empty($search) && (!empty($searchLeagueMatch) && in_array($searchLeagueMatch, $allowLeague))) {
        $addSql = " && nr_ligi='{$searchLeagueMatch}'";
    }
    if (!empty($search) && !empty($searchIdMatch)) {
        $addSql = " && id='{$searchIdMatch}' ";
    }
    if (!empty($search) && (empty($searchIdMatch) && (!in_array($searchLeagueMatch, $allowLeague)))) {
        note("Brak wynikow o podanych kryteriach", "blad");
    }


    $zapytanie = "SELECT * FROM " . TABELA_LIGA . " where vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' && status!='3' && (spotkanie='akt' || spotkanie='bar') {$addSql} ORDER BY `nr_ligi`,`kolejka`,`id` ASC;";
    $a1 = mysql_query($zapytanie);


    $b = 1;
    while ($as2 = mysql_fetch_array($a1)) {
        $crateOption = null;
        foreach ($tablica as $key => $value) {
            $crateOption .= '<option value="' . $key . '">' . $value['gole'][0] . ' : ' . $value['gole'][1];
        }

        $arr[$b][] = $b++;
        $arr[$b][] = $as2['id'];
        $arr[$b][] = linkownik('profil', $as2['n1'], '');
        $arr[$b][] = linkownik('profil', $as2['n2'], '');
        $arr[$b][] = str_replace('_', '/', $as2[7]);
        $arr[$b][] = "
			<form method=\"post\" action=\"\">
			<input type=\"hidden\" name=\"identyf\" value=\"{$as2[0]}\"/>
			<select name=\"zmienna\">{$crateOption}</select>
			<input type=\"submit\" name=\"walkower\" value=\"" . admsg_wystaw . "\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\">
			</form>
		";
        $arr[$b][] = "{$as2[3]} : {$as2[4]}";

        if ($as2[5] == '1') {
            $arr[$b][] = "
			<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"identyf\" value=\"{$as2[0]}\">
				<input type=\"text\" name=\"wynik_1_p\" size=\"1\" value=\"\"> : <input type=\"text\" name=\"wynik_2_p\" size=\"1\" value=\"\">
				<input type=\"submit\" name=\"wpisz\" value=\"" . admsg_wystaw . "\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\">
			</form>";
        }
        if ($as2[5] == 2)
            $arr[$b][] = "<a href=\"" . AKT . "&zatwierdz_spotkanie={$as2['id']}\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\">" . admsg_zatwierdz . "</a>";

        $arr[$b][] = $as2['kolejka'];
        $arr[$b][] = "<input type=\"checkbox\" name=\"wo_grup[]\" value=\"{$as2['id']}\"/>";

    }


    require_once('include/admin/funkcje/function.table.php');

    form_type_wo($tablica);

    $table = new createTable('adminFullTable center');
    $table->setTableHead(array('lp', 'id', 'gospodarz', 'gosc', 'liga', 'wystaw WO', 'aktualny', 'wpisz wynik', 'kolejka', 'grWO'), "adminHeadersClass");
    $table->setTableBody($arr);
    echo "
			<form method=\"post\" action=\"\"><fieldset><div class=smallFields><div>
				<ul>
					<li>
						<select name=\"wo_grup_type\">";
    foreach ($tablica as $key => $value) {
        echo '<option value="' . $key . '">' . $value['gole'][0] . ' : ' . $value['gole'][1];
    }
    echo "</select>
					</li>
					<li>
						<input type=\"submit\" name=\"wystaw_wo_grup\" value=\"wystaw\" 
						onclick=\"return confirm('Czy napewno chcesz wystawic ten wyp WO dla zaznaczonych spotkan?')\"/>
					</li>
				</ul></div></div></fieldset>{$table->getTable()}
			</form>
		
		";


}

?>
