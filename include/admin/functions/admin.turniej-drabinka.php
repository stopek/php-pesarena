<?
require_once('include/functions/function.turniej.php');


function zapisz_mecz_drabinki($m1, $g1, $m2, $g2, $kolejka, $faza)
{
    $p1 = drabinka_miejsca($g1, $m1);
    $p2 = drabinka_miejsca($g2, $m2);
    zapis_turniej($p1, $p2, $faza, R_ID, $kolejka);
}


function drabinka_miejsca($grupa, $miejsce)
{
    $a = wrzuc_graczy_turniejowych($grupa, 'tur');
    $sortuj = sortowanie_d_d_a();
    sortx($a, $sortuj);
    $u = 0;
    while (list($key, $value) = each($a)) {
        $u++;
        if ($u == $miejsce) {
            return $value['name'];
        }
    }
}


// informacja o nowej kolejce w pucharze poziom wyzej
function moja_nowa_kolejka_drabinka($stara)
{
    $w1 = $stara * 2;
    $w2 = $w1 - 1;
    $nowa = array($w1, $w2);
    return $nowa;
} // informacja o nowej kolejce w pucharze poziom wyzej - END


// generuje mecze pucharowe (w zaleznosci od kolejki poprzedniej)
function generuj_mecze_drabinki($kolejka, $faza, $r_id)
{
    switch ($faza) {
        case '1_8':
            $a_sprawdzam_w = "1_16";
            break;
        case '1_4':
            $a_sprawdzam_w = "1_8";
            break;
        case '1_2':
            $a_sprawdzam_w = "1_4";
            break;
        case '1_1':
            $a_sprawdzam_w = "1_2";
            break;
    }

    $t = array();
    $a = mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " where vliga='" . WYBRANA_GRA . "' &&  status='1' && r_id='{$r_id}';");
    while ($as = mysql_fetch_array($a)) {
        $o_kogo_biega = $as[1];
        $a2 = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where vliga='" . WYBRANA_GRA . "'  && spotkanie='{$a_sprawdzam_w}' &&  (n1='{$o_kogo_biega}' || n2='{$o_kogo_biega}') &&  r_id='{$r_id}';");
        while ($as2 = mysql_fetch_array($a2)) {
            $nalezal_do = $as2[6];
            $kto = moja_nowa_kolejka_drabinka($kolejka);
            foreach ($kto as $moze) {
                if ($moze == $nalezal_do) {
                    array_push($t, $o_kogo_biega);
                }
            }
        }
    }
    $p1 = $t[0];
    $p2 = $t[1];
    zapis_turniej($p1, $p2, $faza, $r_id, $kolejka);
    print "
	<table width=\"100%\" boder=\"1\" frame=\"void\">
		<tr class=\"naglowek\">
			<td>Gospodarz</td>
			<tD>Gosc</tD>
		</tr>
		<tr>
			<td  width=\"50%\">" . sprawdz_login_id($p1) . " </td>
			<td  width=\"50%\"> " . sprawdz_login_id($p2) . "</td>
		</tr>
	</table>";
} // generuje mecze pucharowe 


// przejscie do generowania meczy z sprawdzeniem
function wlaczanie_etapow_meczu_drabinki($etap, $do_ilu, $r_id)
{
    $jest_w = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " WHERE status='1' && vliga='" . WYBRANA_GRA . "' && r_id='{$r_id}';"));
    $czy_mecze_sa = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where spotkanie='{$etap}' && vliga='" . WYBRANA_GRA . "' && status!='3' && r_id='{$r_id}';"));
    $mabyc = $do_ilu * 2;
    if ($jest_w == $mabyc) {
        if ($czy_mecze_sa == '0') {
            for ($a = 1; $a <= $do_ilu; $a++) {
                echo generuj_mecze_drabinki($a, $etap, $r_id);
            }
        } else {
            note("W bazie sa jeszcze jakies nieskonczone mecze z fazy {$etap} w grze <B>" . WYBRANA_GRA . "</b>", "blad");
            przeladuj($_SESSION['stara']);
        }
    } else {
        note("Zla liczba userow({$jest_w}) do <b>{$etap}</b>. Byc moze nie zostaly jeszcze zakonczone fazy nizsze!", "blad");
        przeladuj($_SESSION['stara']);
    }
}

// przejscie do generowania meczy z sprawdzeniem - END
?>