@extends('layouts.base')

@section('main')
<div class="card bg-light mb-3">
	<h4 class='card-header'> Adicionar Órdenes de Compra de una fecha </h2>
	<div class="card-body">
		{!! Form::open(['url' => 'oc_list_queries']) !!}
		{!! Form::label('date', 'Fecha: ') !!}
		{!! Form::text('date',null,['id' => 'datepicker']) !!}
		{!! Form::submit('Adicionar',['class'=> 'btn btn-primary']) !!}
		{!! Form::close() !!}
	</div>
</div>
<script>
	$( function() {
		$( "#datepicker" ).datepicker({dateFormat: "dd-mm-yy"});
	} );
</script>

<div class="container">
<h4 class="text-dark">Listas de Órdenes de Compra</h4>
@foreach($oc_list_queries->chunk(3) as $chunk)
	<div class='card-deck'>
	@foreach($chunk as $item)
		<div class="card border-info mb-3">
			<div class="card-body">
				<h5 class="card-title text-dark">{{$item->date->format('d-m-Y')}}: <span class="text-info">{{$item->orden_compras_count}} items</span> </h5>
				<p><small class="text-muted">(obtenida {{$item->query_date}})</small></p>
				<div class="card-block pre-scrollable list-group" >
					@foreach($item->orden_compras()->limit(10)->get() as $oc)
					    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
					    	<h6><span class="font-weight-bold">{{$oc->code}}</span> ({{$oc->pivot->oc_state->name }}) :</h6>
					    	<div>{{$oc->pivot->name}}</div>
					    </button>
					@endforeach
				</div>	
			</div>
		</div>
	@endforeach
	</div>
@endforeach
</div>

@endsection