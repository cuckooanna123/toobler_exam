<?php
class GeneralSetting extends Eloquent{

	public $timestamps = false;

	public static $rules = array(
	'exam_time'=>'required|numeric'
    );


	/**
	 * The database table used by the model.
	 * @var string
	 */
	protected $table = 'general_settings';
	
}