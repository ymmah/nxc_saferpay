<div class="context-block">
	<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">&nbsp;{'Meta information'|i18n( 'extension/saferpay' )}:</h1>
		<div class="header-subline"></div>
	</div></div></div></div></div></div>

	<div class="box-ml"><div class="box-mr"><div class="box-content">

		<div class="block">
			<label>{'ID'|i18n( 'extension/saferpay' )}:</label> {$transaction.id}
		</div>

		<div class="block">
			<label>{'Status'|i18n( 'extension/saferpay' )}:</label> {$transaction.status_description}
		</div>

		{if $transaction.user}
		<div class="block">
			<label>{'Customer'|i18n( 'extension/saferpay' )}:</label> {content_view_gui view=text_linked content_object=$transaction.user}
		</div>
		{/if}

		<div class="block">
			<label>{'Customer IP'|i18n( 'extension/saferpay' )}:</label> {$transaction.user_ip_string}
		</div>

		<div class="block">
			<label>{'Created'|i18n( 'extension/saferpay' )}:</label> {$transaction.created|datetime('custom','%d.%m.%Y %H:%i:%s')}
		</div>

		<div class="block">
			<label>{'Changed'|i18n( 'extension/saferpay' )}:</label> {$transaction.changed|datetime('custom','%d.%m.%Y %H:%i:%s')}
		</div>

		<div class="block">
			<label>{'Used settings file'|i18n( 'extension/saferpay' )}:</label> {$transaction.settings_file}
		</div>

		{if $transaction.extra_data}
		<div class="block">
			<label>{'Extra data'|i18n( 'extension/saferpay' )}:</label> {$transaction.extra_data}
		</div>
		{/if}

		{if $transaction.payment_url}
		<div class="block">
			<label>{'Payment URL'|i18n( 'extension/saferpay' )}:</label> {$transaction.payment_url}
		</div>
		{/if}

	</div></div></div>

	<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block"></div>
		</div></div></div></div></div></div>
	</div>

</div>

<div class="context-block">
	<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">&nbsp;{'Payment link options'|i18n( 'extension/saferpay' )}:</h1>
		<div class="header-subline"></div>
	</div></div></div></div></div></div>

	<div class="box-ml"><div class="box-mr"><div class="box-content">

		<div class="block">
			<label>{'Merchant\'s Saferpay account number'|i18n( 'extension/saferpay' )}:</label> {$transaction.account_id}
		</div>

		<div class="block">
			<label>{'Payment amount'|i18n( 'extension/saferpay' )}:</label> {$transaction.amount|div( 100 )}
		</div>

		<div class="block">
			<label>{'Currency code'|i18n( 'extension/saferpay' )}:</label> {$transaction.currency}
		</div>

		<div class="block">
			<label>{'Sales description'|i18n( 'extension/saferpay' )}:</label> {$transaction.order_description}
		</div>

		<div class="block">
			<label>{'Request the card verification number'|i18n( 'extension/saferpay' )}:</label> {$transaction.request_card_verification_number}
		</div>

		<div class="block">
			<label>{'Request the cardholder or account holder'|i18n( 'extension/saferpay' )}:</label>{$transaction.request_cardholder_name}
		</div>

		{if $transaction.order}
		<div class="block">
			<label>{'Order ID'|i18n( 'extension/saferpay' )}:</label> <a href="{concat( '/shop/orderview/', $transaction.order.id, '/' )|ezurl( 'no' )}">{$transaction.order.order_nr}</a>
		</div>
		{/if}

		<div class="block">
			<label>{'URL, which is to be called up upon successful authorization'|i18n( 'extension/saferpay' )}:</label>{$transaction.success_url}
		</div>

		<div class="block">
			<label>{'URL, which is to be called up upon abort by the customer'|i18n( 'extension/saferpay' )}:</label>{$transaction.back_url}
		</div>

		<div class="block">
			<label>{'URL, which is to be called up if the payment cannot be carried out'|i18n( 'extension/saferpay' )}:</label>{$transaction.fail_url}
		</div>

		<div class="block">
			<label>{'Saferpay sends the result of the a successful authorization or payment directly to this URL'|i18n( 'extension/saferpay' )}:</label>{$transaction.notify_url}
		</div>

		<div class="block">
			<label>{'Delivery'|i18n( 'extension/saferpay' )}:</label>{$transaction.delivery}
		</div>

		{if $transaction.user_notify_email}
		<div class="block">
			<label>{'Email address of the customer'|i18n( 'extension/saferpay' )}:</label>{$transaction.user_notify_email}
		</div>
		{/if}

		{if $transaction.merchant_notify_email}
		<div class="block">
			<label>{'Email address of the merchant'|i18n( 'extension/saferpay' )}:</label>{$transaction.merchant_notify_email}
		</div>
		{/if}

		{if $transaction.autoclose}
		<div class="block">
			<label>{'Autoclose'|i18n( 'extension/saferpay' )}:</label>{$transaction.autoclose}
		</div>
		{/if}

		{if $transaction.provider_set}
		<div class="block">
			<label>{'Providers set'|i18n( 'extension/saferpay' )}:</label>{$transaction.provider_set}
		</div>
		{/if}

		<div class="block">
			<label>{'Virtual Terminal language'|i18n( 'extension/saferpay' )}:</label>{$transaction.language}
		</div>

	</div></div></div>

	<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block"></div>
		</div></div></div></div></div></div>
	</div>

