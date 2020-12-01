<?
if (!defined('PEBLOCK') || !PEBLOCK) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
}
require_once('include/functions/function.zapisy.php');
require_once('include/functions/function.admin-druzyny.php');

$mozliwe_gdzie = array(
    'liga' => TABELA_LIGA_GRACZE,
    'liga_id' => R_ID_L,
    'liga_lista' => TABELA_LIGA_LISTA,

    'turniej' => TABELA_TURNIEJ_GRACZE,
    'turniej_id' => R_ID_T,
    'turniej_lista' => TABELA_TURNIEJ_LISTA,

    'puchar' => TABELA_PUCHAR_DNIA_GRACZE,
    'puchar_id' => R_ID_P,
    'puchar_lista' => TABELA_PUCHAR_DNIA_LISTA);

if ($wlaczona_gra['status'] == 1) {
    if (array_key_exists($podmenu, $mozliwe_gdzie)) {
        $status_g = mysql_fetch_array(mysql_query("SELECT status FROM {$mozliwe_gdzie[$podmenu.'_lista']} WHERE id='{$mozliwe_gdzie[$podmenu.'_id']}'"));
        if (empty($status_g['status'])) {
            zapisy($mozliwe_gdzie[$podmenu], $mozliwe_gdzie[$podmenu . '_lista'], $id_zalogowanego_usera, $mozliwe_gdzie[$podmenu . '_id']);
        } else {
            note(M438, "blad");
        }
    }
} else {
    note(M302, "blad");
}
?>

