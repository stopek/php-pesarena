function makeCheck(name) {
    var zmienna = document.getElementsByName('' + name + '');
    for (i = 0; i < zmienna.length; i++) {
        zmienna[i].checked = true
    }
}


function new_window(src, w, h) {
    window.open(src, 'nazwa', 'menubar=no,toolbar=no,location=no,directories=no,status=no,scrollbars=no,rezsable=no,fullscreen=no,channelmode=no,width=' + w + ',height=' + h + '').focus();
    return false;
}


function makeUncheck(name) {
    var zmienna = document.getElementsByName('' + name + '');
    for (i = 0; i < zmienna.length; i++) {
        zmienna[i].checked = false
    }
}


function movepic(img_name, img_src) {
    document[img_name].src = img_src;
}

function tagSmiley(tag, gdzie) {
    var chat_message = document.getElementById('' + gdzie + '');
    var cache = chat_message.value;
    this.tag = tag;
    chat_message.value = cache + tag;
}


function zaokraglenie(wartoscn1) {
    wartoscn1 = wartoscn1 * 100;
    var wynik1 = Math.round(wartoscn1) / 100;
    return wynik1;
}


function formatuj_date(a, b, c, d, e, f, id) {
    var sylw = new Date(a, b, c, d, e, f); // format daty: rok, miesi�c, dzie�, godzina, minuta, sekunda
    var teraz = new Date();
    var czas = teraz.getTime() - sylw.getTime();
    var dni = Math.floor(czas / (1000 * 60 * 60 * 24));
    if (dni > 0) {
        document.write("Od sylwestra 2007/2008 min�o " + dni + " dni.");
    } else {
        document.write("Ten sylwester dopiero si� odb�dzie!");
    }
}


oldvalue = "";

function passText(passedvalue) {
    if (passedvalue != "") {
        var totalvalue = passedvalue;
        document.displayform.obrazek.value = totalvalue;
        oldvalue = document.displayform.obrazek.value;
    }
}

function koloron(id) {
    document.getElementById(id).style.backgroundColor = "";
}


function kolorof(id) {
    document.getElementById(id).style.backgroundColor = "";
}


function kolorof2(id) {
    document.getElementById(id).style.backgroundColor = "";
}


function pokaz_info(id) {

    var tabs = new Array(
        'info-login',
        'info-pseudo',
        'info-mail',
        'info-haslo');
    for (i = 0; i < tabs.length; i++) {
        if (document.getElementById(tabs[i])) {
            document.getElementById(tabs[i]).style.display = 'none';
        }
    }
    document.getElementById(id).style.display = 'block';
    document.getElementById(id).style.background = '##d7d7d7';

}


// rozwijanie archiwum newsow
function rozwin(id_newsa) {
    state = document.getElementById(id_newsa).style.display;
    if (state == 'block') {
        document.getElementById(id_newsa).style.display = 'none';
    } else {
        document.getElementById(id_newsa).style.display = 'block';
    }
}

