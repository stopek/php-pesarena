<?
//--------------------//
// komunikaty zebrane //
//--------------------//

require_once('include/admin/functions/admin.boot.php');
require_once('include/admin/functions/admin.admin-add.php');
require_once('include/admin/funkcje/function.table.php');
$login = czysta_zmienna_post($_POST['login']);
$haslo = czysta_zmienna_post($_POST['haslo']);
$stanowisko = czysta_zmienna_post($_POST['stanowisko']);
$gg = (int)$_POST['gg'];
$mail = czysta_zmienna_post($_POST['mail']);
$osobie = czysta_zmienna_post($_POST['osobie']);
$druzyna = (int)$_POST['druzyna'];
$nick = (int)$_POST['nick'];
$edycja = (int)$_GET['edycja'];
$usun = (int)$_GET['usun'];
$coteraz = 'dodaj';
$popraw = $_POST['popraw'];
$dodaj = $_POST['dodaj'];

// pobranie wartosci w bazy do edycji
if (!empty($edycja) && empty($popraw)) {
    $rekord = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_ADMINI . " where id='{$edycja}';"));
    $poziom_edytowanego = explode('-', $rekord['access']);
    $coteraz = 'popraw';
} // pobranie wartosci w bazy do edycji - END

//edycja opiekuna
if (!empty($popraw)) {
    if (!in_array('opiekun_edytuj', explode(',', POZIOM_U_A))) {
        return note(admsg_accessDenied, "blad");
    } else { ###
        updateAdmin($login, $haslo, $nick, $druzyna, $stanowisko, $gg, $mail, $osobie, $edycja);
    } ###
} //edycja opiekuna  - END

//dodawanie opiekuna
if (!empty($dodaj)) {
    if (!in_array('opiekun_dodaj', explode(',', POZIOM_U_A))) {
        return note(admsg_accessDenied, "blad");
    } else { ###
        addAdmin($login, $haslo, $nick, $druzyna, $stanowisko, $gg, $mail, $osobie);
    } ###
} //dodawanie opiekuna - END

//usuwanie opiekuna
if (!empty($usun)) {
    if (in_array('opiekun_usun', explode(',', POZIOM_U_A))) {
        deleteAdmin($usun);
    }
} //usuwanie opiekuna - END

//formularz dodawania opiekunow
showForm($rekord, $coteraz, $wybory);
//formularz dodawania opiekunow - END


echo "<form method=\"post\" action=\"\">";
if (in_array('bot_admini', explode(',', POZIOM_U_A))) {
    wyswietl_formularz_wysylania_gg();
}
if (in_array('opiekun_patrz', explode(',', POZIOM_U_A))) {
    showAdminList();
}
echo "</form>";
?>
