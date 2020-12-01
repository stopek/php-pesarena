<?
// deklaracja zmiennych 
$login = czysta_zmienna_post($_POST['login']);
$haslo = czysta_zmienna_post($_POST['haslo']);
$stanowisko = czysta_zmienna_post($_POST['stanowisko']);
$gg = (int)$_POST['gg'];
$mail = czysta_zmienna_post($_POST['mail']);
$osobie = czysta_zmienna_post($_POST['osobie']);
$druzyna = (int)$_POST['druzyna'];
$tytul = czysta_zmienna_post($_POST['tytul']);
$nick = (int)$_POST['nick'];

$dostep = czysta_zmienna_post($_POST['dostep']);
$edycja = (int)$_GET['edycja'];
$usun = (int)$_GET['usun'];
$coteraz = dodaj;
$popraw = czysta_zmienna_post($_POST['popraw']);
$dodaj = czysta_zmienna_post($_POST['dodaj']);
// deklaracja zmiennych - END


require_once('include/admin/functions/admin.boot.php');
require_once('include/admin/functions/admin.admin-add.php');

// pobranie wartosci w bazy do edycji
if (!empty($edycja) && empty($popraw)) {
    $rekord = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_ADMINI . " where id='{$edycja}';"));
    $poziom_edytowanego = explode('-', $rekord['access']);
    $coteraz = 'popraw';
} // pobranie wartosci w bazy do edycji - END

//edycja opiekuna
if (!empty($popraw)) {
    if (!in_array('opiekun_edytuj', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        wykonaj_popraw_admina($login, $haslo, $nick, $druzyna, $stanowisko, $gg, $mail, $osobie, $edycja);
    } ###
} //edycja opiekuna  - END


//dodawanie opiekuna
if (!empty($dodaj)) {
    if (!in_array('opiekun_dodaj', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        wykonaj_dodaj_admina($login, $haslo, $nick, $druzyna, $stanowisko, $gg, $mail, $osobie);
    } ###
} //dodawanie opiekuna - END


//usuwanie opiekuna
if (!empty($usun)) {
    if (!in_array('opiekun_usun', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        wykonaj_usun_admina($usun);
    }
} //usuwanie opiekuna - END


//formularz dodawania opiekunow
wyswietl_formularz_dodawania_edycji_admina($rekord, $coteraz, $wybory);
//formularz dodawania opiekunow - END


//wyswietlenie dostepnych opiekunow
if (!in_array('opiekun_patrz', explode(',', POZIOM_U_A))) {
    return 0;
} else { ###
    echo "<div class=\"jq_alert\"></div>
	<form method=\"post\" action=\"\" name=\"send\">";
    naglowek_edycja_opiekunow();
    $a2 = mysql_query("SELECT a.*,d.id as d_id,d.nazwa as d_nazwa FROM " . TABELA_ADMINI . " a LEFT JOIN druzyny d ON d.id=a.druzyna");
    define('PANE', TRUE);
    while ($as2 = mysql_fetch_array($a2)) {
        print "
		<tr" . kolor($a++) . "> 
			<td>{$a}</td>
			<td>{$as2['login']} (" . linkownik('profil', $as2['nick'], '') . ")</td>
			<td>" . mini_logo_druzyny($as2['d_id']) . "{$as2['d_nazwa']}</td>
			<td>{$as2['stanowisko']}</td>
			<td>{$as2['gg']}</td>
			<td>{$as2['mail']}</td>
			<td><a href=\"" . AKT . "&edycja={$as2['id']}\" class=\"i-edit\"></a></td>
			<td><a href=\"#\" title=\"" . adminList . "|{$as2['id']}|0\" class=\"i-delete jq\"></a></td>
			<td><input type=\"checkbox\" name=\"send_to[]\" value=\"{$as2['nick']}\"/></td>		
		</tr>";
    }
    end_table();
} ###
if (!in_array('bot_admini', explode(',', POZIOM_U_A))) {
    return 0;
} else { ###
    wyswietl_formularz_wysylania_gg();
}
echo "</form>";


//wyswietlenie dostepnych opiekunow - END
?>
