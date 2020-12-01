<?


require_once('include/functions/function.admin-druzyny.php');
require_once('include/functions/function.druzyny.php');


if ($podmenu == 'profil') {
    druzyny_wyswietl_profil((int)$podopcja);
} elseif ($podmenu == 'rozegrane,mecze') {
    wyswietl_wszystkie_mecze_druzyny((int)$podopcja);
} else {
    druzyny_wyswietl_liste();
}

?>
