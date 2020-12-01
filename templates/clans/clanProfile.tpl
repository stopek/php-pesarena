<div>
	<div class="headersPro">
			<div id="team-logo" class="opacity40" style="background: url('{$images.team}')  left -10px no-repeat;;">&nbsp;</div>
			<button class="info-button">nazwa klanu: {$data.nazwa}</button>
			<button class="important-button tips t1" title="kapitan klanu">{$data.kapitan}</button>
	</div>
	<div style="width: 100%; clear: both;">
		<div style="float:left; width: 62%;">
			{$tableWithPlayers}
		</div>
		<div style="float:right; width: 38%;  test-align: right;  text-align: right;">
			<img style="margin: 12px; 0 0 5px" src="{$images.avatar}" alt=""/>
		</div>
	</div>
	
	<div class="headersPro left">
		<button class="info-button">rozegrane mecze</button>
	</div>
	{$tableWithMatches}
	
	<div class="headersPro left">
		<button class="info-button">rozegrane pojedynki</button>
	</div>
	{$tableWithPartys}
</div>