</div>

{if $transaction.saferpay_transaction_id}
<div class="context-block">
	<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">&nbsp;{'Success response values'|i18n( 'extension/saferpay' )}:</h1>
		<div class="header-subline"></div>
	</div></div></div></div></div></div>

	<div class="box-ml"><div class="box-mr"><div class="box-content">

		{if $transaction.saferpay_transaction_id}
		<div class="block">
			<label>{'Saferpay transaction identification'|i18n( 'extension/saferpay' )}:</label> {$transaction.saferpay_transaction_id}
		</div>
		{/if}

		{if $transaction.token}
		<div class="block">
			<label>{'Token'|i18n( 'extension/saferpay' )}:</label> {$transaction.token}
		</div>
		{/if}

		{if $transaction.provider_id}
		<div class="block">
			<label>{'Provider\'s ID'|i18n( 'extension/saferpay' )}:</label> {$transaction.provider_id}
		</div>
		{/if}

		{if $transaction.provider_name}
		<div class="block">
			<label>{'Provider\'s name'|i18n( 'extension/saferpay' )}:</label> {$transaction.provider_name}
		</div>
		{/if}

		{if $transaction.authcode}
		<div class="block">
			<label>{'Processor\'s authorization code'|i18n( 'extension/saferpay' )}:</label> {$transaction.authcode}
		</div>
		{/if}

		{if $transaction.contract_number}
		<div class="block">
			<label>{'Contract number'|i18n( 'extension/saferpay' )}:</label> {$transaction.contract_number}
		</div>
		{/if}

		{if $transaction.eci}
		<div class="block">
			<label>{'Electronic Commerce Indicator (ECI)'|i18n( 'extension/saferpay' )}:</label> {$transaction.eci}
		</div>
		{/if}

		{if $transaction.cavv}
		<div class="block">
			<label>{'3-D Secure Cardholder Authentication Verification Value (MasterCard UCAF-Wert)'|i18n( 'extension/saferpay' )}:</label> {$transaction.cavv}
		</div>
		{/if}

		{if $transaction.xid}
		<div class="block">
			<label>{'3-D Secure Transaction Identifier'|i18n( 'extension/saferpay' )}:</label> {$transaction.xid}
		</div>
		{/if}

		{if $transaction.ip_country}
		<div class="block">
			<label>{'Countrycode origin of the IP Adress'|i18n( 'extension/saferpay' )}:</label> {$transaction.ip_country}
		</div>
		{/if}

		{if $transaction.cc_country}
		<div class="block">
			<label>{'Countrycode origin of the Creditcard'|i18n( 'extension/saferpay' )}:</label> {$transaction.cc_country}
		</div>
		{/if}

	</div></div></div>

	<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block"></div>
		</div></div></div></div></div></div>
	</div>

</div>
{/if}

{def $log_messages = $transaction.log_messages}
{if gt( $log_messages|count(), 0 )}
<div class="context-block">
	<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">&nbsp;{'Transaction\'s log'|i18n( 'extension/saferpay' )}:</h1>
		<div class="header-subline"></div>
	</div></div></div></div></div></div>

	<div class="box-ml"><div class="box-mr"><div class="box-content">

		<div class="block">
			<ol>
				{foreach $transaction.log_messages as $logMessage}
				<li>{$logMessage.created|datetime( 'custom', '%d.%m.%Y %H:%i' )} - {$logMessage.message}</li>
				{/foreach}
			</ol>
		</div>

	</div></div></div>

	<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block"></div>
		</div></div></div></div></div></div>
	</div>

</div>
{/if}
{undef $log_messages}
