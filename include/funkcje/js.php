<?
function js_podaj_wynik($gral1, $gral2, $druzyna_pkt_1, $druzyna_pkt_2)
{
    obliczenie_punktow($gral1, $gral2, ranking); //globalna oblicza pkt miejsca, nowy status pkt  i punkty z modyfikatora
    ?>
    <SCRIPT LANGUAGE="JavaScript">
        function pokaz(form) {
            //pobranie wartosci goli
            a = eval(form.przykladowy_wynik_1.value);
            b = eval(form.przykladowy_wynik_2.value);
            //zadeklarowanie zmiennej pkt za druzyne
            var pkt_z_druzyne_1 = parseFloat(<?=$druzyna_pkt_1?>);
            var pkt_z_druzyne_2 = parseFloat(<?=$druzyna_pkt_2?>);

            // wrzucenie wartosci pkt za druyzne do formularza
            form.pkt_z_druzyne_1.value = pkt_z_druzyne_1;
            form.pkt_z_druzyne_2.value = pkt_z_druzyne_2;

            // warunek ze gole moga byc 0
            if (a == "0") {
                form.przykladowy_wynik_1.value = '0';
            } else if (a) {
                form.przykladowy_wynik_1.value = a;
            }
            if (b == "0") {
                form.przykladowy_wynik_2.value = '0';
            } else if (b) {
                form.przykladowy_wynik_2.value = b;
            }


            // do roznicy miejsc
            if (pkt_z_druzyne_1 > pkt_z_druzyne_2) {
                var r_m = parseFloat(pkt_z_druzyne_1 - pkt_z_druzyne_2);
            } else {
                var r_m = parseFloat(pkt_z_druzyne_2 - pkt_z_druzyne_1);
            }

            // glowny przelicznik bonusu
            var glowny_bonus = zaokraglenie(10 + parseFloat(Math.sqrt(r_m)));


            // jesli wygrywa goscpoarz
            if (a > b) {
                // jesli goscpodarz ma gorszy zespol
                if (pkt_z_druzyne_1 > pkt_z_druzyne_2) {
                    var bonusik1 = glowny_bonus;
                    var bonusik2 = glowny_bonus / 4;
                }
                // jesli goscpodarz ma lepszy wynik
                else if (pkt_z_druzyne_1 < pkt_z_druzyne_2) {
                    var bonusik1 = glowny_bonus / 2;
                    var bonusik2 = glowny_bonus / 4;
                }
                // jesli maja takie same zespoly
                else {
                    var bonusik1 = 5;
                    var bonusik2 = 5;
                }

                // wypelnienie formularza
                form.bonus_1.value = parseFloat(zaokraglenie(bonusik1));
                form.bonus_2.value = parseFloat(zaokraglenie(bonusik2));
                form.pkt_za_mecz_1.value = zaokraglenie(<?print$GLOBALS['pkt_w_1'];?>);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print$GLOBALS['pkt_w_1'] + $GLOBALS['pamiec_punktow_1'] + $GLOBALS['pktzd_1'];?>+bonusik1 + pkt_z_druzyne_1);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print$GLOBALS['pkt_w_1'];?>/-2);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print$GLOBALS['pamiec_punktow_2'] - ($GLOBALS['pkt_w_1'] / 2) + $GLOBALS['pktzd_1'];?>+bonusik2 + pkt_z_druzyne_2);
            }
            // jesli wygrywa gosc
            else if (b > a) {
                // jesli gosc ma lepszy zespol
                if (pkt_z_druzyne_1 > pkt_z_druzyne_2) {
                    var bonusik1 = glowny_bonus / 4;
                    var bonusik2 = glowny_bonus / 2;
                }
                // jesli gosc ma gorszy zespol
                else if (pkt_z_druzyne_1 < pkt_z_druzyne_2) {
                    var bonusik1 = glowny_bonus / 4;
                    var bonusik2 = glowny_bonus;
                }
                // jesli maja takie same zepsoly
                else {
                    var bonusik1 = 5;
                    var bonusik2 = 5;
                }
                // wypelnienie formularza
                form.bonus_1.value = zaokraglenie(bonusik1);
                form.bonus_2.value = zaokraglenie(bonusik2);
                form.pkt_za_mecz_1.value = zaokraglenie(<?print$GLOBALS['pkt_w_2'];?>/-2);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print$GLOBALS['pamiec_punktow_1'] - ($GLOBALS['pkt_w_2'] / 2) + $GLOBALS['pktzd_1'];?>+bonusik1 + pkt_z_druzyne_1);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print$GLOBALS['pkt_w_2'];?>);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print$GLOBALS['pkt_w_2'] + $GLOBALS['pamiec_punktow_2'] + $GLOBALS['pktzd_1'];?>+bonusik2 + pkt_z_druzyne_2);
            }
            // jesli jest remis
            else if (a == b) {
                // jesli gosp ma gorszy zepol
                if (pkt_z_druzyne_1 > pkt_z_druzyne_2) {
                    var bonusik1 = parseFloat(glowny_bonus) / 4;
                    var bonusik2 = parseFloat(glowny_bonus) / 2;
                }
                // jelsi gosc ma lepsy zespol
                else if (pkt_z_druzyne_1 < pkt_z_druzyne_2) {
                    var bonusik1 = parseFloat(glowny_bonus) / 4;
                    var bonusik2 = parseFloat(glowny_bonus);
                }
                // jeli maja takie same zespoly
                else {
                    var bonusik1 = 5;
                    var bonusik2 = 5;
                }
                // wypelnienie formularza
                form.bonus_1.value = zaokraglenie(bonusik1);
                form.bonus_2.value = zaokraglenie(bonusik2);
                form.pkt_za_mecz_1.value = zaokraglenie(<?print$GLOBALS['pkt_r_1'];?>);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print$GLOBALS['pamiec_punktow_1'] + ($GLOBALS['pkt_r_1'])?>+bonusik1 + pkt_z_druzyne_1);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print$GLOBALS['pkt_r_2'];?>);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print$GLOBALS['pamiec_punktow_2'] + ($GLOBALS['pkt_r_2'])?>+bonusik2 + pkt_z_druzyne_2);
            }
        }
    </SCRIPT>
    <?
}


