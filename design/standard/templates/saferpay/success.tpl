{if ne( $error_message, false() )}
	<h1>{$error_message|i18n( 'extension/saferpay' )}</h1>
{else}
	<h1>{'The authorization was successed'|i18n( 'extension/saferpay' )}</h1>
{/if}