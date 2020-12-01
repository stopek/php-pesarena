<?

require_once('include/admin/functions/admin.player.php');
require_once('include/admin/functions/admin.points.php');
if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        //dodawanie dodatkowych punktow
        if (!empty($_POST['dodaj'])) {
            if (!in_array('pkt_dodaj', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                add_points($id_gracza, $pkt, $opis);
            } ###
        } else {
            dodatkowe_punkty_zarzadzaj('', 'dodaj');
        }

        //deklaruje zmienne ktore sa przesylane
        //z linku w funkcji :: wyswietl_wszystkie_dodatkowe_punkty();
        //pobieram dane do edycji
        //wrzucam je poprzez tablice $info do funkcji
        $edit = (int)$_GET['edit'];
        $delete = (int)$_GET['delete'];
        if (!empty($edit)) {
            $info = mysql_fetch_array(mysql_query("SELECT id_gracza, punkty, opis FROM " . TABELA_DODATKOWE_PUNKTY . " WHERE id='{$edit}'"));
            if (!empty($_POST['popraw'])) {
                if (!in_array('pkt_dodaj', explode(',', POZIOM_U_A))) {
                    return note("Brak dostepu!", "blad");
                } else { ###
                    edit_points((int)$_POST['punkty_dod'], czysta_zmienna_post($_POST['opis']), $info['id_gracza'], $edit);
                } ###
            } else {
                dodatkowe_punkty_zarzadzaj($info, 'popraw');
            }
        }

        if (!empty($delete)) {
            if (!in_array('pkt_usun', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                delete_points($delete);
            }
        } else {
            //jesli jest ta zmienna to wyswietlaja sie linki
            define('SHOW_LINKS', true);
            //funkcja w other.points.php
            wyswietl_wszystkie_dodatkowe_punkty();
        }
    } else {
        note("Najprawodopodobniej tutaj nie masz dostepu!!", "blad");
    }

}
wybor_gry_admin(22);
?>
