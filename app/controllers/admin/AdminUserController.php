<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminUserController extends \BaseController {

	public function __construct()
	{
		$this->beforeFilter('auth', array('except' => array('getLogin', 'postLogin')));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::all();
		return View::make('admin.users.index')->withUsers($users);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$user = new User();
		$form = array('url' => url('admin/user'));
		return View::make('admin.users.form')->withUser($user)->withForm($form);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
			'email' => 'required|email|unique:users',
			'password' => 'required'
		);

		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails())
		{
			return Redirect::to(url('admin/user/create'))->withErrors($validator)->withInput();
		}
		
		$user = new User;
		$user->email = Input::get('email');
		$user->password = Hash::make(Input::get('password'));
		$user->save();

		Session::flash('message', array('success' => 'The user has been created.'));
		return Redirect::to(url('admin/user/' . $user->id . '/edit'));

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		try
		{
			$user = User::findOrFail($id);
		}
		catch(ModelNotFoundException $e)
		{
			Session::flash('message', array('danger' => 'Invalid User.'));
			return Redirect::to(url('admin/user'));
		}
		$form = array('url' => url('admin/user/' . $id), 'method' => 'PUT');
		return View::make('admin.users.form')->withUser($user)->withForm($form);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		try
		{
			$user = User::findOrFail($id);
		}
		catch(ModelNotFoundException $e)
		{
			Session::flash('message', array('danger' => 'Invalid User.'));
			return Redirect::to(url('admin/user'));
		}
		$rules = array(
			'email' => 'required|email|unique:users,email,' . $user->id,
		);

		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails())
		{
			return Redirect::to(url('admin/user/' . $id . '/edit'))->withErrors($validator)->withInput();
		}
		
		$user->email = Input::get('email');
		if(Input::get('password'))
		{
			$user->password = Hash::make(Input::get('password'));
		}
		$user->save();

		Session::flash('message', array('success' => 'The user has been updated.'));
		return Redirect::to(url('admin/user/' . $user->id . '/edit'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try
		{
			$user = User::findOrFail($id);
		}
		catch(ModelNotFoundException $e)
		{
			Session::flash('message', array('danger' => 'Invalid User.'));
			return Redirect::to(url('admin/user'));
		}
		$user->delete();

		Session::flash('message', array('success' => 'The user has been removed.'));
		return Redirect::to(url('admin/user/'));
	}

	public function getLogin()
	{
		return View::make('admin.users.login');
	}

	public function postLogin()
	{
		if(Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password'))))
		{
			Session::flash('message', array('success' => 'Hi, ' . Auth::user()->email));
			return Redirect::intended('admin/user');
		}
		Session::flash('message', array('danger' => 'Nope.'));
		return Redirect::to(url('admin/login'));
	}

	public function logout()
	{
		Auth::logout();
		Session::flash('message', array('success' => 'You\'ve been logged out.'));
		return Redirect::to(url('admin/login'));
	}


}
