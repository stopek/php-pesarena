<?
//deklaracja zmiennych
//$tablica - tablica z punktami za wo

require_once('include/admin/functions/admin.match.php');

$walkower = czysta_zmienna_post($_POST['walkower']);
$ide = (int)$_POST['identyf'];
$zmienna = czysta_zmienna_post($_POST['zmienna']);
$zatwierdz_spotkanie = (int)$_GET['zatwierdz_spotkanie'];
$wpisz = czysta_zmienna_post($_POST['wpisz']);
$wynik_1_p = (int)$_POST['wynik_1_p'];
$wynik_2_p = (int)$_POST['wynik_2_p'];
$tablica = array(
    'for1' => array('pkt' => array('100', '-200'), 'gole' => array('3', '0')),
    'for2' => array('pkt' => array('-200', '100'), 'gole' => array('0', '3'))
);

if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('puchar_wo', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            if (!empty($r_id)) {
                // wystawianie walkoweru
                if (!empty($walkower)) {
                    admin_wystaw_wo($ide, TABELA_PUCHAR_DNIA, $tablica, $zmienna);
                }
                // wystawianie walkoweru - END


                // wpisywanie wyniku gdy gosc nie chce !!
                if (!empty($wpisz)) {
                    wpisz_wynik_puchar_dnia($wynik_1_p, $wynik_2_p, $ide);
                }
                // wpisywanie wyniku gdy gosc nie chce !!


                // zatwierdzanie spotkan w ktorych jest juz wpisany wynik
                if (!empty($zatwierdz_spotkanie)) {
                    zatwierdz_spotkanie_puchar_dnia($zatwierdz_spotkanie);
                }
                // zatwierdzanie spotkan w ktorych jest juz wpisany wynik - END


                showInfoAboutWo($tablica);


                $a1 = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where vliga='{$wybrana_gra}' && status!='3' && r_id='" . R_ID . "';");
                $b = 1;
                while ($as2 = mysql_fetch_array($a1)) {
                    $createFormWo = "
						<form method=\"post\" action=\"\">
							<input type=\"hidden\" name=\"identyf\" value=\"{$as2['id']}\"/>
							<select name=\"zmienna\">
								<option value=\"for1\">3 - 0
								<option value=\"for2\">0 - 3
							</select>
							<input type=\"submit\" name=\"walkower\" value=\"wystaw\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\"/>
						</form>";
                    $createFormResult = "
						<form method=\"post\" action=\"\">
							<input type=\"hidden\" name=\"identyf\" value=\"{$as2[0]}\"/>
							<input type=\"text\" name=\"wynik_1_p\" size=\"1\" value=\"\"/> : 
							<input type=\"text\" name=\"wynik_2_p\" size=\"1\" value=\"\"/>
							<input type=\"submit\" name=\"wpisz\" value=\"wpisz\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\"/>
						</form>";
                    $createAcceptResultLink = "
						{$as2[3]} : {$as2[4]}
						<a href=\"" . AKT . "&zatwierdz_spotkanie={$as2[0]}\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\">zatwierdz</a>";
                    $arr[$b][] = $b++;
                    $arr[$b][] = $as2['id'];
                    $arr[$b][] = linkownik('profil', $as2['n1'], '');
                    $arr[$b][] = linkownik('profil', $as2['n2'], '');
                    $arr[$b][] = str_replace('_', '/', $as2['spotkanie']);
                    $arr[$b][] = $createFormWo;
                    $arr[$b][] = ($as2['status'] == 1 ? $createFormResult : $createAcceptResultLink);
                }

                require_once('include/admin/funkcje/function.table.php');
                $table = new createTable('adminFullTable center');
                $table->setTableHead(array('', 'id', 'gospodarz', 'gosc', 'spotkanie', 'wystaw wo', 'wpisz wynik'), 'adminHeadersClass');
                $table->setTableBody($arr);
                echo $table->getTable();

            } else {
                wyswietl_listy_rozgrywek(TABELA_PUCHAR_DNIA_LISTA, TABELA_PUCHAR_DNIA_GRACZE);
            }
        }###

    } else {
        note("Najprawodopodobniej tutaj nie masz dostepu!!", "blad");
    }
}
wybor_gry_admin(16);
?>
