<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.liga.php');
require_once('include/functions/function.create-block.php');
$liga = czysta_zmienna_get($_GET['liga']);
if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('liga_inne_tabele', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                $league = array('a', 'b', 'c', 'd', 'e', '1', '2', '3', '4', '5');
                foreach ($league as $liga) {
                    block_1(admsg_leagueTableForLG . ": {$liga}", pokaz_tabele_admin($liga), array('a' => '1', 'b' => '2'));
                }
            } else {
                wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
            }
        } ###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(12);
?>
