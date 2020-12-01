<?


//funkja przyjmue 2x id graczy oraz wyniki.
function sprawdz_lige_danego_gracza($id_1, $id_2, $wynik_1, $wynik_2)
{
    //tj tablica z punktacja dla graczy ligowych
    $punktacja = array(
        'extraklasa' => array('wygrana' => 700, 'przegrana' => -100, 'remis' => 200),
        'liga_1' => array('wygrana' => 400, 'przegrana' => -130, 'remis' => 140),
        'liga_2' => array('wygrana' => 350, 'przegrana' => -160, 'remis' => 110),
        'liga_3' => array('wygrana' => conf_pointsMatchLeague3Win, 'przegrana' => conf_pointsMatchLeague3Los, 'remis' => conf_pointsMatchLeague3NoW),

        'liga_A' => array('wygrana' => conf_pointsMatchLeagueGrWin, 'przegrana' => conf_pointsMatchLeagueGrLos, 'remis' => conf_pointsMatchLeagueGrNoW),
        'liga_B' => array('wygrana' => conf_pointsMatchLeagueGrWin, 'przegrana' => conf_pointsMatchLeagueGrLos, 'remis' => conf_pointsMatchLeagueGrNoW),
        'liga_C' => array('wygrana' => conf_pointsMatchLeagueGrWin, 'przegrana' => conf_pointsMatchLeagueGrLos, 'remis' => conf_pointsMatchLeagueGrNoW),
        'liga_D' => array('wygrana' => conf_pointsMatchLeagueGrWin, 'przegrana' => conf_pointsMatchLeagueGrLos, 'remis' => conf_pointsMatchLeagueGrNoW),
        'liga_E' => array('wygrana' => conf_pointsMatchLeagueGrWin, 'przegrana' => conf_pointsMatchLeagueGrLos, 'remis' => conf_pointsMatchLeagueGrNoW)
    );

    //tutaj zamieniam (pozniej pobiore z bazy info o statusie) status danego gracza na wlasciwa nazwe dla tablicu u gory
    $zamien = array('1' => 'extraklasa', '2' => 'liga_1', '3' => 'liga_2', '4' => 'liga_3', 'A' => 'liga_A', 'B' => 'liga_B', 'C' => 'liga_C', 'D' => 'liga_D', 'E' => 'liga_E');

    //tutaj sprawdzam statusy $dla graczy w zaleznosci od wynnikow
    //$dla wykorzystam do tablicy punktacja
    if ($wynik_1 > $wynik_2) {
        $dla_1 = 'wygrana';
        $dla_2 = 'przegrana';
    } elseif ($wynik_1 < $wynik_2) {
        $dla_1 = 'przegrana';
        $dla_2 = 'wygrana';
    } else {
        $dla_1 = 'remis';
        $dla_2 = 'remis';
    }

    //pobieram statusy poszszegolnych graczy
    $rek = mysql_fetch_array(mysql_query("SELECT status FROM " . TABELA_LIGA_GRACZE . " WHERE id_gracza='{$id_1}' && r_id='" . R_ID_L . "'"));
    $rek2 = mysql_fetch_array(mysql_query("SELECT status FROM " . TABELA_LIGA_GRACZE . " WHERE id_gracza='{$id_2}' && r_id='" . R_ID_L . "'"));


    //zwracam tablice z punktami potem poza funkcja wykorzystanie poprzez [0] oraz [1]
    return array($punktacja[$zamien[$rek['status']]][$dla_1], $punktacja[$zamien[$rek2['status']]][$dla_2]);
}


