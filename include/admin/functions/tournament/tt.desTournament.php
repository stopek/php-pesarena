<?

function showDescriptionTournament($config, $do_l, $tournamentName)
{

    $changeT = changeT($config, $do_l);

    $druzyny = explode(',', $config['druzyny_tur']);
    $druzyny_cup = explode(',', $config['druzyny_tur_cup']);
    $druzyny_fg = explode(',', $config['druzyny_tur_fg']);
    foreach ($druzyny as $id_d) {
        if (!empty($id_d)) {
            $nazwa = mysql_fetch_array(mysql_query("SELECT nazwa FROM " . TABELA_DRUZYNY . " WHERE id='{$id_d}'"));
            $foreachTeams .= $nazwa['nazwa'] . ", ";
        }
    }
    foreach ($druzyny_fg as $id_d) {
        if (!empty($id_d)) {
            $nazwa = mysql_fetch_array(mysql_query("SELECT nazwa FROM " . TABELA_DRUZYNY . " WHERE id='{$id_d}'"));
            $foreachTeamsFg .= $nazwa['nazwa'] . ", ";
        }
    }
    foreach ($druzyny_cup as $id_d) {
        if (!empty($id_d)) {
            $nazwa = mysql_fetch_array(mysql_query("SELECT nazwa FROM " . TABELA_DRUZYNY . " WHERE id='{$id_d}'"));
            $foreachTeamsCup .= $nazwa['nazwa'] . ", ";
        }
    }
    if ($config['start_type'] == 2) $config['ilosc_koszy'] = $config['kosze_fg'];
    $b = @($do_l % $config['ilosc_koszy']);
    return "<div class=\"leagueStatus\">Ustawienia ogolne turnieju: 
		<span>
			Do <em><strong>{$tournamentName}</strong></em> zapisalo sie <strong class=\"green\">{$do_l}</strong> graczy. 
			Wybralismy podzial na
				" . (empty($config['ilosc_koszy']) ?
            "<em class=\"red\">brak ustawienia ilosci koszy</em>"
            :
            "<strong  class=\"green\">{$config['ilosc_koszy']} koszy.</strong>") . "
			A wiec w koszu bedzie po <strong class=\"green\">" . @floor($do_l / $config['ilosc_koszy']) . "</strong> graczy. 
			Z czego powstanie tyle samo grup. " .
        ($b == 0 ?
            "<strong  class=\"green\">Liczba graczy jest w sam raz</strong>, dlatego w kazdym koszu bedzie po rowno graczy"
            :
            "Liczba graczy <strong class=\"green\">nie jest w sam raz</strong> dlatego
				<strong  class=\"green\">" . @floor($do_l % $config['ilosc_koszy']) . "</strong> graczy " .
            ($config['reszta'] == 1 ?
                "<strong>zostanie przypisana do koszy od poczatku</strong> (kosz 1, kosz 2 itd)"
                :
                "<strong>zostanie losowo przydzielona do koszy</strong>"
            )
        ) . "
			Turniej zaczniemy od: " .
        ($config['start_type'] == 1 ?
            "<strong>eliminacji po czym bedzie faza glowna i na koniec puchar</strong>.
					Eliminacje beda na zasadzie: " .
            ($config['elimination_type'] == 1 ?
                "systemu ligowego w grupie <strong>kazdy z kazdym</strong> wszystkie kolejki. "
                :
                "<strong>ograniczenia ilosci kolejek </strong>do " .
                (empty($config['ilosc_kolejek']) ?
                    "<em class=\"red\">brak ustawienia ilosci kolejek</em>."
                    :
                    "<strong>{$config['ilosc_kolejek']}</strong>."
                )
            ) .
            (!empty($config['kosze_fg']) && 32 % $config['kosze_fg'] == 0 ?
                "Liczba ustawionych koszy dla fazy glownej jest poprawna"
                :
                "<em class=\"red\">brak ustawienia ilosci koszy dla fazy glownej 
						lub liczba ustawionych koszy nie dzieli sie bez reszty przez 32</em>"
            )
            :
            (!empty($config['start_type']) ?
                ($config['start_type'] == 2 ?
                    "<strong>fazy glownej po czym bedzie puchar</strong>. "
                    :
                    "<strong>eliminacji po czym odrazu przejdziemy do pucharu</strong>. "
                )
                :
                "<em class=\"red\">brak ustawienia wygladu ogolnego turnieju</em>"
            )
        ) . "
				
			Jesli chodzi o losowanie druzyn wybrales aby:  " .
        (!empty($config['team_rand']) ?
            ($config['team_rand'] == 1 ?
                "<strong>losowanie druzyn </strong>sie odbylo ale <strong>tylko w eliminacjach</strong>. " .

                (empty($config['druzyny_tur']) ?
                    "<em class=\"red\">Nie ustawiles jeszcze druzyn</em> jakimi beda grac. "
                    :
                    (count($druzyny) < (floor($do_l / $config['ilosc_koszy'])) ?
                        "<em class=\"red\">Liczba ustawionych przez Ciebie druzyn jest za mala.</em>
								Ilosc druzyn musi byc taka jak ilosc graczy w koszu tutaj: " . floor($do_l / $config['ilosc_koszy']) . ". "
                        :
                        null
                    ) .
                    "<em>Do wyboru dales im druzyny takie jak: <strong>{$foreachTeams}</strong></em>. "
                )
                :
                ($config['team_rand'] == 3 ?
                    "<strong>losowanie druzyn </strong>sie odbylo ale <strong>tylko w fazie glownej</strong>. " .
                    (empty($config['druzyny_tur_fg']) ?
                        "<em class=\"red\">Nie ustawiles jeszcze druzyn</em> jakimi beda grac w fazie glownej. "
                        :
                        (count($druzyny_fg) == (8) ?
                            "<em class=\"red\">Liczba ustawionych przez Ciebie druzyn jest za mala, musi byc 8 druzyn dla fazy glownej.</em> "
                            :
                            null
                        ) .

                        "<em>Do wyboru dales druzyny takie jak: <strong>{$foreachTeamsFg}</strong></em>. "
                    )
                    :
                    ($config['team_rand'] == 4 ?
                        "<strong>losowanie druzyn </strong>sie odbylo <strong>w fazie glownej i eliminacjach. </strong> " .
                        (empty($config['druzyny_tur_fg']) ?
                            "<em class=\"red\">Nie ustawiles jeszcze druzyn</em> jakimi beda grac w fazie glownej. "
                            :
                            (count($druzyny_fg) != 8 ?
                                "<em class=\"red\">Liczba ustawionych przez Ciebie druzyn do fazy glownej jest niepoprawna. Musi byc 8 druzyn</em>. "
                                :
                                null
                            ) .
                            "<em>Do wyboru w fazie glownej dales druzyny takie jak: <strong>{$foreachTeamsFg}</strong></em>. "
                        )
                        . "
								" .
                        (empty($config['druzyny_tur']) ?
                            "<em class=\"red\">Nie ustawiles jeszcze druzyn</em> jakimi beda grac w eliminacjach. "
                            :
                            (count($druzyny) < (floor($do_l / $config['ilosc_koszy'])) ?
                                "<em class=\"red\">Liczba ustawionych przez Ciebie druzyn do eliminacji jest za mala. </em>
										Ilosc druzyn musi byc taka jak ilosc graczy w koszu tutaj: " . floor($do_l / $config['ilosc_koszy']) . ". "
                                :
                                null
                            ) .
                            "<em>Do wyboru dales druzyny takie jak: <strong>{$foreachTeams}</strong></em>. "
                        )


                        :
                        ($config['team_rand'] == 2 ?
                            "<strong> Gracze grali wybranymi przez siebie klubami. </strong> "
                            :
                            null
                        )
                    )
                )
            )
            :
            "<em class=\"red\">brak ustawiena dotyczacego losowania w eliminacjach i fazie glownej</em> "
        ) .
        "
		</span>
	</div>";
}

?>