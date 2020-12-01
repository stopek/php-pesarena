<!-- blok z ostanimi moimi meczami -->
<div class="naglowek_prawa">
    <div class="zaokraglenie_lewa"></div>
    <img src="img/strzalka.gif" alt="<?= M227 ?>"/>
    <h1>Moje mecze</h1>
    <div class="_close"
         onclick="potwierdzenie('profil,edytuj-modul,wylacz-<?= $ID_MODUL ?>.htm','Czy jestes pewien, ze chcesz wylaczyc ten modul?');"></div>
    <div class="zaokraglenie_prawa"></div>
</div>

<div class="jquery_menu">
    <ul class="yyy">
        <li><a href="#area-12"><span>Wyzwania</span></a></li>
        <li><a href="#area-13"><span>Liga</span></a></li>
        <li><a href="#area-14"><span>Puchar</span></a></li>
        <li><a href="#area-15"><span>Turniej</span></a></li>
    </ul>
    <div id="area-12" class="srodek_moje_mecze"><? pokaz_mini_tabele(DEFINIOWANE_ID, TABELA_WYZWANIA); ?></div>
    <div id="area-13" class="srodek_moje_mecze"><? pokaz_mini_tabele(DEFINIOWANE_ID, TABELA_LIGA); ?></div>
    <div id="area-14" class="srodek_moje_mecze"><? pokaz_mini_tabele(DEFINIOWANE_ID, TABELA_PUCHAR_DNIA); ?></div>
    <div id="area-15" class="srodek_moje_mecze"><? pokaz_mini_tabele(DEFINIOWANE_ID, TABELA_TURNIEJ); ?></div>
</div>