// aktualizuje tabele z statystykami graczy 
function aktualizacja_gracze($id, $gral1, $gral2, $wynik_1, $wynik_2, $s1, $s2, $gra, $gdzie)
{
    //jezeli w sesji znajdzie sie zmienna : wstecz :
    //to w zostaje zamieniony znak na -
    //sluzy do poprawy wyniku (przywraca staty z przed meczu i ustawia na nowo
    if (isset($_SESSION['wstecz'])) {
        $znak = "-";
    } else {
        $znak = "+";
    }


    // zapamietuje sytulacje z przed meczu
    znajdz_miejsca_i_pkt($gral1, $gral2);
    $pamiec_miejsca_1[0] = $GLOBALS['pamiec_miejsca_1'];
    $pamiec_miejsca_2[0] = $GLOBALS['pamiec_miejsca_2'];


    // aktualizacja  rankingu i zakonczenie meczu
    if (mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking={$GLOBALS['pamiec_punktow_1']}+'{$s1}' WHERE  id_gracza='{$gral1}' && vliga='{$gra}';")) {
        $confirm[1] = "Zmiana rankingu dla gracza1 - zmieniono!";
    } else {
        $confirm[1] = "Zmiana rankingu dla gracza1 - blad!";
    }

    if (mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking={$GLOBALS['pamiec_punktow_2']}+'{$s2}' WHERE  id_gracza='{$gral2}' && vliga='{$gra}';")) {
        $confirm[2] = "Zmiana rankingu dla gracza2 - zmieniono!";
    } else {
        $confirm[2] = "Zmiana rankingu dla gracza2 - blad!";
    }

    $wskaznik_gosp = mysql_fetch_array(mysql_query("SELECT seria_wskaznik,seria_ilosc FROM " . TABELA_GRACZE . " WHERE id_gracza='{$gral1}' && vliga='{$gra}'"));
    $wskaznik_gosc = mysql_fetch_array(mysql_query("SELECT seria_wskaznik,seria_ilosc FROM " . TABELA_GRACZE . " WHERE id_gracza='{$gral2}' && vliga='{$gra}'"));


    //zmiana wskaznika
    if ($wynik_1 > $wynik_2) {
        $p_znak_1 = "+";
        $p_znak_2 = "-";
        $ret = TRUE;
    } elseif ($wynik_1 < $wynik_2) {
        $p_znak_1 = "-";
        $p_znak_2 = "+";
        $ret = TRUE;
    }
    if (!empty($ret)) {
        if ($wskaznik_gosp['seria_wskaznik'] == $p_znak_1) {
            $sql = "UPDATE " . TABELA_GRACZE . " SET seria_ilosc=seria_ilosc+1  WHERE id_gracza='{$gral1}' && vliga='{$gra}'";
        } else {
            $sql = "UPDATE " . TABELA_GRACZE . " SET seria_wskaznik='{$p_znak_1}', seria_ilosc='1' WHERE id_gracza='{$gral1}' && vliga='{$gra}'";
        }
        if (mysql_query($sql)) {
            $confirm[9] = "Seria dla gospodarza zostala zmieniona!";
        }


        if ($wskaznik_gosc['seria_wskaznik'] == $p_znak_2) {
            $sql = "UPDATE " . TABELA_GRACZE . " SET seria_ilosc=seria_ilosc+1  WHERE id_gracza='{$gral2}' && vliga='{$gra}'";
        } else {
            $sql = "UPDATE " . TABELA_GRACZE . " SET seria_wskaznik='{$p_znak_2}', seria_ilosc='1' WHERE id_gracza='{$gral2}' && vliga='{$gra}'";
        }
        if (mysql_query($sql)) {
            $confirm[9] = "Seria dla goscia zostala zmieniona!";
        }
    }


    //,status='3'
    if (mysql_query("UPDATE {$gdzie} SET k_1='{$s1}',k_2='{$s2}',w1='{$wynik_1}',w2='{$wynik_2}',rozegrano=NOW(),status='3' WHERE  id='{$id}';")) {
        note("Spotkanie zakonczone! (<a href=\"javascript:rozwin('info_pozostale');\" title=\"\">Pokaz szczegoly</a>)", "info");


        // zapamietuje sytulacje po meczu ..
        znajdz_miejsca_i_pkt($gral1, $gral2);
        $pamiec_miejsca_1[1] = $GLOBALS['pamiec_miejsca_1'];
        $pamiec_miejsca_2[1] = $GLOBALS['pamiec_miejsca_2'];


        // dla gracza1
        //jesli przed meczem mialem lepsze miejsce
        if ($pamiec_miejsca_1[0] < $pamiec_miejsca_1[1]) {
            $wskaznik_1 = "-";
        } // jesli przed meczem mialem gorsze miejsce
        else if ($pamiec_miejsca_1[0] > $pamiec_miejsca_1[1]) {
            $wskaznik_1 = "+";
        } // jesli moja pozycja nie zmienila sie
        else {
            $wskaznik_1 = "0";
        }


        // dla gracza2
        //jesli przed meczem mialem lepsze miejsce
        if ($pamiec_miejsca_2[0] < $pamiec_miejsca_2[1]) {
            $wskaznik_2 = "-";
        } // jesli przed meczem mialem gorsze miejsce
        else if ($pamiec_miejsca_2[0] > $pamiec_miejsca_2[1]) {
            $wskaznik_2 = "+";
        } // jesli moja pozycja nie zmienila sie
        else {
            $wskaznik_2 = "0";
        }

        if (mysql_query("UPDATE " . TABELA_GRACZE . " SET pozycja='{$wskaznik_1}' WHERE  id_gracza='{$gral1}' && vliga='{$gra}';")) {
            $confirm[3] = "Wskaznik dla gracza1 - zmieniono!";
        } else {
            $confirm[3] = "Wskaznik dla gracza1 - blad!";
        }
        if (mysql_query("UPDATE " . TABELA_GRACZE . " SET pozycja='{$wskaznik_2}' WHERE  id_gracza='{$gral2}' && vliga='{$gra}';")) {
            $confirm[4] = "Wskaznik dla gracza2 - zmieniono!";
        } else {
            $confirm[4] = "Wskaznik dla gracza2 - blad!";
        }


        if (mysql_query("UPDATE " . TABELA_GRACZE . " SET b_m=b_m{$znak}{$wynik_2},b_p=b_p{$znak}{$wynik_1} WHERE  id_gracza='{$gral1}' && vliga='{$gra}';")) {
            $confirm[5] = "Statystyki bramek dla gracza1 - zmieniono!";
        } else {
            $confirm[5] = "Statystyki bramek dla gracza1 - blad!";
        }

        if (mysql_query("UPDATE " . TABELA_GRACZE . " SET b_p=b_p{$znak}{$wynik_2},b_m=b_m{$znak}{$wynik_1} WHERE  id_gracza='{$gral2}' && vliga='{$gra}';")) {
            $confirm[6] = "Statystyki bramek dla gracza2 - zmieniono!";
        } else {
            $confirm[6] = "Statystyki bramek dla gracza2 - zmieniono!";
        }


        if ($wynik_1 > $wynik_2) {
            $sq[1] = "m_w=m_w+1 ";
            $sq[2] = "m_p=m_p+1 ";
        }

        if ($wynik_1 < $wynik_2) {
            $sq[2] = "m_w=m_w+1 ";
            $sq[1] = "m_p=m_p+1 ";

        }
        if ($wynik_1 == $wynik_2) {
            $sq[1] = $sq[2] = "m_r=m_r+1";
        }


        if (mysql_query("UPDATE " . TABELA_GRACZE . " SET {$sq[1]} WHERE  id_gracza='{$gral1}' && vliga='{$gra}';")) {
            $confirm[7] = "Zmiana statystyk z rozegranych meczy dla gracza1 - zakonczono!";
        } else {
            $confirm[7] = "Zmiana statystyk z rozegranych meczy dla gracza1 - blad!";
        }
        if (mysql_query("UPDATE " . TABELA_GRACZE . " SET {$sq[2]} WHERE  id_gracza='{$gral2}' && vliga='{$gra}';")) {
            $confirm[8] = "Zmiana statystyk z rozegranych meczy dla gracza2 - zakonczono!";
        } else {
            $confirm[8] = "Zmiana statystyk z rozegranych meczy dla gracza1 - blad!";
        }
        $confirm[] = "-----dodatkowe informacje------";
        $confirm[] = "Byl to mecz o id: <b>{$id}</b>";
        $confirm[] = "Gospodarzem byl: <b>" . sprawdz_login_id($gral1) . "</b>";
        $confirm[] = "Gosciem byl: <b>" . sprawdz_login_id($gral2) . "</b>";
        $confirm[] = "Wynik dla gospodarza: <b>{$wynik_1}</b>";
        $confirm[] = "Wynik dla goscia: <b>{$wynik_2}</b>";
        $confirm[] = "Gospodarz otrzymuje <b>{$s1} pkt</b>";
        $confirm[] = "Gosc otrzymuje: <b>{$s2} pkt</b>";
        $confirm[] = "Gospodarz ma <b>{$pamiec_miejsca_1[0]}</b> miejsce";
        $confirm[] = "Gosc ma: <b>{$pamiec_miejsca_2[0]}</b> miejsce";
        $confirm[] = "Po meczu gospodarz ma <b>{$pamiec_miejsca_1[1]}</b> miejsce";
        $confirm[] = "Po meczu gosc ma: <b>{$pamiec_miejsca_2[1]}</b> miejsce";
        $confirm[] = "Spotkanie jest w: <b>{$gra}</b>";


        // jesli to puchar dnia to dodatkowo zmienia status gracza w pucharu na 0 - odpadl
        if ($gdzie == TABELA_PUCHAR_DNIA) {
            if ($GLOBALS['aktualne_spotkanie'] != 'ele') {
                if ($wynik_1 > $wynik_2) {
                    $next1 = 1;
                    $next2 = 0;
                }
                if ($wynik_1 < $wynik_2) {
                    $next2 = 1;
                    $next1 = 0;
                }
            } else {
                $next1 = 1;
                $next2 = 1;
            }
            $update1 = mysql_query("UPDATE " . TABELA_PUCHAR_DNIA_GRACZE . " SET status='{$next1}' WHERE  id_gracza='{$gral1}' && r_id='" . R_ID_P . "';");
            $update2 = mysql_query("UPDATE " . TABELA_PUCHAR_DNIA_GRACZE . " SET status='{$next2}' WHERE  id_gracza='{$gral2}' && r_id='" . R_ID_P . "';");
        }

        if ($gdzie == TABELA_TURNIEJ) {
            $dla_jakich = array('1_8', '1_4', '1_2', '1_1');
            if (in_array($GLOBALS['aktualne_spotkanie'], $dla_jakich)) {
                if ($wynik_1 > $wynik_2) {
                    $next1 = 1;
                    $next2 = 0;
                }
                if ($wynik_1 < $wynik_2) {
                    $next2 = 1;
                    $next1 = 0;
                }
                $update1 = mysql_query("UPDATE " . TABELA_TURNIEJ_GRACZE . " SET status='{$next1}' WHERE  id_gracza='{$gral1}' && r_id = '" . R_ID_T . "';");
                $update2 = mysql_query("UPDATE " . TABELA_TURNIEJ_GRACZE . " SET status='{$next2}' WHERE  id_gracza='{$gral2}' && r_id = '" . R_ID_T . "';");
                $confirm[] = " JEST TO SPOTKANIE TURNIEJOWE W FAZIE PUCHAROWEJ! Przegrany gracz odpada!";
            }
        }
        ////////////////////


        echo "<fieldset id=\"info_pozostale\" class=\"display_none\">";
        foreach ($confirm as $key => $text) {
            echo $key . ". " . $text . "<br/>";
        }
        echo "</fieldset>";


    } else {
        note("Wystapil blad podczas konczena spotkania!", "blad");
    }


    unset($_SESSION['wstecz']);
} // aktualizuje tabele z statystykami graczy  - END