// wybor co na starcie ma byc zaznaczone
function start() {


    var per1 = document.getElementById('per1');
    if (per1 != null) {
        document.getElementById('per1').src = 'grafiki/zakladki/per_1_c.gif';
        document.getElementById('zakladka_1_per').style.display = 'block';
    }

    var r1 = document.getElementById('r1');
    if (r1 != null) {
        document.getElementById('r1').src = 'grafiki/zakladki/r_1_c.gif';
        document.getElementById('zakladka_1_r').style.display = 'block';
    }

    var lm1 = document.getElementById('lm1');
    if (lm1 != null) {
        document.getElementById('lm1').src = 'grafiki/zakladki/lm_1_c.gif';
        document.getElementById('zakladka_1_lm').style.display = 'block';
    }
    var reklama1 = document.getElementById('reklama1');
    if (reklama1 != null) {
        document.getElementById('reklama1').src = 'grafiki/zakladki/reklama_1_c.gif';
        document.getElementById('zakladka_1_reklama').style.display = 'block';
    }


    var mm1 = document.getElementById('mm1');
    if (mm1 != null) {
        document.getElementById('mm1').src = 'grafiki/zakladki/lm_1_c.gif';
        document.getElementById('zakladka_1_mm').style.display = 'block';
    }


    var st1 = document.getElementById('st1');
    if (st1 != null) {
        document.getElementById('st1').src = 'grafiki/zakladki/st_1_c.gif';
        document.getElementById('zakladka_1_st').style.display = 'block';
    }
    var sd1 = document.getElementById('sd1');
    if (sd1 != null) {
        document.getElementById('sd1').src = 'grafiki/zakladki/sd_1_c.gif';
        document.getElementById('zakladka_1_sd').style.display = 'block';
    }
    var online1 = document.getElementById('online1');
    if (online1 != null) {
        document.getElementById('online1').src = 'grafiki/zakladki/online_1_c.gif';
        document.getElementById('zakladka_1_online').style.display = 'block';
    }
    var rd1 = document.getElementById('rd1');
    if (rd1 != null) {
        document.getElementById('rd1').src = 'grafiki/zakladki/rd_1_c.gif';
        document.getElementById('zakladka_1_rd').style.display = 'block';
    }

    var wy1 = document.getElementById('wy1');
    if (wy1 != null) {
        document.getElementById('wy1').src = 'grafiki/zakladki/wy_1_c.gif';
        document.getElementById('zakladka_1_wy').style.display = 'block';
    }
    var lig1 = document.getElementById('lig1');
    if (lig1 != null) {
        document.getElementById('lig1').src = 'grafiki/zakladki/lig_1_c.gif';
        document.getElementById('zakladka_1_lig').style.display = 'block';
    }
    var pu1 = document.getElementById('pu1');
    if (pu1 != null) {
        document.getElementById('pu1').src = 'grafiki/zakladki/pu_1_c.gif';
        document.getElementById('zakladka_1_pu').style.display = 'block';
    }

}

