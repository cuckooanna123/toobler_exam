<?php 

class LanguagesController extends AdminbaseController
{


	public function __construct() {
	//$this->beforeFilter('csrf', array('on'=>'post'));
	$this->beforeFilter('admin_check', array('except' => array('index')));	
	$this->beforeFilter('login_check', array('except' => array('index')));
    }

   
    

    protected $layout = "layouts.main";

     /**
     * function generates languages list
     * @author ans 
     */
     public function showIndex()
		{
			// login check
			// return $this->loginCheck();

			 $languages = Language::paginate(5);
			 $languageArray = array();
			 foreach ($languages as $language) {
			 	$refl = new ReflectionObject($language);
				$prop = $refl->getProperty('attributes');
				$prop->setAccessible(true);
				$lang = $prop->getValue($language);
				
				// fetch category name from db
				$catId = (int)$lang['categoryId'];
				$CategoryDet = Category::find($catId);
				
					$refl1 = new ReflectionObject($CategoryDet);
					$prop1 = $refl1->getProperty('attributes');
					$prop1->setAccessible(true);
					$cat = $prop1->getValue($CategoryDet);
					
				
				$CategoryName = $cat['category'];
			 	$lang['CategoryName'] = $CategoryName;
			 	//print_r($lang);
			 	array_push($languageArray, $lang);
			 }
			 
		$this->layout->content = View::make('languages.index',array('lang'=>$languages))
		->with('languages',$languageArray);;
		}


	/**
	* function to add a new language
	*@author ans
	*
	*/
	 public function getCreate(){
	 	$categories = Category::lists('category', 'id');
	 	$this->layout->content = View::make('languages.create',
	 	array("categories"=> $categories));
	 }

	 /**
     * function generates edit form for language
     * @author ans 
     */
	 public function getEdit($id){
	 	
	 		$langDet = Language::find($id);
	 		if($langDet != ''){
	 			
					$refl2 = new ReflectionObject($langDet);
					$prop2 = $refl2->getProperty('attributes');
					$prop2->setAccessible(true);
					$language = $prop2->getValue($langDet);
					
			 	$categories = Category::lists('category', 'id');
			 	$this->layout->content = View::make('languages.edit',
			 		array("categories"=> $categories,"language"=> $language));
	 		}else{
	 			return Redirect::to('languages/list')->with('message', 'Invalid language id provided!');
	 		}
	}

	 /**
	 *function to add a new language under a category
	 * @author ans
	 */
	 public function addLanguage(){

		$validator = Validator::make(Input::all(), Language::$rules);
	     
	    if ($validator->passes()) {
	       // var_dump(Input::all());
	        $language = new Language;
	        $language->categoryId = (int)Input::get('category');
	        $language->language = Input::get('language');
	        $language->save();

	        return Redirect::to('languages/list')->with('message', 'Language added successfully!');
	    }else {
	        // validation has failed, display error messages
	         return Redirect::to('languages/create')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();

	    }   

    }

     /**
	 *function to save changes in lanuage
	 * @author ans
	 */
    public function updateLanguage(){

    	$validator = Validator::make(Input::all(), Language::$rules);

    	//var_dump(Input::all()); exit;
	    $lang_id = Input::get('lang_id');
    	if ($validator->passes() && $lang_id != '') {

	        $language = Language::find($lang_id);
	        $language->categoryId = (int)Input::get('category');
	        $language->language = Input::get('language');
	        $language->status = Input::get('status');
	        $language->save();

	        return Redirect::to('languages/edit/'.$lang_id)->with('message', 'Language updated successfully!');
	    }else {
	        // validation has failed, display error messages
	         return Redirect::to('languages/edit/'.$lang_id)->with('message', 'The following errors occurred')->withErrors($validator)->withInput();

	    } 
    }
    /**
	 *function to delete a lanuage
	 * @author ans
	 */
    public function deleteLanguage(){
    	$lang_id = Input::get('id');
    	if($lang_id != ''){
	    	$language = Language::find($lang_id);
	    	if($language != ''){
				$language->delete();
				//return Redirect::to('languages/list')->with('message', 'Language deleted successfully!');
				return Response ::json(array("status"=>true,'msg'=>"deleted language  successfully!"));
			}else{
				return Redirect::to('languages/list')->with('message', 'Language does not  exist!');
			}
		}else{
			return Redirect::to('languages/list')->with('message', 'delete failed!');
		}
    }

   

}