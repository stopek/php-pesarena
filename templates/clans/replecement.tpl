<div id="centerBox2">
	<form method="post" action="">
		<span class="padding-15">
		wykonujesz zamian� {if $reType eq 2} w obr�bie klanu {else} na gracza spoza klanu {/if} w klanie: <strong>{$clanName}</strong> <br/>
			
		usu�:
		<select name="old_player">
			{foreach from=$oldPlayer item=vars}
					<option value="{$vars.id_gracza}">{$vars.pseudo}</option>
			{/foreach}
		</select>
		
		dodaj:
		<select name="new_player">
			{if $reType eq 2}
				{foreach from=$oldPlayer item=vars}
					<option value="{$vars.id_gracza}">{$vars.pseudo}</option>
				{/foreach}
			{else}
				{foreach from=$allPlayer item=vars}
					<option value="{$vars.id}">{$vars.pseudo}</option>
				{/foreach}
			{/if}
		</select>
		<input type="submit" name="goReplecement" value="zamie� gracza"/>
		</span>
	</form>
	<ul class="n">
		<li>zamiana w obr�bie klanu powoduje zamiane starego gracza na nowego z przej�ciem wszystkich jego nierozegranych spotka�</li>
		<li>zamiana na gracza spoza klanu powoduje zamian� starego gracza na nowego z przej�ciem wszystkich jego spotkan(nawet rozegranych)</li>
	</ul>
</div>