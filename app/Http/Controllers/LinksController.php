<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Models\Links;
use App\Models\Hits;


class LinksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	private function generateRandomString($length = 5) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	private function checkShortUrl($short_url) 
	{
		return (Links::where('short', $short_url)->count() > 0);
	}
	
	public function index()
    {
//		dd(Auth::user()->links);
		return view('links', [ 'links' => Auth::user()->links->sortByDesc('created_at') ]);
    }

    public function short(Request $request)
	{
		$request->validate([
			'url' => 'url',
//			'lifetime' => 'integer'
		]);
		
		if ($request->filled('shortname')) {
			$request->validate([
				'shortname' => 'alpha_num|size:5',
			]);
			$short_url = $request->get('shortname');
			if ($this->checkShortUrl($short_url)) {
				throw ValidationException::withMessages(['short_name' => 'Short link is already exists.']);
			}
		}
		else {
			do { 
				$short_url = $this->generateRandomString();
			} while ($this->checkShortUrl($short_url));
		}
		$url = $request->get('url');
		$lifetime = $request->filled('lifetime') ? date_create('+' . $request->get('lifetime') . ' seconds') : null;
		
		Auth::user()->links()->create([ 'long_url' => $url,
										'short' => $short_url,
										'expires_at' => $lifetime,
										]);

		return $request->getSchemeAndHttpHost() . '/' . $short_url;
    }

    public function go($shortLink)
    {		
		if( is_null($ext_link = Links::where('short', $shortLink)->first()) ) abort(404);
//		dd($ext_link);
		if ($ext_link->expires_at == null || date_create('now') < date_create($ext_link->expires_at) ) {
			Hits::create(['links_id' => $ext_link->id, 'ip' => \Request::ip()]);
			return redirect($ext_link->long_url);
		} else {
			return response('link expires at ' . $ext_link->expires_at, 200)
                  ->header('Content-Type', 'text/plain');
		}	
	}
}
