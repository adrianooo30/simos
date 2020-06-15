@foreach( $order_batch_no as $bwesit )
	<span class="text-muted font-12">{{ $bwesit['batch_no']['batch_no'] }} - {{ $bwesit['quantity_format'] }}</span><br>
@endforeach