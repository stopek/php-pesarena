<span style="float-left">
	{if $option_1}
		<a href="{$BASE_URL}results/{$ident}/typeresult/"><button>wprowadz&nbsp;wynik</button></a>
	{/if}

	{if $option_2}

		<a href="{$BASE_URL}results/{$ident}/acceptresult/" onclick="confirm('Czy na pewno chcesz potwierdziæ ten wynik?');"><button>potwierdz</button></a>
		<a href="{$BASE_URL}results/{$ident}/reject/" onclick="confirm('Czy ten wynik na pewno jest b³êdny?');"><button>odrzuæ</button></a>
	{/if}	

	{if $option_3}
		<a href="{$BASE_URL}results/{$ident}/edit/"><button>Edytuj</button></a>
	{/if}
</span>

