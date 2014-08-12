<?php 
class Language extends Eloquent{

	public $timestamps = false;

	public static $rules = array(
	'category'=>'required',
    'language'=>'required|min:2'
    );


	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = 'languages';
	
}