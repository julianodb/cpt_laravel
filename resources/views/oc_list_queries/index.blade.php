@extends('layouts.base')

@section('main')
<form>
<div class="form-group">
@foreach($oc_list_queries as $item)
	<label for="sel{{ $item->id }}">{{$item->date}} (retrieved {{$item->query_date}}):</label>
	<select multiple class="form-control" id="sel{{ $item->id }}">
	@foreach($item->orden_compras as $oc)
		 <br />
	    <option>{{$oc->code}}: {{$oc->pivot->name}} ({{$oc->pivot->oc_state->name }})</option>
	@endforeach
	</select>	
@endforeach
</div>
</form>
@endsection