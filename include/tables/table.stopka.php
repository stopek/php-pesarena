<!-- blok z stopka -->
<div class="naglowek_prawa">
    <div class="zaokraglenie_lewa"></div>
    <img src="img/strzalka.gif" alt="<?= M233 ?>"/>
    <h1>Dodatkowe</h1>
    <div class="_info"><a href="dodatkowe,punkty.htm">Wszystkie</a></div>
    <div class="_close"
         onclick="potwierdzenie('profil,edytuj-modul,wylacz-<?= $ID_MODUL ?>.htm','Czy jestes pewien, ze chcesz wylaczyc ten modul?');"></div>
    <div class="zaokraglenie_prawa"></div>
</div>


<div class="jquery_menu">
    <ul class="yyy">
        <li><a href="#area-5"><span>Wszystkie</span></a></li>
        <li><a href="#area-6"><span>Ujemne</span></a></li>
        <li><a href="#area-7"><span>Dodatnie</span></a></li>
    </ul>
    <div id="area-5" class="srodek_ranking"><? wyswietl_dodatkowe_punkty(0); ?></div>
    <div id="area-6" class="srodek_ranking"><? wyswietl_dodatkowe_punkty('-'); ?></div>
    <div id="area-7" class="srodek_ranking"><? wyswietl_dodatkowe_punkty('+'); ?></div>
</div>


<!-- blok z stopka - koniec -->

