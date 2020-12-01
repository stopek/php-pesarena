	<span class="float-left white">
		<button class="info-button">kolejka: {$rec['kolejka']}</button>
		<a href="{$BASE_URL}profile/{$rec['gosp']}/"><button>{$gosp}</button></a>
		vs.
		<a href="{$BASE_URL}profile/{$rec['gosc']}/"><button>{$gosc}</button></a>
		<button class="info-button tips t1" title="ilo¶æ ukoñczonych meczy">{$rec['ilosc_rozegranych']}</button>
	</span>
	<span class="float-right">
		<a href="{$BASE_URL}table/"><button class="green-button">tabela klanowa</button></a>
	</span>