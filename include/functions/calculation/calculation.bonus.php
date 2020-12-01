<?


// wylicza pkt przjmujac za parametry pamieci pkt pamieci miejsc wyniki oraz $cos (co chcemy uzyskac przez ta funkcje)
function licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, $wynik_1, $wynik_2, $cos)
{
    $ttt = array(
        '31' => array(1 => 300, 2 => 150, 3 => -75),
        '32' => array(1 => 200, 2 => 100, 3 => -50),
        '30' => array(1 => 150, 2 => 100, 3 => -75),
        '43' => array(1 => 100, 2 => 100, 3 => 100),
        '45' => array(1 => 200, 2 => 100, 3 => -50),
        '44' => array(1 => 300, 2 => 150, 3 => -75),
        '46' => array(1 => 150, 2 => 50, 3 => -75),
        '47' => array(1 => 200, 2 => 75, 3 => -75),
        '54' => array(1 => 300, 2 => 150, 3 => -75),
        '55' => array(1 => 200, 2 => 100, 3 => -50),
        '57' => array(1 => 350, 2 => 150, 3 => -100),
        '58' => array(1 => 450, 2 => 200, 3 => -150),
        '59' => array(1 => 150, 2 => 50, 3 => -75),
        '64' => array(1 => 100, 2 => 100, 3 => 100),
        '66' => array(1 => 200, 2 => 75, 3 => -75),
        '68' => array(1 => 300, 2 => 150, 3 => -75),
        '69' => array(1 => 200, 2 => 100, 3 => -50),
        '70' => array(1 => 300, 2 => 150, 3 => -100),
        '72' => array(1 => 400, 2 => 100, 3 => -200),
        '73' => array(1 => 400, 2 => 100, 3 => -200),
        '74' => array(1 => 350, 2 => 175, 3 => -75)


    );


    //wyliczam roznicce miejsc
    //obliczam modyfikatory
    $roznica_miejsc = abs($pamiec_miejsca_1 - $pamiec_miejsca_2);
    $mod_dla_wygranej = sqrt($roznica_miejsc * 2) * 4;
    $mod_dla_remisu = sqrt($roznica_miejsc * 2) * 2;

    $dla_wygranego_o_gorszej_pozycji = PKT_WYGRANE + $mod_dla_wygranej;
    $dla_wygranego_o_lepszej_pozycji = PKT_WYGRANE - $mod_dla_wygranej;

    $dla_remisu_o_gorszej_pozycji = PKT_REMISU + $mod_dla_remisu;
    $dla_remisu_o_lepszej_pozycji = PKT_REMISU - $mod_dla_remisu;

    //jezeli wygrywa gospodarz
    if ($wynik_1 > $wynik_2) {
        //gospodarz jest w gorszej pozyji
        if ($pamiec_miejsca_1 > $pamiec_miejsca_2) {
            $rezultat_1 = $pamiec_punktow_1 + $dla_wygranego_o_gorszej_pozycji;
            $rezultat_2 = $pamiec_punktow_2 - $dla_wygranego_o_gorszej_pozycji / 2;
        }
        //gospodarz jest w lepszej pozyji
        if ($pamiec_miejsca_1 < $pamiec_miejsca_2) {
            $rezultat_1 = $pamiec_punktow_1 + $dla_wygranego_o_lepszej_pozycji;
            $rezultat_2 = $pamiec_punktow_2 - $dla_wygranego_o_lepszej_pozycji / 2;
        }


        $pkt_l_1 = PKT_LIGA_WYGRANE;
        $pkt_l_2 = PKT_LIGA_WYGRANE / -2;


        $pkt_t_1 = $ttt[$_SESSION['sesja_turniej']][1];
        $pkt_t_2 = $ttt[$_SESSION['sesja_turniej']][3];
    }


    //jezeli wygrywa gosc
    if ($wynik_1 < $wynik_2) {
        // gosc jest w lepszej pozycji
        if ($pamiec_miejsca_1 > $pamiec_miejsca_2) {
            $rezultat_1 = $pamiec_punktow_1 - $dla_wygranego_o_lepszej_pozycji / 2;
            $rezultat_2 = $pamiec_punktow_2 + $dla_wygranego_o_lepszej_pozycji;

        }
        //gosc jest w gorszej pozycji
        if ($pamiec_miejsca_1 < $pamiec_miejsca_2) {
            $rezultat_1 = $pamiec_punktow_1 - $dla_wygranego_o_gorszej_pozycji / 2;
            $rezultat_2 = $pamiec_punktow_2 + $dla_wygranego_o_gorszej_pozycji;
        }
        $pkt_l_2 = PKT_LIGA_WYGRANE;
        $pkt_l_1 = PKT_LIGA_WYGRANE / -2;

        $pkt_t_2 = $ttt[$_SESSION['sesja_turniej']][1];
        $pkt_t_1 = $ttt[$_SESSION['sesja_turniej']][3];


    }


    //jezeli remis
    if ($wynik_1 == $wynik_2) {
        //gospodarz jest w gorszej pozycji
        if ($pamiec_miejsca_1 > $pamiec_miejsca_2) {
            $rezultat_1 = $pamiec_punktow_1 + $dla_remisu_o_gorszej_pozycji;
            $rezultat_2 = $pamiec_punktow_2 + $dla_remisu_o_lepszej_pozycji;
        }
        //gospodarz jest w lepszej pozycji
        if ($pamiec_miejsca_1 < $pamiec_miejsca_2) {
            $rezultat_1 = $pamiec_punktow_1 + $dla_remisu_o_lepszej_pozycji;
            $rezultat_2 = $pamiec_punktow_2 + $dla_remisu_o_gorszej_pozycji;
        }
        $pkt_l_1 = $pkt_l_2 = PKT_LIGA_REMISU;
        $pkt_t_1 = $pkt_t_2 = $ttt[$_SESSION['sesja_turniej']][2];

    }


    $pkt1 = $rezultat_1 - $pamiec_punktow_1;
    $pkt2 = $rezultat_2 - $pamiec_punktow_2;

    /*
                note("kalkulacje: gospodarz ma pkt: $pamiec_punktow_1 , gosc ma pkt: $pamiec_punktow_2 , gospodarz ma miejsce: $pamiec_miejsca_1, gosc ma miejsce: $pamiec_miejsca_2
                roznica miejsc: $roznica_miejsc=abs($pamiec_miejsca_1-$pamiec_miejsca_2);<br>
                mod wygranego: $mod_dla_wygranej=sqrt($roznica_miejsc*2)*4;<br>
                mod remisu: $mod_dla_remisu=sqrt($roznica_miejsc*2)*2;<br>
                wygrany z gorsza pozycja: $dla_wygranego_o_gorszej_pozycji=".PKT_WYGRANE."+$mod_dla_wygranej;<br>
                wygrany z lepsza pozycja $dla_wygranego_o_lepszej_pozycji=".PKT_WYGRANE."-$mod_dla_wygranej;<br>
                remis z gorsza pozycja: $dla_remisu_o_gorszej_pozycji=".PKT_REMISU."+$mod_dla_remisu;<br>
                Remis z lepsza pozycja: $dla_remisu_o_lepszej_pozycji=".PKT_REMISU."-$mod_dla_remisu;<br>
                Rezultat koncowy all pkt1: $rezultat_1<br>
                Rezulata koncowy all pkt2: $rezultat_2<br>
                Pkt wg. wyliczen 1: $pkt1<Br>
                Pkt wg wyliczen 2: $pkt2<br>
                ","fieldset");
    */
    switch ($cos) {
        case 'ret1':
            return $rezultat_1;
            break;
        case 'ret2':
            return $rezultat_2;
            break;
        case 'dwogp':
            return $dla_wygranego_o_gorszej_pozycji;
            break;
        case 'dwolp':
            return $dla_wygranego_o_lepszej_pozycji;
            break;
        case 'drogp':
            return $dla_remisu_o_gorszej_pozycji;
            break;
        case 'drolp':
            return $dla_remisu_o_lepszej_pozycji;
            break;
        case 'pkt1':
            return $pkt1;
            break;
        case 'pkt2':
            return $pkt2;
            break;
        case 'pkt_l_1':
            return $pkt_l_1;
            break;
        case 'pkt_l_2':
            return $pkt_l_2;
            break;
        case 'pkt_t_1':
            return $pkt_t_1;
            break;
        case 'pkt_t_2':
            return $pkt_t_2;
            break;
    }
} // oblicza pkt - END


