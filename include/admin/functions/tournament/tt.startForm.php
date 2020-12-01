<?

function showTournamentStartForm($do_l, $config)
{
    require_once('include/admin/functions/tournament/tt.desTournament.php');

    for ($test = 1; $test <= @floor($do_l / $config['ilosc_koszy']); $test++) {
        $config['ilosc_kolejek'] = $test;
        $now = changeT($config, $do_l);
        if ($now[0] == false) {
            $createOptionSelect .= "<option " . ($config['ilosc_kolejek'] == $test ? "selected=\"selected\"" : null) . "value=\"{$test}\">{$test} kolejek";
        }
    }
    $allow = array(2, 4, 6, 8);
    foreach ($allow as $i) {
        $optNumBinTour .= "<option value=\"{$i}\">{$i} kosze/y";
    }


    for ($i = 10; $i <= 32; $i++) {
        $createNumPlayers .= ($i % 2 == 0 ? "<option value=\"{$i}\">{$i} graczy" : null);
    }
    return "<ul class=\"glowne_bloki\">
			<li class=\"glowne_bloki_naglowek legenda\"><span>Informacje - konfiguracja systemu turniejowego</span></li>
			<li class=\"glowne_bloki_zawartosc\">" . showDescriptionTournament($config, $do_l, $st['nazwa']) . "</li>			
		</ul>
		
		<ul class=\"glowne_bloki\">
			<li class=\"glowne_bloki_naglowek bot\">Witaj w systemie turnieju.</li>
			<li class=\"glowne_bloki_zawartosc\">
				
				<form acton=\"\" method=\"post\"  class=\"buka\">
					<fieldset class=\"adminAccessList leagueManager\"><legend>Wyglad turnieju</legend>
						<input type=\"radio\" value=\"1\" name=\"start_type\" class=\"IH\" " . ($config['start_type'] == 1 ? "checked=\"checked\"" : null) . "/> Eliminacje -&gt Faza Grupowa -&gt Puchar <br/>
						<input type=\"radio\" value=\"2\" name=\"start_type\" class=\"IH\" " . ($config['start_type'] == 2 ? "checked=\"checked\"" : null) . "/> Faza Grupowa -&gt Puchar<br/>
						<input type=\"radio\" value=\"3\" name=\"start_type\" class=\"IH\" " . ($config['start_type'] == 3 ? "checked=\"checked\"" : null) . "/> Eliminacje  -&gt Puchar <br/>
						
					</fieldset>
					" .
        ($config['start_type'] == 1 || $config['start_type'] == 3 ?
            "<fieldset class=\"adminAccessList leagueManager\"><legend>Robimy eliminacje na zasadzie</legend>
						<input type=\"radio\" value=\"1\" name=\"elimination_type\" " . ($config['elimination_type'] == 1 ? "checked=\"checked\"" : null) . "/> W grupie system ligowy (kazdy z kazdym)<br/>
						<input type=\"radio\" value=\"2\" name=\"elimination_type\" " . ($config['elimination_type'] == 2 ? "checked=\"checked\"" : null) . "/> Ograniczamy ilosc kolejek 
					</fieldset>
						<fieldset class=\"adminAccessList leagueManager\"><legend>Ilosc graczy awansujaca do fazy grupowej / ilosc z jaka startuje faza grupowa</legend>
						<input type=\"text\" name=\"ilosc_awans\" value=\"{$config['ilosc_awans']}\"/>/<select name=\"fg_num_players\">{$createNumPlayers}</select>
					</fieldset>
					" : null) . "
					
					" . (($config['start_type'] == 1 || $config['start_type'] == 3) && $config['elimination_type'] == 2 ?
            "<fieldset class=\"adminAccessList leagueManager\"><legend>Ilosc wygenerowanych kolejek</legend>
						<select name=\"ilosc_kolejek\">{$createOptionSelect}</select>
					</fieldset>
				
					" : null) . "
					
					" . ($config['start_type'] != 2 ? "
					<fieldset class=\"adminAccessList leagueManager\"><legend>Liczba koszy w eliminacjach</legend>
						<input type=\"text\" name=\"ilosc_koszy\" value=\"{$config['ilosc_koszy']}\"/>
					</fieldset>

					<fieldset class=\"adminAccessList leagueManager\"><legend>Zasada przydzielania graczy do koszy w wypadku niezgodnosci </legend>
						<input type=\"radio\" name=\"reszta\" value=\"1\" " . ($config['reszta'] == 1 ? "checked=\"checked\"" : null) . "/> Sa dodawani do koszy od poczatku<br/>
						<input type=\"radio\" name=\"reszta\" value=\"2\" " . ($config['reszta'] != 1 ? "checked=\"checked\"" : null) . "/> Trafiaja do losowych koszy
					</fieldset>" : null) . "
					
					
					" .
        ($config['start_type'] == 1 || $config['start_type'] == 2 ?
            "
						<fieldset class=\"adminAccessList leagueManager\"><legend>Liczba koszy w fazie grupowej</legend>
							<select name=\"kosze_fg\">{$optNumBinTour}</select>
						</fieldset>
					"
            : null) . "
					
						<fieldset class=\"adminAccessList leagueManager\"><legend>Liczba graczy do fazy pucharowej</legend>
							<select name=\"sys_cup\"><option value=\"16\">16 graczy od fazy 1/8
							<option value=\"8\">8 graczy od fazy 1/4</select>
						</fieldset>	
					
					
					
					<fieldset class=\"adminAccessList leagueManager\"><legend>Losowanie druzyn</legend>
						<input type=\"radio\" value=\"2\" name=\"team_rand\" " . ($config['team_rand'] == 2 ? "checked=\"checked\"" : null) . "/> Gracze graja wybranymi przez siebie druzynami <br/>
						" . ($config['start_type'] == 1 || $config['start_type'] == 3 ? "<input type=\"radio\" value=\"1\" name=\"team_rand\" " . ($config['team_rand'] == 1 ? "checked=\"checked\"" : null) . "/> Robimy losowanie druzyn w eliminacjach<br/>" : null) . "
						" . ($config['start_type'] == 1 || $config['start_type'] == 2 ? "<input type=\"radio\" value=\"3\" name=\"team_rand\" " . ($config['team_rand'] == 3 ? "checked=\"checked\"" : null) . "/> Robimy losowanie druzyn w fazie glownej<br/>" : null) . "
						" . ($config['start_type'] == 1 ? "<input type=\"radio\" value=\"4\" name=\"team_rand\" " . ($config['team_rand'] == 4 ? "checked=\"checked\"" : null) . "/> Robimy losowanie w eliminacjach i fazie glownej<br/>" : null) . "
					</fieldset>

						" . (($config['team_rand'] == 3 || $config['team_rand'] == 4) && !empty($config['team_rand']) ? "
							<fieldset class=\"adminAccessList leagueManager jquery_select_team\"><legend>Selekcja druzyn do turnieju <strong>dla fazy grupowej</strong></legend>
							" . createSelectWithTeams('klub', '') . "
							<a class=\"i-add-team-to-form\"></a>
							<input type=\"text\" name=\"druzyny_tur_fg\" value=\"{$config['druzyny_tur_fg']}\"/>
							</fieldset>
						" : null) . "
					
					
						" . (($config['team_rand'] == 1 || $config['team_rand'] == 4) && !empty($config['team_rand']) && $config['start_type'] != 2 ? "
							<fieldset class=\"adminAccessList leagueManager jquery_select_team2\"><legend>Selekcja druzyn do turnieju dla " . ($config['team_rand'] == 1 ? "eliminacji" : "fazy grupowej oraz eliminacji") . "</legend>
								" . createSelectWithTeams('klub', '') . "
								<a class=\"i-add-team-to-form\"></a>
								<input type=\"text\" name=\"druzyny_tur\" value=\"{$config['druzyny_tur']}\"/>
							</fieldset>
						" : null) . "
					<input type=\"hidden\" name=\"zapisz_ustawienie\" value=\"1\"/>
					<input type=\"image\" src=\"grafiki/admin_ikons/saveConfig.gif\"/>
				</form>
			
			</li>			
		</ul>";
}

?>