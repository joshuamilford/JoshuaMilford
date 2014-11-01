<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminPageController extends \BaseController {

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
		$pages = Page::all();
		return View::make('admin.pages.index')->withPages($pages);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$page = new Page();
		$form = array('url' => url('admin/page'));
		return View::make('admin.pages.form')->withPage($page)->withForm($form);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if(! Input::get('slug'))
		{
			$slug = Str::slug(Input::get('title'));
			$i = 1;
			do
			{
				$data = Page::where('slug', '=', $slug .= ($i == 1 ? '' : '-' . $i))->count();
				$i++;
			}while(!empty($data));


			Input::merge(array('slug' => $slug));
		}

		$rules = array(
			'title' => 'required',
			'slug' => 'unique:pages'
		);
	
		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails())
		{
			return Redirect::to(url('admin/page/create'))->withErrors($validator)->withInput();
		}


		$page = new Page();
		$page->title = Input::get('title');
		$page->content = Input::get('content');
		$page->slug = Input::get('slug');
		$page->save();

		Session::flash('message', array('success' => 'The page has been created.'));
		return Redirect::to(url('admin/page/' . $page->id . '/edit'));

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
			$page = Page::findOrFail($id);
		}
		catch(ModelNotFoundException $e)
		{
			Session::flash('message', array('danger' => 'Invalid Page.'));
			return Redirect::to(url('admin/page'));
		}
		$form = array('url' => url('admin/page/' . $id), 'method' => 'PUT');
		return View::make('admin.pages.form')->withPage($page)->withForm($form);
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
			$page = Page::findOrFail($id);
		}
		catch(ModelNotFoundException $e)
		{
			Session::flash('message', array('danger' => 'Invalid Page.'));
			return Redirect::to(url('admin/page'));
		}


		$slug = Str::slug(Input::get('slug'));
		Input::merge(array('slug' => $slug));

		$rules = array(
			'title' => 'required',
			'slug' => 'required|unique:pages,slug,' . $page->id,
		);

		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails())
		{
			return Redirect::to(url('admin/page/' . $id . '/edit'))->withErrors($validator)->withInput();
		}
		
		$page->title = Input::get('title');
		$page->content = Input::get('content');
		$page->slug = Input::get('slug');

		$page->save();

		Session::flash('message', array('success' => 'The page has been updated.'));
		return Redirect::to(url('admin/page/' . $page->id . '/edit'));
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
			$page = Page::findOrFail($id);
		}
		catch(ModelNotFoundException $e)
		{
			Session::flash('message', array('danger' => 'Invalid Page.'));
			return Redirect::to(url('admin/page'));
		}
		$page->delete();

		Session::flash('message', array('success' => 'The page has been removed.'));
		return Redirect::to(url('admin/page'));
	}


}
