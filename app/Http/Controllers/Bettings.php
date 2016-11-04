<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Page;
use App\Tv;
use App\Tv_name;
use App\UserVirtualAccounts;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\MyCustom\Calculate;

class Bettings extends Controller {

    private $startTime;

    public function __construct() {
	$this->startTime = microtime(true);
    }

    public function getTimeInterval($startTime) {
	return microtime(true) - $startTime;
    }

    public function pre_dump($items) {
	echo '<pre>';
	var_dump($items);
	echo '</pre>';
    }

    public function getPages(Request $request) {
	$perPage = 10;
	$currentPage = Input::get('page', 1);
	$offSet = ($currentPage * $perPage) - $perPage;
	$new_mass = [];

	$inputs = Input::all();
	$available_input = ['date_from', 'date_to', 'kingdom', 'type_prognosis', 'calculate'];
	$mass_value = [];
	//Удаление/перерассчет ресурсов
	if (Input::has('action')) {
	    $func_to = Input::get('control_block');
	    $action = Input::get('action');
	    foreach ($inputs as $key => $value) {
		if (strpos($key, 'page_id_') !== false) {
		    $page = Page::find($value);
		    $page->bet = Tv_name::where('name', 'bet')->first()->tvs()->where('page_id', $page->id)->first();
		    $page->odds = Tv_name::where('name', 'odds')->first()->tvs()->where('page_id', $page->id)->first();
		    $page->user = UserVirtualAccounts::find($page->publishedby);
		    $page->calculate = Tv_name::where('name', 'calculate')->first()->tvs()->where('page_id', $page->id)->first();
		    switch ($action) {
			case 'delete':
			    if (!$page->calculate) {
				if ($func_to === 'soft_delete') {
				    $params = [
					'virtual_account' => $page->user->virtual_account,
					'rating_account' => $page->user->rating_account,
					'bet' => $page->bet->value,
					'odds' => $page->odds->value,
					'func_from' => null,
					'func_to' => 'soft_delete',
				    ];
				    $calc = new Calculate($params);
				    $UserVirtualAccounts = UserVirtualAccounts::find($page->user->user_id);
				    $UserVirtualAccounts->virtual_account = $calc->virtual_account;
				    $UserVirtualAccounts->rating_account = $calc->rating_account;
				    $UserVirtualAccounts->save();
				}
				Page::destroy($page->id);
			    } else {
				continue;
			    }

			    break;
			case 'recalculate':
			    if ($page->calculate) {
				$params = [
				    'virtual_account' => $page->user->virtual_account,
				    'rating_account' => $page->user->rating_account,
				    'bet' => $page->bet->value,
				    'odds' => $page->odds->value,
				    'func_from' => 're_' . $page->calculate->value,
				    'func_to' => 'to_' . $func_to,
				];
				$calc = new Calculate($params);
				/**/
				$UserVirtualAccounts = UserVirtualAccounts::find($page->user->user_id);
				$UserVirtualAccounts->virtual_account = $calc->virtual_account;
				$UserVirtualAccounts->rating_account = $calc->rating_account;
				$UserVirtualAccounts->save();
				/**/
				$Tv = Tv::find($page->calculate->id);
				$Tv->value = $func_to;
				$Tv->save();
			    } else {
				continue;
			    }
			    break;
		    }
		}
	    }
	}
	//Выборка id ресурсов от новых до старых, а если заданы фильтры, то и их параметры
	$ids = DB::table('pages')
		->where('pages.template', 9);
	foreach ($inputs as $name => $value) {
	    if (!in_array($name, $available_input) || empty($value)) {
		continue;
	    }
	    switch ($name) {
		case 'date_from':
		    $ids->where('pages.publishedon', '>', strtotime($value));
		    break;
		case 'date_to':
		    $ids->where('pages.publishedon', '<', strtotime($value) + 60 * 60 * 24);
		    break;
		case 'kingdom':
		case 'type_prognosis':
		    $ids->leftjoin('tvs AS t_' . $name, 'pages.id', '=', 't_' . $name . '.page_id');
		    $ids->where('t_' . $name . '.value', $value);
		    break;
		case 'calculate':
		    $ids->leftjoin('tvs AS t_' . $name, 'pages.id', '=', 't_' . $name . '.page_id');
		    $ids->where('t_' . $name . '.value', '<', strtotime($value));
		    break;
	    }
	}
	$ids->select(['pages.id'])->distinct();
	//$this->pre_dump($ids->toSql());

	$count_Items = $ids;
	$count_Items = $count_Items->count();

	//select * from `pages` where `template` = 9 and `publishedon` > 1477094400
	/*
	  $items = $items->leftjoin('tvs', 'pages.id', '=', 'tvs.page_id');
	  $items = $items->leftjoin('tv_names', 'tv_name_id', '=', 'tv_names.id');
	 */
	//$items = $items->where('tv_names.name', 'kingdom');
	$ids = $ids->skip($offSet)
		->take($perPage + 1)
		->orderBy('pages.id', 'desc');
	$ids = $ids->select(['pages.id'])->get();
	$ids = array_map(function($item) {
	    return $item->id;
	}, $ids);
	// Выборка спорта для фильтров
	$sports = DB::table('kingdom')->get();
	// Выборка данных прогнозов
	$items = DB::table('pages')
		->leftjoin('users', 'pages.publishedby', '=', 'users.id')
		->whereIn('pages.id', $ids)
		->orderBy('id', 'desc')
		->select(['pages.id', 'pages.pagetitle', 'pages.publishedon', 'pages.uri', 'users.username'])
		->get();
	foreach ($items as $item) {
	    $new_mass[$item->id]['id'] = $item->id;
	    $new_mass[$item->id]['pagetitle'] = $item->pagetitle;
	    $new_mass[$item->id]['uri'] = $item->uri;
	    $new_mass[$item->id]['publishedon'] = date('Y-m-d', $item->publishedon);
	    $new_mass[$item->id]['username'] = $item->username;
	}
	// Выборка дополнительных данных прогнозов
	$items = DB::table('pages')
		->leftjoin('tvs', 'pages.id', '=', 'tvs.page_id')
		->leftjoin('tv_names', 'tv_name_id', '=', 'tv_names.id')
		->whereIn('pages.id', $ids)
		->whereIn('tv_names.name', ['kingdom', 'type_prognosis', 'calculate'])
		->orderBy('id', 'desc')
		->select(['pages.id', 'tv_names.name', 'tvs.value'])
		->get();
	foreach ($items as $item) {
	    $new_mass[$item->id][$item->name] = $item->value;
	}
	//
	$new_mass = array_slice($new_mass, 0, null, true);
	//
	return view('prognosis')->with([
		    'items' => new Paginator($new_mass, $perPage, $currentPage, ['path' => preg_replace('/(&|\?)page=[\d]+/m', '', $request->fullUrl())]),
		    'startTime' => $this->startTime,
		    'count_Items' => $count_Items,
		    'sports' => $sports,
		    'selected_value' => $inputs
	]);

	/*
	 * if($count = (int)Input::get('count')){
	  $items->take($count);
	  } */
    }

}