// aktywacja poszczegolnych zakladek
function activateTab(tab, co) {
    if (co == 'r') {
        var tabs = new Array(
            'zakladka_1_r',
            'zakladka_2_r',
            'zakladka_3_r',
            'zakladka_4_r'
        );
        var r1 = document.getElementById('r1');
        if (r1 != null) {
            document.getElementById('r1').src = 'grafiki/zakladki/r_1_b.gif';
            document.getElementById('r2').src = 'grafiki/zakladki/r_2_b.gif';
            document.getElementById('r3').src = 'grafiki/zakladki/r_3_b.gif';
            document.getElementById('r4').src = 'grafiki/zakladki/r_4_b.gif';
        }
    } else if (co == 'online') {
        var tabs = new Array('zakladka_1_online', 'zakladka_2_online');
        var online1 = document.getElementById('online1');
        if (online1 != null) {
            document.getElementById('online1').src = 'grafiki/zakladki/online_1_b.gif';
            document.getElementById('online2').src = 'grafiki/zakladki/online_2_b.gif';
        }
    } else if (co == 'rd') {
        var tabs = new Array(
            'zakladka_1_rd',
            'zakladka_2_rd',
            'zakladka_3_rd',
            'zakladka_4_rd',
            'zakladka_5_rd',
            'zakladka_6_rd');
        var rd1 = document.getElementById('rd1');
        if (rd1 != null) {
            document.getElementById('rd1').src = 'grafiki/zakladki/rd_1_b.gif';
            document.getElementById('rd2').src = 'grafiki/zakladki/rd_2_b.gif';
            document.getElementById('rd3').src = 'grafiki/zakladki/rd_3_b.gif';
            document.getElementById('rd4').src = 'grafiki/zakladki/rd_4_b.gif';
            document.getElementById('rd5').src = 'grafiki/zakladki/rd_5_b.gif';
            document.getElementById('rd6').src = 'grafiki/zakladki/rd_6_b.gif';
        }
    } else if (co == 'lm') {
        var tabs = new Array(
            'zakladka_1_lm',
            'zakladka_2_lm',
            'zakladka_3_lm',
            'zakladka_4_lm');
        var lm1 = document.getElementById('lm1');
        if (lm1 != null) {
            document.getElementById('lm1').src = 'grafiki/zakladki/lm_1_b.gif';
            document.getElementById('lm2').src = 'grafiki/zakladki/lm_2_b.gif';
            document.getElementById('lm3').src = 'grafiki/zakladki/lm_3_b.gif';
            document.getElementById('lm4').src = 'grafiki/zakladki/lm_4_b.gif';
        }
    } else if (co == 'mm') {
        var tabs = new Array(
            'zakladka_1_mm',
            'zakladka_2_mm',
            'zakladka_3_mm',
            'zakladka_4_mm');
        var mm1 = document.getElementById('mm1');
        if (mm1 != null) {
            document.getElementById('mm1').src = 'grafiki/zakladki/mm_1_b.gif';
            document.getElementById('mm2').src = 'grafiki/zakladki/mm_2_b.gif';
            document.getElementById('mm3').src = 'grafiki/zakladki/mm_3_b.gif';
            document.getElementById('mm4').src = 'grafiki/zakladki/mm_4_b.gif';
        }
    } else if (co == 'wy') {
        var tabs = new Array(
            'zakladka_1_wy',
            'zakladka_2_wy',
            'zakladka_3_wy',
            'zakladka_4_wy',
            'zakladka_5_wy');
        var wy1 = document.getElementById('wy1');
        if (wy1 != null) {
            document.getElementById('wy1').src = 'grafiki/zakladki/wy_1_b.gif';
            document.getElementById('wy2').src = 'grafiki/zakladki/wy_2_b.gif';
            document.getElementById('wy3').src = 'grafiki/zakladki/wy_3_b.gif';
            document.getElementById('wy4').src = 'grafiki/zakladki/wy_4_b.gif';
            document.getElementById('wy5').src = 'grafiki/zakladki/wy_5_b.gif';
        }
    } else if (co == 'lig') {
        var tabs = new Array(
            'zakladka_1_lig',
            'zakladka_2_lig',
            'zakladka_3_lig');
        var lig1 = document.getElementById('lig1');
        if (lig1 != null) {
            document.getElementById('lig1').src = 'grafiki/zakladki/lig_1_b.gif';
            document.getElementById('lig2').src = 'grafiki/zakladki/lig_2_b.gif';
            document.getElementById('lig3').src = 'grafiki/zakladki/lig_3_b.gif';
        }
    } else if (co == 'per') {
        var tabs = new Array(
            'zakladka_1_per',
            'zakladka_2_per',
            'zakladka_3_per');
        var per1 = document.getElementById('per1');
        if (per1 != null) {
            document.getElementById('per1').src = 'grafiki/zakladki/per_1_b.gif';
            document.getElementById('per2').src = 'grafiki/zakladki/per_2_b.gif';
            document.getElementById('per3').src = 'grafiki/zakladki/per_3_b.gif';
        }
    } else if (co == 'st') {
        var tabs = new Array(
            'zakladka_1_st',
            'zakladka_2_st',
            'zakladka_3_st');
        var st1 = document.getElementById('st1');
        if (st1 != null) {
            document.getElementById('st1').src = 'grafiki/zakladki/st_1_b.gif';
            document.getElementById('st2').src = 'grafiki/zakladki/st_2_b.gif';
            document.getElementById('st3').src = 'grafiki/zakladki/st_3_b.gif';
        }
    } else if (co == 'sd') {
        var tabs = new Array(
            'zakladka_1_sd',
            'zakladka_2_sd',
            'zakladka_3_sd');
        var sd1 = document.getElementById('sd1');
        if (sd1 != null) {
            document.getElementById('sd1').src = 'grafiki/zakladki/sd_1_b.gif';
            document.getElementById('sd2').src = 'grafiki/zakladki/sd_2_b.gif';
            document.getElementById('sd3').src = 'grafiki/zakladki/sd_3_b.gif';
        }
    } else if (co == 'pu') {
        var tabs = new Array(
            'zakladka_1_pu',
            'zakladka_2_pu');
        var pu1 = document.getElementById('pu1');
        if (pu1 != null) {
            document.getElementById('pu1').src = 'grafiki/zakladki/pu_1_b.gif';
            document.getElementById('pu2').src = 'grafiki/zakladki/pu_2_b.gif';
        }
    } else if (co == 'reklama') {
        var tabs = new Array(
            'zakladka_1_reklama',
            'zakladka_2_reklama');
        var reklama1 = document.getElementById('reklama1');
        if (reklama1 != null) {
            document.getElementById('reklama1').src = 'grafiki/zakladki/reklama_1_b.gif';
            document.getElementById('reklama2').src = 'grafiki/zakladki/reklama_2_b.gif';
        }
    }

    var p = tab.split("_");
    var p1 = p[1];
    for (i = 0; i < tabs.length; i++) {
        if (document.getElementById(tabs[i])) {
            document.getElementById(tabs[i]).style.display = 'none';
        }
    }
    document.getElementById(tab).style.display = 'block';
    document.getElementById(co + '' + p1).src = 'grafiki/zakladki/' + co + '_' + p1 + '_c.gif';
}


