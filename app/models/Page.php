<?php


class Page extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'pages';

	public function categories()
	{
		return $this->belongsToMany('Category')->withTimestamps();
	}

}
