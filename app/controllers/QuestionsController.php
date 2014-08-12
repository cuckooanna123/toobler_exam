<?php 

class QuestionsController extends AdminbaseController
{

	public function __construct() {
		//$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('admin_check', array('except' => array('index')));	
		$this->beforeFilter('login_check', array('except' => array('index')));
	    }

	    protected $layout = "layouts.main";

	    //function to load create question view
	    public function addQuestion(){
	    	$categories = Category::lists('category', 'id');
	    	$categories[0] = "select category";
	    	
	    	
	 		$this->layout->content = View::make('questions.add',
	 		array("categories"=> $categories));
	    }

	    public function editQuestion($id){

	    	$categories = Category::lists('category', 'id');
		    $categories[0] = "select category";
	
	    	$qstDet = Question::find($id);
	 		if($qstDet != ''){
	 			
					$refl2 = new ReflectionObject($qstDet);
					$prop2 = $refl2->getProperty('attributes');
					$prop2->setAccessible(true);
					$quest = $prop2->getValue($qstDet);

					$languages = Language::where('categoryId',$quest['categoryId'])
		    		->lists('language', 'id');

		 		$this->layout->content = View::make('questions.edit',
		 		array("categories"=> $categories,'question'=>$quest,
		 			'languages'=>$languages));
	 		}else{
	 			echo "No such question";
	 			return;
	 			//$this->layout->content = View::make('questions.edit',array("categories"=> $catList))->with('message', 'Question not found!');
	 		}
	    }

