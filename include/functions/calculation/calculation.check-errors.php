<?


function pkta($id_gracza)
{
    $b = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g = mysql_fetch_array($b)) {
        $a += $g['k_2'];
    }
    $b1 = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g1 = mysql_fetch_array($b1)) {
        $a += $g1['k_1'];
    }

    $b = mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($ga = mysql_fetch_array($b)) {
        $a += $ga['k_2'];
    }
    $b1 = mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g1b = mysql_fetch_array($b1)) {
        $a += $g1b['k_1'];
    }

    $b = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($ga = mysql_fetch_array($b)) {
        $a += $ga['k_2'];
    }
    $b1 = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g1b = mysql_fetch_array($b1)) {
        $a += $g1b['k_1'];
    }

    $b2 = mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g2 = mysql_fetch_array($b2)) {
        $a += $g2['k_2'];
    }
    $b3 = mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g3 = mysql_fetch_array($b3)) {
        $a += $g3['k_1'];
    }


    if (!empty($a)) {
        return "1000" + $a;
    }
}

function strzelone_bramkia($id_gracza)
{
    $b = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g = mysql_fetch_array($b)) {
        $a += $g['w2'];
    }
    $b1 = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g1 = mysql_fetch_array($b1)) {
        $a += $g1['w1'];
    }

    $b2 = mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g2 = mysql_fetch_array($b2)) {
        $a += $g2['w2'];
    }
    $b3 = mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g3 = mysql_fetch_array($b3)) {
        $a += $g3['w1'];
    }

    $b2 = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g2 = mysql_fetch_array($b2)) {
        $a += $g2['w2'];
    }
    $b3 = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g3 = mysql_fetch_array($b3)) {
        $a += $g3['w1'];
    }

    $b24 = mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g25 = mysql_fetch_array($b24)) {
        $a += $g25['w2'];
    }
    $b34 = mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g36 = mysql_fetch_array($b34)) {
        $a += $g36['w1'];
    }
    return $a;
}

function dodatkowe_punkty($id_gracza)
{
    $a = 0;
    $b = mysql_query("SELECT * FROM " . TABELA_DODATKOWE_PUNKTY . " where id_gracza='{$id_gracza}' && vliga='" . DEFINIOWANA_GRA . "'");
    while ($g = mysql_fetch_array($b)) {
        $a += $g['punkty'];
    }
    return $a;
}


function stracone_bramkia($id_gracza)
{
    $b = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g = mysql_fetch_array($b)) {
        $a += $g['w1'];
    }
    $b1 = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g1 = mysql_fetch_array($b1)) {
        $a += $g1['w2'];
    }

    $b3 = mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g = mysql_fetch_array($b)) {
        $a += $g3['w1'];
    }
    $b14 = mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g1 = mysql_fetch_array($b1)) {
        $a += $g14['w2'];
    }

    $b3 = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g = mysql_fetch_array($b)) {
        $a += $g3['w1'];
    }
    $b14 = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g1 = mysql_fetch_array($b1)) {
        $a += $g14['w2'];
    }

    $b2 = mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n2='{$id_gracza}' && status='3'");
    while ($g2 = mysql_fetch_array($b2)) {
        $a += $g2['w1'];
    }
    $b3 = mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && n1='{$id_gracza}' && status='3'");
    while ($g3 = mysql_fetch_array($b3)) {
        $a += $g3['w2'];
    }
    return $a;
}


function wygranea($id_gracza)
{
    $b = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`>`w1`) || (n1='{$id_gracza}' && `w2`<`w1`)) && status='3'"));
    $c = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`>`w1`) || (n1='{$id_gracza}' && `w2`<`w1`)) && status='3'"));
    $d = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`>`w1`) || (n1='{$id_gracza}' && `w2`<`w1`)) && status='3'"));
    $d = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`>`w1`) || (n1='{$id_gracza}' && `w2`<`w1`)) && status='3'"));

    return $b + $c + $d + $e;
}

function przegranea($id_gracza)
{
    $b = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`<`w1`) || (n1='{$id_gracza}' && `w2`>`w1`)) && status='3'"));
    $c = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`<`w1`) || (n1='{$id_gracza}' && `w2`>`w1`)) && status='3'"));
    $d = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`<`w1`) || (n1='{$id_gracza}' && `w2`>`w1`)) && status='3'"));
    $e = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`<`w1`) || (n1='{$id_gracza}' && `w2`>`w1`)) && status='3'"));

    return $b + $c + $d + $e;
}

function remisya($id_gracza)
{
    $b = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`=`w1`) || (n1='{$id_gracza}' && `w2`=`w1`)) && status='3'"));
    $c = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`=`w1`) || (n1='{$id_gracza}' && `w2`=`w1`)) && status='3'"));
    $d = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`<`w1`) || (n1='{$id_gracza}' && `w2`>`w1`)) && status='3'"));
    $d = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  vliga='" . DEFINIOWANA_GRA . "' && ((n2='{$id_gracza}' && `w2`=`w1`) || (n1='{$id_gracza}' && `w2`=`w1`)) && status='3'"));

    return $b + $c + $d + $e;
}


function wielka_poprawa_bledow_2($id_gracza, $gra)
{


    $wygral = wygranea($id_gracza);
    $przegral = przegranea($id_gracza);
    $remisy = remisya($id_gracza);
    $strzelil = strzelone_bramkia($id_gracza);
    $stracil = stracone_bramkia($id_gracza);
    $pkt = pkta($id_gracza);
    $dodatkowe = dodatkowe_punkty($id_gracza);
    $sumka = floor($pkt + $dodatkowe);


    if (mysql_query("UPDATE " . TABELA_GRACZE . " SET 
		ranking='" . $sumka . "',
		b_p='" . $strzelil . "',
		b_m='" . $stracil . "',
		m_w='" . $wygral . "',
		m_p='" . $przegral . "',
		m_r='" . $remisy . "' 
		where  id_gracza='{$id_gracza}' && vliga='{$gra}';")) {

        note(M259 . " 
		Gracz:  " . sprawdz_login_id($id_gracza) . " w gre: $gra ::  wygral: {$wygral}, 
		przegral: {$przegral}, 
		zremisowal: {$remisy}, 
		strzelil: {$strzelil}, 
		stracil: {$stracil}, 
		dodatkowe punkty: {$dodatkowe}, 
		ranking: {$pkt}, 
		ogolem: {$sumka}", 'fieldset');

    } else {
        note(M260, 'blad');
    }

}

?>