// oblicza bonus przyjmuje wyniki oraz pkt druzyn
function wylicz_bonus($wynik_1, $wynik_2, $klub_1_pkt, $klub_2_pkt)
{
    global $bonusik1, $bonusik2;

    $roznica_miejsc = abs($pamiec_miejsca_1 - $pamiec_miejsca_2);

    $glowny_bonus = round(10 + sqrt($roznica_miejsc), 0);
    if ($wynik_1 > $wynik_2) {
        if ($klub_1_pkt > $klub_2_pkt) {
            $bonusik1 = $glowny_bonus;
            $bonusik2 = $glowny_bonus / 4;
        } elseif ($klub_1_pkt < $klub_2_pkt) {
            $bonusik1 = $glowny_bonus / 2;
            $bonusik2 = $glowny_bonus / 4;
        } else {
            $bonusik1 = 5;
            $bonusik2 = 5;
        }
    } elseif ($wynik_2 > $wynik_1) {
        if ($klub_1_pkt > $klub_2_pkt) {
            $bonusik1 = $glowny_bonus / 4;
            $bonusik2 = $glowny_bonus / 2;
        } else if ($klub_1_pkt < $klub_2_pkt) {
            $bonusik1 = $glowny_bonus / 4;
            $bonusik2 = $glowny_bonus;
        } else {
            $bonusik1 = 5;
            $bonusik2 = 5;
        }
    } elseif ($wynik_1 == $wynik_2) {
        if ($klub_1_pkt > $klub_2_pkt) {
            $bonusik1 = ($glowny_bonus - 3) / 4;
            $bonusik2 = ($glowny_bonus - 3) / 2;
        } else if ($klub_1_pkt < $klub_2_pkt) {
            $bonusik1 = ($glowny_bonus - 3) / 4;
            $bonusik2 = ($glowny_bonus - 3);
        } else {
            $bonusik1 = 5;
            $bonusik2 = 5;
        }
    }
} // oblicza bonus - END
?>
