<?php
include("config.php");
include("include/admin/functions/admin.config.php");


$action = $_POST['action'];
$id = (int)$_POST['id'];
$tb = czysta_zmienna_post($_POST['tb']);
$r = (int)$_POST['r'];


$zamien = array(
    cupPlayersKey => TABELA_PUCHAR_DNIA_GRACZE,
    leaguePlayersKey => TABELA_LIGA_GRACZE,
    torurnamentPlayersKey => TABELA_TURNIEJ_GRACZE
);

$sss = array(
    cupPlayersKey => mysql_query("DELETE FROM " . TABELA_PUCHAR_DNIA_GRACZE . " WHERE id_gracza='{$id}' && r_id = '{$r}'"),
    leaguePlayersKey => mysql_query("DELETE FROM " . TABELA_LIGA_GRACZE . " WHERE id_gracza='{$id}' && r_id = '{$r}'"),
    torurnamentPlayersKey => mysql_query("DELETE FROM " . TABELA_TURNIEJ_GRACZE . " WHERE id_gracza='{$id}' && r_id = '{$r}'")
);
if (!empty($_SESSION['szefuniu'])) {

    $sql = $sss[$tb];
}

?>