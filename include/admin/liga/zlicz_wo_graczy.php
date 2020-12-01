<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.liga.php');
$liga = czysta_zmienna_get($_GET['liga']);

if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('liga_inne_wo', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                $league = array('', 'a', 'b', 'c', 'd', 'e', '1', '2', '3', '4', '5');
                if (!empty($liga) && in_array($liga, $league)) {
                    liga_staty_gracza($liga, $r_id);
                } else {
                    echo "<ul class=\"glowne_bloki\">
					<li class=\"glowne_bloki_naglowek legenda\"><span>" . admsg_changeLeague . "</span></li>
					<li class=\"glowne_bloki_zawartosc centerT\">";
                    for ($temp = 1; $temp <= 10; $temp++) print "<a href=\"" . AKT . "&liga={$league[$temp]}\" title=\"\">[{$league[$temp]}]</a> | ";
                    echo "</li>
				</ul>";
                }
            } else {
                wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
            }
        }###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(12);
?>
