@extends('layouts.base')

@section('main')
<div class="card bg-light mb-3">
	<h4 class='card-header'> Adicionar Item de una Órden de Compra</h2>
	<div class="card-body">
		{!! Form::open(['url' => 'oc_item_queries']) !!}
		{!! Form::label('code', 'Código: ') !!}
		{!! Form::text('code',null,['id' => 'codepicker']) !!}
		{!! Form::submit('Adicionar',['class'=> 'btn btn-primary']) !!}
		{!! Form::close() !!}
	</div>
</div>
<script>
$( "#codepicker" ).autocomplete({
  source: function( request, response ) {
    $.post( {
      url: "{{route('oc_item_suggest')}}",
      data: {'partial': request.term, '_token': "{{csrf_token()}}"},
      success: function( data ) {
        response( data );
      }
    } );
  },
  select: function( event, ui ) {
    console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
  }
});
</script>

<div class="container">
<h4 class="text-dark">Items de Órdenes de Compra</h4>
@foreach($oc_item_queries->chunk(3) as $chunk)
	<div class='card-deck'>
	@foreach($chunk as $item)
		<div class="card border-info mb-3">
			<div class="card-body">
				<h5 class="card-title text-dark">{{ $item->orden_compra->code }}: <span class="text-info">{{$item->name}}</span> </h5>
				<p><small class="text-muted">(obtenida {{$item->query_date}})</small></p>
				<div class="card-block pre-scrollable list-group" >
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Descripción</span>: </h6>
				    	<div>{{$item->description}}</div>
				    </button>
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Financiamiento</span>: </h6>
				    	<div>{{$item->financing}}</div>
				    </button>
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Pais</span>: </h6>
				    	<div>{{$item->country}}</div>
				    </button>
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Clasificaciones</span>: </h6>
				    	<div>Promedio: {{$item->classification_mean}}</div>
				    	<div>Número: {{$item->classification_n}}</div>
				    </button>
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Estado</span>: </h6>
				    	<div>{{$item->oc_state->name}}</div>
				    </button>
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Tipo</span>: </h6>
				    	<div>{{$item->oc_type->name}}</div>
				    </button>
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Forma de Entrega</span>: </h6>
				    	<div>{{$item->oc_delivery_type->name}}</div>
				    </button>
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Forma de Pago</span>: </h6>
				    	<div>{{$item->oc_payment_type->name}}</div>
				    </button>
				    <button type="button" class="list-group-item list-group-item-secondary list-group-item-action">
				    	<h6><span class="font-weight-bold">Fechas</span>: </h6>
				    	<div>Creada: {{$item->created_at}}</div>
				    	<div>Enviada: {{$item->sent_at}}</div>
				    	<div>Aceptada: {{$item->accepted_at}}</div>
				    	<div>Cancelada: {{$item->cancelled_at}}</div>
				    	<div>Última Actualización: {{$item->updated_at}}</div>
				    </button>
				</div>	
			</div>
		</div>
	@endforeach
	</div>
@endforeach
</div>

@endsection