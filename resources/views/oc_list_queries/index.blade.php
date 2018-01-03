@extends('layouts.base')

@section('main')
<div class="card bg-light mb-3">
	<h4 class='card-header'> Adicionar Órdenes de Compra de una fecha </h2>
	<div class="card-body">
		{!! Form::open(['url' => 'oc_list_queries']) !!}
		{!! Form::text('date',null,['id' => 'datepicker']) !!}
		{!! Form::submit('Adicionar',['class'=> 'btn btn-primary']) !!}
		{!! Form::close() !!}
	</div>
</div>
<script>
	$( function() {
		$( "#datepicker" ).datepicker({dateFormat: "dd/mm/yy"});
	} );
</script>
<form>
{{--<div class="form-group">--}}
<div class="row">
@foreach($oc_list_queries as $item)
	<div class="col-lg-6">
		<div class="card card-body mb-3">
			<div class="form-group">
				<label for="sel{{ $item->id }}">{{$item->date}}: {{$item->orden_compras_count}} (obtenida {{$item->query_date}})</label>
				<select multiple class="form-control" id="sel{{ $item->id }}">
				@foreach($item->orden_compras()->limit(10)->get() as $oc)
				    <option>{{$oc->code}}: {{$oc->pivot->name}} ({{$oc->pivot->oc_state->name }})</option>
				@endforeach
				</select>	
			</div>
		</div>
	</div>
@endforeach
</div>
</form>
@endsection