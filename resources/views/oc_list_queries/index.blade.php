{{ $oc_list_queries }} <br />
@foreach($oc_list_queries as $item)
	{{ $item->orden_compras }}  <br />
	@foreach($item->orden_compras as $oc)
		{{$oc }} <br />
		{{$oc->pivot->oc_state }} <br />
	@endforeach
@endforeach