function js_podaj_wynik_turniej($gral1, $gral2)
{
    obliczenie_punktow($gral1, $gral2, ranking); //globalna oblicza pkt miejsca, nowy status pkt  i punkty z modyfikatora
    ?>
    <SCRIPT LANGUAGE="JavaScript">
        function pokaz(form) {
            //pobranie wartosci goli
            a = eval(form.przykladowy_wynik_1.value);
            b = eval(form.przykladowy_wynik_2.value);

            // warunek ze gole moga byc 0
            if (a == "0") {
                form.przykladowy_wynik_1.value = '0';
            } else if (a) {
                form.przykladowy_wynik_1.value = a;
            }
            if (b == "0") {
                form.przykladowy_wynik_2.value = '0';
            } else if (b) {
                form.przykladowy_wynik_2.value = b;
            }


            // jesli wygrywa goscpoarz
            if (a > b) {
                // wypelnienie formularza
                form.pkt_za_mecz_1.value = zaokraglenie(<?print PKT_TURNIEJ_WYGRANE;?>);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print (PKT_TURNIEJ_WYGRANE + $GLOBALS['pamiec_punktow_1']);?>);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print PKT_TURNIEJ_WYGRANE;?>/-2);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print ($GLOBALS['pamiec_punktow_2'] - PKT_TURNIEJ_WYGRANE / 2);?>);
            }
            // jesli wygrywa gosc
            else if (b > a) {
                // wypelnienie formularza
                form.pkt_za_mecz_1.value = zaokraglenie(<?print PKT_TURNIEJ_WYGRANE;?>/-2);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print $GLOBALS['pamiec_punktow_1'] - (PKT_TURNIEJ_WYGRANE / 2);?>);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print PKT_TURNIEJ_WYGRANE;?>);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print PKT_TURNIEJ_WYGRANE + $GLOBALS['pamiec_punktow_2'];?>);
            }
            // jesli jest remis
            else if (a == b) {
                // wypelnienie formularza
                form.pkt_za_mecz_1.value = zaokraglenie(<?print PKT_TURNIEJ_REMISU;?>);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print $GLOBALS['pamiec_punktow_1'] + (PKT_TURNIEJ_REMISU);?>);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print PKT_TURNIEJ_REMISU;?>);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print$GLOBALS['pamiec_punktow_2'] + (PKT_TURNIEJ_REMISU);?>);
            }
        }
    </SCRIPT>
    <?
}


function js_podaj_wynik_liga($gral1, $gral2)
{
    obliczenie_punktow($gral1, $gral2, ranking); //globalna oblicza pkt miejsca, nowy status pkt  i punkty z modyfikatora
    ?>
    <SCRIPT LANGUAGE="JavaScript">
        function pokaz(form) {
            //pobranie wartosci goli
            a = eval(form.przykladowy_wynik_1.value);
            b = eval(form.przykladowy_wynik_2.value);

            // warunek ze gole moga byc 0
            if (a == "0") {
                form.przykladowy_wynik_1.value = '0';
            } else if (a) {
                form.przykladowy_wynik_1.value = a;
            }
            if (b == "0") {
                form.przykladowy_wynik_2.value = '0';
            } else if (b) {
                form.przykladowy_wynik_2.value = b;
            }


            // jesli wygrywa goscpoarz
            if (a > b) {
                // wypelnienie formularza
                form.pkt_za_mecz_1.value = zaokraglenie(<?print PKT_LIGA_WYGRANE;?>);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print (PKT_LIGA_WYGRANE + $GLOBALS['pamiec_punktow_1']);?>);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print PKT_LIGA_WYGRANE;?>/-2);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print ($GLOBALS['pamiec_punktow_2'] - PKT_LIGA_WYGRANE / 2);?>);
            }
            // jesli wygrywa gosc
            else if (b > a) {
                // wypelnienie formularza
                form.pkt_za_mecz_1.value = zaokraglenie(<?print PKT_LIGA_WYGRANE;?>/-2);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print $GLOBALS['pamiec_punktow_1'] - (PKT_LIGA_WYGRANE / 2);?>);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print PKT_LIGA_WYGRANE;?>);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print PKT_LIGA_WYGRANE + $GLOBALS['pamiec_punktow_2'];?>);
            }
            // jesli jest remis
            else if (a == b) {
                // wypelnienie formularza
                form.pkt_za_mecz_1.value = zaokraglenie(<?print PKT_LIGA_REMISU;?>);
                form.nowy_status_pkt_1.value = zaokraglenie(<?print $GLOBALS['pamiec_punktow_1'] + (PKT_LIGA_REMISU);?>);
                form.pkt_za_mecz_2.value = zaokraglenie(<?print PKT_LIGA_REMISU;?>);
                form.nowy_status_pkt_2.value = zaokraglenie(<?print$GLOBALS['pamiec_punktow_2'] + (PKT_LIGA_REMISU);?>);
            }
        }
    </SCRIPT>
    <?
}

?>
