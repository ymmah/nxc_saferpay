{literal}
<script type="text/javascript">
if( typeof( MooTools ) != 'undefined' ) {
	window.addEvent( 'domready', function() {
		var options  = {
			'navigationBlocks'         : [ 'paginator-navigation-pages' ],
			'quantityBlocks'           : [ 'paginator-items-per-page' ],
			'possbileQuantities'       : [ 10, 25, 50, 100 ],
			'defaultQuantity'          : 10
		};
		var paginator = new NXC.Paginator.Simple( '#saferpay-transactions-table tbody tr', options );
		paginator.build();

		window.messageStack = ( $type( window.messageStack ) === false ) ? new NXC.MessageStack() : window.messageStack;
	} );
}
</script>
{/literal}

<div class="context-block">
	<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
		<h1 class="context-title">&nbsp;{'SaferPay Transactions'|i18n( 'extension/saferpay' )} [{count( $transactions )}]</h1>
		<div class="header-subline"></div>
	</div></div></div></div></div></div>

	<div class="box-ml"><div class="box-mr"><div class="box-content">

		<div class="context-toolbar">
			<div class="block">
				<div class="left">
					<p id="paginator-items-per-page"></p>
				</div>
				<div class="break"></div>
			</div>
		</div>

		<div class="content-navigation-childlist">
			<table id="saferpay-transactions-table" class="list" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<th>{'ID'|i18n( 'extension/saferpay' )}</th>
						<th>{'Date'|i18n( 'extension/saferpay' )}</th>
						<th>{'Order'|i18n( 'extension/saferpay' )}</th>
						<th>{'Customer'|i18n( 'extension/saferpay' )}</th>
						<th>{'Customer IP'|i18n( 'extension/saferpay' )}</th>
						<th>{'Status'|i18n( 'extension/saferpay' )}</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					{def
						$order = false()
						$user  = false()
					}
					{foreach $transactions as $transaction sequence array( 'bgdark', 'bglight' ) as $style }
					<tr class="{$style}">
						<td>{$transaction.id}</td>
						<td>{$transaction.created|datetime('custom','%d.%m.%Y %H:%i:%s')}</td>
						{set $order = $transaction.order}
						<td>{if $order}<a href="{concat( '/shop/orderview/', $order.id, '/' )|ezurl( 'no' )}">{$order.order_nr}</a>{/if}</td>
						{set $user = $transaction.user}
						<td>{if $user}{content_view_gui view=text_linked content_object=$user}{/if}</td>
						<td>{$transaction.user_ip_string}</td>
						<td>{$transaction.status_description}</td>
						<td>
							<a href="{concat( 'saferpay/details/', $transaction.id )|ezurl( 'no' )}"><img src="{'saferpay/details.png'|ezimage( 'no' )}" alt="{'Details'|i18n( 'extension/saferpay' )}" title="{'Details'|i18n( 'extension/saferpay' )}" /></a>
						</td>
					</tr>
					{/foreach}
					{undef $order $user}
				</tbody>
			</table>
		</div>

		<div class="context-toolbar">
			<div class="pagenavigator">
				<span id="paginator-navigation-pages"></span>
				<div class="break"></div>
			</div>
		</div>

	</div></div></div>

	<div class="controlbar">
		<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
			<div class="block"></div>
		</div></div></div></div></div></div>
	</div>

</div>