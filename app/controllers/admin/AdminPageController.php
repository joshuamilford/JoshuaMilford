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
		$sitemap = $this->get_sitemap();
		// echo '<pre>';
		// print_r($this->get_sitemap_array());
		// echo '</pre>';
		return View::make('admin.pages.index')->withPages($pages)->withSitemap($sitemap);
	}

	public function get_sitemap($parent = 0, $return = '')
	{
		$return .= '<ul>';
		$data = Page::where('parent_id', '=', $parent)->get();
		foreach($data as $d)
		{
			$return .= '<li>' . $d->title . ' has ' . $this->count_children($d->id) . ' children.' . $this->get_sitemap($d->id) . '</li>';
		}
		$return .= '</ul>';
		return $return;
	}

	public function count_children($parent, &$count = 0)
	{
		$data = Page::where('parent_id', '=', $parent)->get();
		$count += count($data);
		foreach($data as $d)
		{
			$this->count_children($d->id, $count);
		}
		return $count;
	}

	public function get_sitemap_array($parent = 0, $return = array())
	{
		$data = Page::where('parent_id', '=', $parent)->get();
		$i = 0;
		foreach($data as $d)
		{
			$return[$d->id] = array('title' => $d->title);
			$return[$d->id]['children'] = $this->get_sitemap_array($d->id);
			$i += count($return[$d->id]['children']);
		}
		return $return;
	}

	// public function count_sitemap_array($array, $return = array())
	// {
	// 	foreach($array as $k=>$a)
	// 	{

	// 	}
	// 	return $array;
	// }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$page = new Page();
		$form = array('url' => url('admin/page'));
		$categories = Category::all();
		$page_cats = array();
		$parents = Page::all()->lists('title', 'id');
		return View::make('admin.pages.form')->withPage($page)->withForm($form)->withCategories($categories)->withPage_cats($page_cats)->withParents($parents);
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
			$parent = '';
			if(Input::get('parent_id'))
			{
				$data = Page::where('id', '=', Input::get('parent_id'))->first();
				$parent = $data->slug . '/';
			}
			$pre_slug = $parent . Str::slug(Input::get('title'));
			$i = 1;
			do
			{ 
				$slug = $pre_slug . ($i == 1 ? '' : '-' . $i);
				$data = Page::where('slug', '=', $slug)->count();
				$i++;
			}while(!empty($data));


			Input::merge(array('slug' => $slug));
		}

		$rules = array(
			'title' => 'required',
			'slug' => 'unique:pages'
		);
	
		$page_cats = Input::get('categories');

		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails())
		{
			$categories = Category::all();
			return Redirect::to(url('admin/page/create'))->withErrors($validator)->withInput(Input::except('slug'))->withCategories($categories)->withPage_cats($page_cats);
		}


		$page = new Page();
		$page->title = Input::get('title');
		$page->content = Input::get('content');
		$page->slug = Input::get('slug');
		$page->parent_id = Input::get('parent_id');
		$page->save();

		$page->categories()->sync(Input::has('categories') ? Input::get('categories') : array());

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
		$categories = Category::all();
		$page_cats = $page->categories()->lists('category_id');
		$parents = Page::all()->lists('title', 'id');
		$form = array('url' => url('admin/page/' . $id), 'method' => 'PUT');
		return View::make('admin.pages.form')->withPage($page)->withForm($form)->withCategories($categories)->withPage_cats($page_cats)->withParents($parents);
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

		$page_cats = Input::get('categories');

		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails())
		{
			$categories = Category::all();
			return Redirect::to(url('admin/page/' . $id . '/edit'))->withErrors($validator)->withInput()->withCategories($categories)->withPage_cats($page_cats);
		}
		
		$page->title = Input::get('title');
		$page->content = Input::get('content');
		$page->slug = Input::get('slug');
		$page->parent_id = Input::get('parent_id');

		$page->categories()->sync(Input::has('categories') ? Input::get('categories') : array());

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

		$page->categories()->detach();


		Session::flash('message', array('success' => 'The page has been removed.'));
		return Redirect::to(url('admin/page'));
	}


}
