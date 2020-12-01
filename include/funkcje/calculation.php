<?

require_once('include/functions/calculation/calculation.points.php');
require_once('include/functions/calculation/calculation.bonus.php');
require_once('include/functions/calculation/calculation.check-errors.php');

function szczegoly_klubu($id_klubu)
{
    $nazwa = sprawdz_nazwe_klubu($id_klubu);
    global $druzyna_pkt, $druzyna_id, $druzyna_nazwa, $druzyna_sciezka;
    list($druzyna_id, $reszta) = split('[.]', $nazwa);
    list($nazwaa, $druzyna_pkt, $rozszezenie) = split('[-]', $reszta);
    $druzyna_nazwa = str_replace("_", " ", $nazwaa);
}


function znajdz_miejsca_i_pkt($gral1, $gral2)
{
    global $pamiec_miejsca_1, $pamiec_miejsca_2, $pamiec_punktow_1, $pamiec_punktow_2;
    $tablica = sortuj_uzytkownikow_z_pkt(DEFINIOWANA_GRA);
    $i = 0;
    while (list($key, $value) = each($tablica)) {
        $i++;
        if ($value['name'] == $gral1) {
            $pamiec_miejsca_1 = $i;
            $pamiec_punktow_1 = $value['num3'];
        }
        if ($value['name'] == $gral2) {
            $pamiec_miejsca_2 = $i;
            $pamiec_punktow_2 = $value['num3'];
        }
    }
    if (empty($pamiec_punktow_1)) {
        $pamiec_punktow_1 = 1000;
    }
    if (empty($pamiec_punktow_2)) {
        $pamiec_punktow_2 = 1000;
    }
}


function szczegoly_meczu($id, $typ)
{
    global $gral1, $gral2, $wynik_1, $wynik_2, $klub_1, $klub_2, $aktualne_spotkanie, $punkty_ma_1, $punkty_ma_2;
    $wr = mysql_query("SELECT * FROM {$typ} WHERE id='{$id}';");
    while ($wyn = mysql_fetch_array($wr)) {
        $gral1 = $wyn['n1'];
        $gral2 = $wyn['n2'];
        $wynik_1 = $wyn['w1'];
        $wynik_2 = $wyn['w2'];
        $klub_1 = $wyn['klub_1'];
        $klub_2 = $wyn['klub_2'];
        $aktualne_spotkanie = $wyn['spotkanie'];
        $punkty_ma_1 = $wyn['k_1'];
        $punkty_ma_2 = $wyn['k_2'];
    }
}


?>
