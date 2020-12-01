<!-- blok z ostanimi moimi meczami -->
<div class="naglowek_prawa">
    <div class="zaokraglenie_lewa"></div>
    <img src="img/strzalka.gif" alt="<?= M227 ?>"/>
    <h1>Tabela Ligowa</h1>
    <div class="_info"><a href="liga-tabela.htm">Tabele</a></div>
    <div class="_close"
         onclick="potwierdzenie('profil,edytuj-modul,wylacz-<?= $ID_MODUL ?>.htm','Czy jestes pewien, ze chcesz wylaczyc ten modul?');"></div>
    <div class="zaokraglenie_prawa"></div>
</div>
<div class="srodek_moje_mecze"><? mini_tabela_ligowa(sprawdz_miejsce_w_lidze(DEFINIOWANE_ID, R_ID_L)); ?></div>
<!-- blok z ostanimi moimi meczami- koniec -->
