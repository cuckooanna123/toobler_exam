<?php 
class Question extends Eloquent{

	public $timestamps = false;

	public static $rules = array(
	'category'=>'required',
    'language'=>'required',
    'question'=>'required|min:4',
    'qtype'=>'required'
    );


	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = 'questions';
	
}