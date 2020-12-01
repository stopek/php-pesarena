<span>
	<a href="{$BASE_URL}setCaptain/{$id}/{$id_gracza}/" {if not $stanowisko} class="opacity40" {/if}><button>kapitan</button></a>
	<button {if $stanowisko} class="opacity40" {/if}>gracz</button>
</span>	
<span class="float-right">
	<a href="{$BASE_URL}deletePlayer/{$id_gracza}/{$id}/"><button>usuñ</button><a/>
</span>	