<!-- blok z sonda -->
<div class="naglowek_prawa">
    <div class="zaokraglenie_lewa"></div>
    <img src="img/strzalka.gif" alt="<?= M223 ?>"/>
    <h1><?= M223 ?></h1>
    <div class="_close"
         onclick="potwierdzenie('profil,edytuj-modul,wylacz-<?= $ID_MODUL ?>.htm','Czy jestes pewien, ze chcesz wylaczyc ten modul?');"></div>
    <div class="zaokraglenie_prawa"></div>
</div>


<div class="jquery_menu">
    <ul class="yyy">
        <li><a href="#area-8"><span>Aktualna</span></a></li>
        <li><a href="#area-9"><span>Wyniki</span></a></li>
    </ul>
    <div id="area-8" class="srodek_sonda"><? sonda_oddaj_glos();
        sonda_wyswietl($odp_sonda_warianty); ?></div>
    <div id="area-9" class="srodek_sonda"><? sonda_wyniki($odp_sonda_warianty); ?></div>
</div>

<!-- blok z sonda - koniec -->