// konczenie spotkania
function zakoncz_spotkanie($id, $id_gracza, $gdzie)
{
    $r = mysql_num_rows(mysql_query("SELECT * FROM {$gdzie} where id='{$id}' && n2='{$id_gracza}' && status='2';"));


    if ($r == '0' && !defined('ADMIN_EDIT')) {
        note(M40, "blad");

    } else {

        // wyswietlam szczegoly meczu
        szczegoly_meczu($id, $gdzie);


        znajdz_miejsca_i_pkt($GLOBALS['gral1'], $GLOBALS['gral2']);
        $glos_k_ = $_POST['glos_k_'];
        $zamien_gdzie_na = array(TABELA_LIGA => 'l', TABELA_PUCHAR_DNIA => 'p', TABELA_WYZWANIA => 'w', TABELA_TURNIEJ => 't');
        wystaw_komentarz($zamien_gdzie_na[$gdzie], $id, $id_gracza, $GLOBALS['gral2'], $glos_k_);


        // zmienne globalne deklaruje
        $pamiec_punktow_1 = $GLOBALS['pamiec_punktow_1'];
        $pamiec_punktow_2 = $GLOBALS['pamiec_punktow_2'];
        $pamiec_miejsca_1 = $GLOBALS['pamiec_miejsca_1'];
        $pamiec_miejsca_2 = $GLOBALS['pamiec_miejsca_2'];
        $wynik_1 = $GLOBALS['wynik_1'];
        $wynik_2 = $GLOBALS['wynik_2'];

        // sprawdza ile pkt bedzie za ten mecz
        $k_1 = licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, $wynik_1, $wynik_2, pkt1);
        $k_2 = licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, $wynik_1, $wynik_2, pkt2);

        // sprawdza ile pkt ma druzyna
        $klub_1_pkt = pkt_za_moja_druzyne($GLOBALS['klub_1']);
        $klub_2_pkt = pkt_za_moja_druzyne($GLOBALS['klub_2']);

        // sprawdza ile dany gracz ma pkt
        $plus_ma_1 = sprawdz_ranking_id($GLOBALS['gral1'], DEFINIOWANA_GRA);
        $plus_ma_2 = sprawdz_ranking_id($GLOBALS['gral2'], DEFINIOWANA_GRA);

        // jesli pusto to wyswietl 0 / bedzie blad jesli ma serio 0!
        if (empty($plus_ma_1)) {
            $plus_ma_1 = 1000;
        }
        if (empty($plus_ma_2)) {
            $plus_ma_2 = 1000;
        }

        // wyliczam bonus
        wylicz_bonus($GLOBALS['wynik_1'], $GLOBALS['wynik_2'], $klub_1_pkt, $klub_2_pkt);

        // sumuje pkt do wyniku z meczu
        if ($gdzie == TABELA_LIGA) {

            $tablica = sprawdz_lige_danego_gracza($GLOBALS['gral1'], $GLOBALS['gral2'], $GLOBALS['wynik_1'], $GLOBALS['wynik_2']);

            $s1 = $tablica[0];
            $s2 = $tablica[1];

            //	$s1 = licz_punkty('','','','',$wynik_1,$wynik_2,'pkt_l_1');
            // 	$s2 = licz_punkty('','','','',$wynik_1,$wynik_2,'pkt_l_2');


        } elseif ($gdzie == TABELA_TURNIEJ) {

            if ($_SESSION['sesja_turniej'] == 28) // turniej jerrego
            {
                $s1 = $s2 = 100;
            } else {
                $s1 = licz_punkty('', '', '', '', $wynik_1, $wynik_2, 'pkt_t_1');
                $s2 = licz_punkty('', '', '', '', $wynik_1, $wynik_2, 'pkt_t_2');
            }
        } else {
            $s1 = $GLOBALS['bonusik_1'] + $klub_1_pkt + $k_1;
            $s2 = $GLOBALS['bonusik_1'] + $klub_2_pkt + $k_2;
        }

        //poprawka obliczen
        //if ($wynik_1>$wynik_2 && $s2>0) { $s2 = -30; }
        //if ($wynik_1>$wynik_2 && $s1<0) { $s1 = 60; }


        //if ($wynik_1<$wynik_2 && $s2<0) { $s1 = 60; }
        //if ($wynik_1<$wynik_2 && $s1>0) { $s1 = -30; }

        //if ($wynik_1==$wynik_2 && $s2<0) { $s2 = 40; }
        //if ($wynik_1==$wynik_2 && $s1<0) { $s1 = 40; }


        // sumuje pkt do nowego_rankingu
        $suma1 = $s1 + $plus_ma_1;
        $suma2 = $s2 + $plus_ma_2;


        // do funkcji aktualizacji stat gracza (tabela: gracze)
        aktualizacja_gracze($id, $GLOBALS['gral1'], $GLOBALS['gral2'], $wynik_1, $wynik_2, floor($s1), floor($s2), DEFINIOWANA_GRA, $gdzie);


        //jesli to jest edycja spotkania przez admina
        //to nie dodaje punktow do rankingow dodatkowych - przerywam funkcje
        if (defined('ADMIN_EDIT')) return 0;


        //dodaje gracza do rankingow dnia
        aktualizacja_ranking_gracz($GLOBALS['gral1'], floor($s1), 1);
        aktualizacja_ranking_gracz($GLOBALS['gral2'], floor($s2), 1);

        //dodaje gracza do rankingow tygodnia
        aktualizacja_ranking_gracz($GLOBALS['gral1'], floor($s1), 2);
        aktualizacja_ranking_gracz($GLOBALS['gral2'], floor($s2), 2);

        //dodaje gracza do rankingow miesiaca
        aktualizacja_ranking_gracz($GLOBALS['gral1'], floor($s1), 3);
        aktualizacja_ranking_gracz($GLOBALS['gral2'], floor($s2), 3);

        aktualizacja_poker_ranking($GLOBALS['gral1'], floor($s1));
        aktualizacja_poker_ranking($GLOBALS['gral2'], floor($s2));


        /*
            note("Wg wyliczen gospodarz dostaje: {$k_1} pkt gosc dostaje: {$k_2} pkt  pkt za druzyne gospodarza: {$klub_1_pkt} pkt.
            pkt za druzyne goscia: {$klub_2_pkt} wyliczam bonus druzyny   dla gosp tj.{$GLOBALS['bonusik1']} dla goscia tj.{$GLOBALS['bonusik2']} ","fieldset");

        */


    }
} // konczenie spotkania - END


