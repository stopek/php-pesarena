<form method="post" action="">
	<input type="hidden" name="id" value="{$id}"/>
	<select name="type">
		<option value="0" {if not $status} selected {/if}>mo�liwo�� zmian - wy��czona
		<option value="1" {if $status} selected {/if}>mo�liwo�� zmian - w��czona
	</select>
	<input type="submit" name="goStatus" value="zmie�"/>
</form>