<div id="naglowek_prawa_poczatek">
    <div class="zaokraglenie_lewa"></div>
    <img src="img/strzalka.gif" alt="<?= M218 ?>"/>
    <h1><?= M218 ?></h1>
    <div class="_info"><a href="ranking.htm">Wszyscy</a></div>
    <div class="_close"
         onclick="potwierdzenie('profil,edytuj-modul,wylacz-<?= $ID_MODUL ?>.htm','Czy jestes pewien, ze chcesz wylaczyc ten modul?');"></div>
    <div class="zaokraglenie_prawa"></div>
</div>
<div class="jquery_menu">
    <ul class="yyy">
        <li><a href="#area-1"><span>Graczy</span></a></li>
        <li><a href="#area-2"><span>Bramek+</span></a></li>
        <li><a href="#area-3"><span>Bramek-</span></a></li>
        <li><a href="#area-4"><span>Meczy+</span></a></li>
    </ul>

    <div id="area-1" class="srodek_ranking"><? ranking_glowny($l_u, 'ranking'); ?></div>
    <div id="area-2" class="srodek_ranking"><? ranking_glowny($l_u, 'b_p'); ?></div>
    <div id="area-3" class="srodek_ranking"><? ranking_glowny($l_u, 'b_m'); ?></div>
    <div id="area-4" class="srodek_ranking"><? ranking_glowny($l_u, 'm_w'); ?></div>
</div>