// odrzucanie spotkania - wyzwania
function odrzuc_spotkanie($id, $id_gracza)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . "  WHERE id='{$id}' && (n1='{$id_gracza}' || n2='{$id_gracza}') && status='p';"));
    if (!empty($id)) {
        if ($licz != '0') {
            if (!empty($id)) {
                if (mysql_query("DELETE FROM " . TABELA_WYZWANIA . " WHERE id='{$id}';")) {
                    note(M53, "info");
                } else {
                    note(M261, 'blad');
                }
            } else {
                if (empty($licz)) {
                    note(M43, "blad");
                }
                if (empty($id)) {
                    note(M36, "blad");
                }
            }
        }
    }
} // odrzucanie spotkania - wyzwania - END


// odrzucanie zlego wyniku 
function odrzuc_wynik($id, $id_gracza, $gdzie)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM {$gdzie}  WHERE id='{$id}' && n2='{$id_gracza}' && status='2';"));
    if ($licz != '0') {
        if (!empty($id)) {
            if (mysql_query("UPDATE {$gdzie} SET status='1',w1='',w2='' WHERE id='{$id}'  && status='2';")) {
                note(M44, "info");
            } else {
                note(M256, 'blad');
            }
        }
    } else {
        if (empty($licz)) {
            note(M38, "blad");
        }
        if (empty($id)) {
            note(M36, "blad");
        }
    }
} // odrzucanie zlego wyniku - END


