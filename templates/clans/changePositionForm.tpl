<form method="post" action="#rp{$id}">
	<input type="hidden" name="player_id" value="{$id_gracza}" />
	<input type="hidden" name="clan_id" value="{$id}" />
	<select name="position">
		<option value="">rezerwowy
		<option value="1" {if $pozycja == '1'} selected = "selected" {/if}>1
		<option value="2" {if $pozycja == '2'} selected = "selected" {/if}>2
		<option value="3" {if $pozycja == '3'} selected = "selected" {/if}>3
		<option value="4" {if $pozycja == '4'} selected = "selected" {/if}>4
	</select><input type="submit" name="goPosition" value="ustaw"/>
</form>