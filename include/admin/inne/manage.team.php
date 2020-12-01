<?
require_once('config.php');
require_once('include/functions/function.admin-druzyny.php');

switch ($podopcja) {
    case 1:
        if (!empty($_POST['zapisz'])) {
            if (!in_array('druzyny_edytuj', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                edytuj_druzyny_admin((int)$_POST['id'], (int)$_POST['punkty'], czysta_zmienna_post($_POST['nazwa']), "");
            }
        }
        if (!empty($_POST['view'])) {
            if (!in_array('druzyny_edytuj', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                edytuj_druzyny_admin((int)$_POST['id'], (int)$_POST['punkty'], czysta_zmienna_post($_POST['nazwa']), (isset($_POST['view']) ? ",view='{$_POST['new_view']}'" : ""));
            }
        }
        if (!empty($_POST['dodaj'])) {
            if (!in_array('druzyny_dodaj', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                dodaj_druzyne_admin(czysta_zmienna_post($_POST['nazwa_nowa_druzyna']), (int)$_POST['punkty_nowa_druzyna'], (int)$view, $_POST['logo']);
            }
        }
        if (!empty($_GET['usun_druzyne'])) {
            if (!in_array('druzyny_usun', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                usun_druzyne((int)$_GET['usun_druzyne']);
            }
        }
        dodaj_druzyne();
        wyswietl_druzyny_admin();
        break;
    case 2:
        if (!empty($_POST['kategoria'])) {
            if (!in_array('druzyny_kategorie_dodaj', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                dodaj_kategorie_glowna(czysta_zmienna_post($_POST['kategoria']));
            }
        }
        if (!empty($_POST['podkategoria'])) {
            if (!in_array('druzyny_podkategorie_dodaj', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                dodaj_podkategorie(czysta_zmienna_post($_POST['podkategoria']), (int)$_POST['kategorie_glowne']);
            }
        }
        if (!empty($_POST['p_usun'])) {
            if (!in_array('druzyny_podkategorie_usun', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                usun_podkategorie((int)$_POST['podkategoria_usun']);
            }
        }
        if (!empty($_POST['k_usun'])) {
            if (!in_array('druzyny_kategorie_usun', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                usun_kategorie((int)$_POST['usun_tylko_kategoria']);
            }
        }
        wyswietl_kategorie_admin();
        wyswietl_kategorie_usun();


        echo "<ul class=\"glowne_bloki\">
				<li class=\"glowne_bloki_naglowek legenda\"><span>Informacja</span></li>
				<li class=\"glowne_bloki_zawartosc\">
					<div class=\"description\">
						Pami�taj, Najpierw dodajesz kategorie o podanej nazwie, potem dodajesz do niej podkategorie 
						I najwaniejsze aby w utworzonej przez Ciebie podkategori znalaz�y sie druzyny, musisz przejsc do opcji
						<a href=\"administrator.php?opcja=28&podopcja=1\">Zarzadzaj Druzynami</a> i tam do utworzonej przez siebie 
						podkategori przypisac druzyny.
					</div>
				</li>
			</ul>";
        break;
}

?>