// podawanie wyniku
function podaj_wynik($wyn_1, $wyn_2, $id_spotkania, $id_gracza, $gdzie)
{


    //ustawiam maxymalny wynik dla bramek
    $wyn_1 = ($wyn_1 > MAX_WYNIK ? MAX_WYNIK : $wyn_1);
    $wyn_2 = ($wyn_2 > MAX_WYNIK ? MAX_WYNIK : $wyn_2);

    //dla jakich meczy turniejowych nie moze byc remisu
    $dla_jakich = array('1_8', '1_4', '1_2', '1_1');
    szczegoly_meczu($id_spotkania, $gdzie);


    if (
        ($gdzie == TABELA_PUCHAR_DNIA && $GLOBALS['aktualne_spotkanie'] != 'ele' && ($wyn_1 == $wyn_2)) ||
        ($gdzie == TABELA_TURNIEJ && in_array($GLOBALS['aktualne_spotkanie'], $dla_jakich) && ($wyn_1 == $wyn_2))
    ) {
        return note(M257, "blad");
    }


    $czy_w = mysql_num_rows(mysql_query("SELECT * FROM {$gdzie} where  id='{$id_spotkania}' && n1='{$id_gracza}' && status='1';"));
    if ($czy_w == 1 && isset($wyn_1) && isset($wyn_2) && !empty($id_spotkania)) {
        if (mysql_query("UPDATE {$gdzie} SET w1='{$wyn_1}',w2='{$wyn_2}',status='2' WHERE id='{$id_spotkania}' && status='1' && n1='{$id_gracza}';")) {
            note(M31, "info");
        } else {
            note(M258, 'blad');
        }
    } else {
        if ($czy_w != 1) {
            $skladanka .= M38;
        }
        if (empty($id_spotkania)) {
            $skladanka .= "<bR>" . M36;
        }
        if (empty($wyn_1)) {
            $skladanka .= "<bR>" . M34;
        }
        if (empty($wyn_2)) {
            $skladanka .= "<bR>" . M35;
        }
        note(M39 . " <br> " . $skladanka, "blad");
    }
} // podawanie wyniku  - END

?>