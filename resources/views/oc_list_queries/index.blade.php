@extends('layouts.base')

@section('main')
{!! Form::open(['url' => 'oc_list_queries']) !!}
{!! Form::text('date',null,['id' => 'datepicker']) !!}
{!! Form::submit('Crear') !!}
{!! Form::close() !!}
<script>
	$( function() {
		$( "#datepicker" ).datepicker({dateFormat: "dd/mm/yy"});
	} );
</script>
<form>
<div class="form-group">
@foreach($oc_list_queries as $item)
	<label for="sel{{ $item->id }}">{{$item->date}}: {{$item->orden_compras_count}} (retrieved {{$item->query_date}})</label>
	<select multiple class="form-control" id="sel{{ $item->id }}">
	@foreach($item->orden_compras()->limit(10)->get() as $oc)
	    <option>{{$oc->code}}: {{$oc->pivot->name}} ({{$oc->pivot->oc_state->name }})</option>
	@endforeach
	</select>	
@endforeach
</div>
</form>
@endsection