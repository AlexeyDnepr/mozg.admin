<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use App\Models\Leagues;

class League extends Controller {

    public function index(Request $request) {
	$perPage = 30;
	$currentPage = Input::get('page', 1);
	$offSet = ($currentPage * $perPage) - $perPage;
	$new_mass = [];

	/*

	  $available_input = ['sort', 'date_from', 'date_to', 'range_from', 'range_to', 'users_names'];
	  $sort = (object) null;
	  $sort->column = 'users.id';
	  $sort->direction = 'desc';
	  $users_names = DB::table('users')->lists('username');
	  $user_type = ['Beginner', 'Profy', 'Expert', 'SuperExpert'];
	  $mass_value = [];
	  //Выборка id пользователей от новых до старых, а если заданы фильтры, то и их параметры
	  $ids = DB::table('membergroup_names')
	  ->whereIn('membergroup_names.name', $user_type);
	  $ids->leftJoin('member_groups', 'membergroup_names.id', '=', 'member_groups.user_group')
	  ->leftJoin('users', 'member_groups.member', '=', 'users.id')
	  ->leftJoin('user_accounts_virtual', 'users.id', '=', 'user_accounts_virtual.user_id');

	  foreach ($this->inputs as $name => $value) {
	  if (!in_array($name, $available_input) || empty($value)) {
	  continue;
	  }
	  switch ($name) {
	  case 'users_names':
	  $ids->where('users.username', $value);
	  break;
	  case 'date_from':
	  $ids->where('user_accounts_virtual.timeregister', '>=', $value);
	  break;
	  case 'date_to':
	  $ids->where('user_accounts_virtual.timeregister', '<=', $value);
	  break;
	  case 'range_from':
	  $ids->where('user_accounts_virtual.timeregister', '<=', $value);
	  break;
	  case 'range_to':
	  $ids->where('user_accounts_virtual.timeregister', '<=', $value);
	  break;
	  case 'sort':
	  switch ($value) {
	  case 'pos_desc':
	  $sort->column = 'user_accounts_virtual.rating_account';
	  $sort->direction = 'asc';
	  break;
	  case 'pos_asc':
	  $sort->column = 'user_accounts_virtual.rating_account';
	  $sort->direction = 'desc';
	  break;
	  case 'user_name_desc':
	  $sort->column = 'users.username';
	  $sort->direction = 'asc';
	  break;
	  case 'user_name_asc':
	  $sort->column = 'users.username';
	  $sort->direction = 'desc';
	  break;
	  case 'user_id_desc':
	  $sort->column = 'users.id';
	  $sort->direction = 'asc';
	  break;
	  case 'user_id_asc':
	  $sort->column = 'users.id';
	  $sort->direction = 'desc';
	  break;
	  }
	  break;
	  }
	  }

	  $ids = $ids->whereNotNull('users.id')
	  ->skip($offSet)
	  ->take($perPage + 1)
	  ->orderBy($sort->column, $sort->direction);
	  $ids->select('users.id')->distinct();
	  $ids = $ids->lists('users.id');

	  $items = DB::table('users')
	  ->leftJoin('member_groups', 'users.id', '=', 'member_groups.member')
	  ->leftJoin('membergroup_names', 'member_groups.user_group', '=', 'membergroup_names.id')
	  ->leftJoin('user_accounts_virtual', 'users.id', '=', 'user_accounts_virtual.user_id')
	  ->whereIn('users.id', $ids)
	  ->whereIn('membergroup_names.name', $user_type)
	  ->orderBy($sort->column, $sort->direction)
	  ->select('users.id', 'users.username', 'user_accounts_virtual.timeregister', 'user_accounts_virtual.virtual_account', 'user_accounts_virtual.rating_account', 'membergroup_names.name')
	  ->get();
	  $sort_position = DB::table('user_accounts_virtual')
	  ->whereNotNull('rating_account')
	  ->orderBy('rating_account', 'desc')
	  ->lists('user_id');
	  foreach ($items as $item) {
	  $new_mass[$item->id]['id'] = $item->id;
	  $new_mass[$item->id]['username'] = $item->username;
	  $new_mass[$item->id]['position'] = gettype($index = array_search($item->id, $sort_position)) === 'integer' ? $index + 1 : false;
	  $new_mass[$item->id]['timeregister'] = $item->timeregister;
	  $new_mass[$item->id]['virtual_account'] = $item->virtual_account;
	  $new_mass[$item->id]['rating_account'] = $item->rating_account;
	  $new_mass[$item->id]['name'] = $item->name;
	  }

	  // Выборка дополнительных данных пользователей
	  $items = DB::table('user_accounts_personal')
	  ->whereIn('user_id', $ids)
	  ->get();
	  foreach ($items as $item) {
	  $new_mass[$item->user_id]['user_id'] = $item->user_id;
	  $new_mass[$item->user_id]['currency_id'] = $item->currency_id;
	  $new_mass[$item->user_id][$item->payment_system_id.'_balance'] = $item->balance;
	  }
	  //

	  $payment_systems = DB::table('payment_systems')->get();
	 */
	$ids = DB::table('site_tmplvar_contentvalues')
			->where('value', 'Marathon Bet')->lists('contentid');

	$count_values = DB::table('site_content')
		->leftjoin('site_tmplvar_contentvalues', 'site_content.id', '=', 'site_tmplvar_contentvalues.contentid')
		->leftjoin('site_tmplvars', 'tmplvarid', '=', 'site_tmplvars.id')
		->whereIn('site_content.id', $ids)
		->where('site_tmplvars.name', 'league_en')
		->lists('site_tmplvar_contentvalues.value');
	$count_values = array_count_values($count_values);
	arsort($count_values);
	$asort_count_values = array_keys($count_values);
	dump($count_values);
	dump($asort_count_values);
	///////////
	$items = DB::table('league_standards AS standard')
		->leftJoin('league_connectors AS connector', 'standard.connector_id', '=', 'connector.id')
		->leftJoin('league_countries AS country', 'standard.isCountry', '=', 'country.id')
		->leftJoin('league_consolidated AS marathon', 'standard.Marathon', '=', 'marathon.id')
		->leftJoin('league_consolidated AS william', 'standard.William', '=', 'william.id')
		->select(['standard.id', 'connector.connector_en', 'connector.connector_ru', 'standard.standart_en', 'standard.standart_ru', 'standard.origin_standart_en', 'standard.origin_standart_ru', 'standard.isCountry', 'country.ISO', 'marathon.name_en AS marathon_name_en', 'marathon.name_ru AS marathon_name_ru', 'william.name_en AS william_name_en', 'william.name_ru AS william_name_ru'])
		->orderBy('id', 'DESC')
		->skip($offSet)
		->take($perPage + 1)
		->get();
	foreach ($items as $item) {
	    $new_mass[$item->id]['id'] = $item->id;
	    $new_mass[$item->id]['isCountry'] = $item->isCountry;
	    $new_mass[$item->id]['ISO'] = $item->ISO;
	    $new_mass[$item->id]['connector_en'] = $item->connector_en;
	    $new_mass[$item->id]['connector_ru'] = $item->connector_ru;
	    $new_mass[$item->id]['standart_en'] = $item->standart_en;
	    $new_mass[$item->id]['standart_ru'] = $item->standart_ru;
	    $new_mass[$item->id]['origin_standart_en'] = $item->origin_standart_en;
	    $new_mass[$item->id]['origin_standart_ru'] = $item->origin_standart_ru;
	    $new_mass[$item->id]['marathon.name_en'] = $item->marathon_name_en;
	    $new_mass[$item->id]['marathon.name_ru'] = $item->marathon_name_ru;
	    $new_mass[$item->id]['william.name_en'] = $item->william_name_en;
	    $new_mass[$item->id]['william.name_ru'] = $item->william_name_ru;
	    $new_mass[$item->id]['pub_forec'] = FALSE;
	    $new_standart_en = str_replace($item->connector_en, '', $item->standart_en);
	    $new_standart_en = preg_replace('/(^[\s\.]+|[\s\.]+$)/m', '', $new_standart_en);
	    foreach ($count_values as $key => $value) {
		if (strpos($key, $item->connector_en . '. ' . $new_standart_en) !== false) {
		    $new_mass[$item->id]['pub_forec'] = $value;
		    break;
		}
	    }
	}
	$new_mass = array_slice($new_mass, 0, null, true);
	//dump($new_mass);
	$leagueConsolidated = Leagues\LeagueConsolidated::where('bookmaker_id', 1)->select('id', 'name_en')->get()->toJson();
	return view('league')->with([
		    'items' => new Paginator($new_mass, $perPage, $currentPage, [
			'path' => preg_replace('/(&|\?)page=[\d]+(&|)/m', '?', $request->fullUrl())
			    ]),
		    'leagueConsolidated' => json_encode($leagueConsolidated)
			/*
			  'startTime' => $this->startTime,
			  'count_Items' => count($sort_position),
			  //'sports' => $sports,
			  'selected_value' => $this->inputs,
			  'request' => $request->fullUrl(),
			  'users_names' => $users_names,
			  'payment_systems' => $payment_systems
			 * */
	]);
    }

    public function post() {
	$result = Leagues\LeagueStandards::where('id', $_POST['id'])->update(['William' => $_POST['value']]);
    }

}
