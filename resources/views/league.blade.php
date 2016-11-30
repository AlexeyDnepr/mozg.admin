@extends('layouts.app')

@section('content')
<form class="form-inline filter">
    <div class="form-group">
	<div class="input-group">
	    <div class="input-group-addon">Date</div>
	    <input type="date" name="date_from" value="{{ isset($selected_value['date_from']) ? $selected_value['date_from'] : '' }}" class="form-control" placeholder='гггг-мм-дд' pattern='201[0-9]-[0-9]{2}-[0-9]{2}'>
	    <div class="input-group-addon">-</div>
	    <input type="date" name="date_to" value="{{ isset($selected_value['date_to']) ? $selected_value['date_to'] : '' }}" class="form-control" placeholder='гггг-мм-дд' pattern='201[0-9]-[0-1][0-9]-[0-3][0-9]'>
	</div>
    </div>
    <div class="form-group">
	<select name="kingdom" class="form-control">
	    <option value="">Sport</option>
	    @foreach($sports as $sport)
	    <option value="{{ $sport->name_en }}" {{ (isset($selected_value['kingdom']) && $selected_value['kingdom'] == $sport->name_en) ? ' selected' : '' }}>{{ $sport->name_ru }}</option>
	    @endforeach
	</select>
    </div>
    <div class="form-group">
	<select name="type_prognosis" class="form-control">
	    <option value="">Free/Pay</option>
	    <option value="Free" {{ (isset($selected_value['type_prognosis']) && $selected_value['type_prognosis'] == 'Free') ? ' selected' : '' }}>Free</option>
	    <option value="Pay" {{ (isset($selected_value['type_prognosis']) && $selected_value['type_prognosis'] == 'Pay') ? ' selected' : '' }}>Pay</option>
	</select>
    </div>
    <input type="submit" class="btn btn-primary">
</form>
<form class="form-inline control" method="POST">
    {{ csrf_field() }}
    <div class="control_block">
	<div class="form-group" id='delete_block'>
	    <div class="input-group">
		<input class="hidden" id='hard_delete' type='radio' name="control_block" value="hard_delete" disabled='disabled'>
		<label class="glyphicon glyphicon-flash input-group-addon" for='hard_delete'></label> 
		<input class="hidden" id='soft_delete' type='radio' name="control_block" value="soft_delete" disabled='disabled'>
		<label class="glyphicon glyphicon-share-alt input-group-addon" for='soft_delete'></label>
		<button type="submit" name='action' class="btn btn-default form-control" value='delete' disabled='disabled'>Удалить</button>
	    </div>
	</div>
	<div class="form-group" id='recalculate_block'>
	    <div class="input-group">
		<input class="hidden" id='won' type='radio' name="control_block" value="won" disabled='disabled'>
		<label class="glyphicon glyphicon-ok-sign input-group-addon" for='won'></label> 
		<input class="hidden" id='lost' type='radio' name="control_block" value="lost" disabled='disabled'>
		<label class="glyphicon glyphicon-remove-sign input-group-addon" for='lost'></label> 
		<input class="hidden" id='return' type='radio' name="control_block" value="return" disabled='disabled'>
		<label class="glyphicon glyphicon-record input-group-addon" for='return'></label> 
		<button type="submit" name='action' class="btn btn-default form-control" value='recalculate' disabled='disabled'>Рассчитать</button>
	    </div>
	</div>
    </div>

    <table class="table table-striped table-bordered">
	<thead>
	    <tr>
		<th></th>
		<th>ID</th>
		<th>Событие</th>
		<th>Дата</th>
		<th>Аналитик</th>
		<th>Free/Pay</th>
		<th></th>
	    </tr>
	</thead>
	<tbody>
	    @foreach($items as $key => $item)
	    <tr>
		<td><label><input type="checkbox" value="{{$item['id']}}" name="page_id_{{$item['id']}}"></label></td>
		<td>{{$item['id']}}</td>
		<td><div class="{{ strtolower($item['kingdom']) }} kingdom">{{ $item['pagetitle'] }}</div></td>
		<td>{{ $item['publishedon'] }}</td>
		<td>{{ $item['username'] }}</td>
		<td>{{ $item['type_prognosis'] }}</td>
		<td><div class="{{ isset($item['calculate']) ? $item['calculate'] : 'not' }} calculate"></div></td>
	    </tr>
	    @endforeach
	</tbody>
	<tfoot>
	    <tr>
		<td colspan="3"><div class="pager pull-right">{{ $count_Items }} строк</div></td>
		<td colspan="4">{{ $items->render() }}</td>
	    </tr>
	</tfoot>
    </table>
</form>
<h1>{{ $deltaTime = microtime(true) - $startTime }}</h1>
<script src="{{ elixir("js/all.js") }}"></script>
@endsection