// zmiana zakladek radio w meczach
function getElement(e, f) {
    if (document.layers) {
        f = (f) ? f : self;
        if (f.document.layers[e]) {
            return f.document.layers[e];
        }
        for (W = 0; i < f.document.layers.length; W++) {
            return (getElement(e, fdocument.layers[W]));
        }
    }
    if (document.all) {
        return document.all[e];
    }
    return document.getElementById(e);
}


function hide_them_all() {
    getElement("l1").style.display = 'none';
    getElement("l2").style.display = 'none';
    getElement("l3").style.display = 'none';
    getElement("l4").style.display = 'none';
    getElement("l5").style.display = 'none';
    getElement("l6").style.display = 'none';
    getElement("l7").style.display = 'none';
    getElement("l8").style.display = 'none';
    getElement("l9").style.display = 'none';
    getElement("l10").style.display = 'none';
}


function hide_them_all2() {
    getElement("w5").style.display = 'none';
    getElement("w6").style.display = 'none';
    getElement("w7").style.display = 'none';
}


function show_checked_option() {
    hide_them_all();
    if (getElement('opcja_l_1').checked) {
        getElement('l1').style.display = 'block';
    } else if (getElement('opcja_l_2').checked) {
        getElement('l2').style.display = 'block';
    } else if (getElement('opcja_l_3').checked) {
        getElement('l3').style.display = 'block';
    } else if (getElement('opcja_l_4').checked) {
        getElement('l4').style.display = 'block';
    } else if (getElement('opcja_l_5').checked) {
        getElement('l5').style.display = 'block';
    } else if (getElement('opcja_l_5').checked) {
        getElement('l6').style.display = 'block';
    } else if (getElement('opcja_l_5').checked) {
        getElement('l7').style.display = 'block';
    } else if (getElement('opcja_l_5').checked) {
        getElement('l8').style.display = 'block';
    } else if (getElement('opcja_l_5').checked) {
        getElement('l9').style.display = 'block';
    } else if (getElement('opcja_l_5').checked) {
        getElement('l10').style.display = 'block';
    }
}

function show_checked_option2() {
    hide_them_all2();
    if (getElement('opcja_w_5').checked) {
        getElement('w5').style.display = 'block';
    } else if (getElement('opcja_w_6').checked) {
        getElement('w6').style.display = 'block';
    } else if (getElement('opcja_w_7').checked) {
        getElement('w7').style.display = 'block';
    }
}