	    /**
	    * function to save a new question with answer options and correct answer
	    * @author ans
	    */
	    public function saveQuestion(){
	    	//var_dump(Input::all());
	    	$validator = Validator::make(Input::all(), Question::$rules);

	    	if ($validator->passes()) {
	       // var_dump(Input::all());

	  		$qtypeString = Input::get('qtype');
	  		if($qtypeString == "objective"){

	  			$qtype = 1;
	  			$qanswer = Input::get('answer');
	  		}else if($qtypeString == "descriptive"){

	  			$qtype = 0;
	  			$qanswer = Input::get('descriptive_answer');
	  		}

			$question = new Question;
			$question->categoryId = (int)Input::get('category');
	        $question->languageId = (int)Input::get('language');
	        $question->question = Input::get('question');
	        $question->questionType = $qtype;
	        $question->answerOption = $qanswer;
	        if($qtype == 1){
	        	$question->option1 = Input::get('option1');
	        	$question->option2 = Input::get('option2');
	        	$question->option3 = Input::get('option3');
	        	$question->option4 = Input::get('option4');
	        }

	        $question->save();


	        return Redirect::to('questions/add')->with('message', 'Question added successfully!');
	    }else {
	        // validation has failed, display error messages
	         return Redirect::to('questions/add')->with('message', 'The following errors occurred')->withErrors($validator)->withInput();

	    }   
	    }
	    /**
	    *function to save question edit
	    * @author ans
	    */
	    public function questionUpdate(){
	    	
	    	$qs_id = Input::get('qs_id');
	    	$validator = Validator::make(Input::all(), Question::$rules);

	    	if ($validator->passes()) {
	       // var_dump(Input::all());

	  		$qtypeString = Input::get('qtype');
	  		if($qtypeString == "objective"){

	  			$qtype = 1;
	  			$qanswer = Input::get('answer');
	  		}else if($qtypeString == "descriptive"){

	  			$qtype = 0;
	  			$qanswer = Input::get('descriptive_answer');
	  		}else{
	  			$qtype = $qtypeString;
	  			$qanswer = Input::get('answer');
	  			if($qanswer == ''){
	  			$qanswer = Input::get('descriptive_answer');
	  			}
	  		}
	  		/*echo "type:".$qtype."<br>";
	  		echo "ans:".$qanswer; exit;*/
	  		$question = Question::find($qs_id);
	  		if($question !=''){

	  		$question->categoryId = (int)Input::get('category');
	        $question->languageId = (int)Input::get('language');
	        $question->question = Input::get('question');
	        $question->questionType = $qtype;
	        $question->answerOption = $qanswer;
	        if($qtype == 1){
	        	$question->option1 = Input::get('option1');
	        	$question->option2 = Input::get('option2');
	        	$question->option3 = Input::get('option3');
	        	$question->option4 = Input::get('option4');
	        }else{
	        	$question->option1 = "";
	        	$question->option2 = "";
	        	$question->option3 = "";
	        	$question->option4 = "";
	        }

	        $question->save();
	        return Redirect::to('questions/edit/'.$qs_id)->with('message', 'Question updated sucessfully!');
	  		}else{
	  			return Redirect::to('questions/edit/'.$qs_id)->with('message', 'No such language exist!');
	  		}


	    }
	}
		/**
		* function to list questions w.r.t language, filtered w.r.t qtype
		* @author ans
		*/
	    public function getList($lang_id){

	    	$qs_type = 1;
	    	if(isset($_GET['type'])){
	    	$qs_type = $_GET['type']; 
	    	}

	    	if($lang_id != ''&& $qs_type != ''){
	    		$qsList = Question::where('languageId', '=', $lang_id)
	    		->where('questionType', '=', $qs_type)
	    		->paginate(2);

	    		
	    		//fetch details of parent lanuage and category
	    		$langDet = Language::find($lang_id);
	 		if($langDet != ''){
	    			$refl4 = new ReflectionObject($langDet);
					$prop4 = $refl4->getProperty('attributes');
					$prop4->setAccessible(true);
					$language = $prop4->getValue($langDet);

					// fetch category name from db
					$catId = (int)$language['categoryId'];
					$CategoryDet = Category::find($catId);
				
					$refl1 = new ReflectionObject($CategoryDet);
					$prop1 = $refl1->getProperty('attributes');
					$prop1->setAccessible(true);
					$cat = $prop1->getValue($CategoryDet);
					
					$CategoryName = $cat['category'];
			 		$language['CategoryName'] = $CategoryName;
					}else{
						return Redirect::to('languages/list')->with('message', 'No such language exist!');
						
					}


	    		$quesArray = array();
	    		foreach ($qsList as $qst) {

	    			$refl3 = new ReflectionObject($qst);
		            $prop3 = $refl3->getProperty('attributes');
		            $prop3->setAccessible(true);
		            $qst_item = $prop3->getValue($qst);
		            array_push($quesArray, $qst_item);
	    		}

	    		
	    		$this->layout->content = View::make('questions.list',
	    			array('questions'=> $quesArray,'language'=> $language,
	    				'qsList'=>$qsList));
	    }else{
						return Redirect::to('languages/list')->with('message', 'No Questions found!');
						
					}
	    }

	    public function deleteQuestion(){

	    	$qs_id = Input::get('id');
	    	$lang_id = Input::get('lid');
    	if($qs_id != ''){
	    	$question = Question::find($qs_id);
	    	if($question != ''){
				$question->delete();
				//return Redirect::to('languages/list')->with('message', 'Language deleted successfully!');
				return Response ::json(array("status"=>true,'msg'=>"deleted question  successfully!"));
			}else{
				return Redirect::to('questions/list/'.$lang_id)->with('message', 'Question does not  exist!');
			}
		}else{
			return Redirect::to('questions/list/'.$lang_id)->with('message', 'delete failed!');
		}

	    }

	    // function to return all lanuages in a category
	    public function fetchLanguages(){
	    	$cat_id = Input::get('cat_id');
    	if($cat_id != ''){
    		$langList = Language::where('categoryId', '=', $cat_id)->get();

    		$LangArray = array();
    		foreach ($langList as $lang) {

    		$refl = new ReflectionObject($lang);
            $prop = $refl->getProperty('attributes');
            $prop->setAccessible(true);
            $lang_item = $prop->getValue($lang);
            array_push($LangArray, $lang_item);
    		}
    		return Response ::json(array("status"=>true,'languages'=>$LangArray));
	    }else{
	    	return Redirect::to('questions/add')->with('message', 'No language stored under this category!');
	    }
}

}
?>