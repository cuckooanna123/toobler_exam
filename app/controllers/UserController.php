<?php 

class UserController extends BaseController
{


	/*public function __construct() {
	//$this->beforeFilter('csrf', array('on'=>'post'));
	$this->beforeFilter('admin_check', array('except' => array('index')));	
	$this->beforeFilter('login_check', array('except' => array('index')));
    }*/

    protected $layout = "layouts.main";

    // load login view
    public function getLogin(){
    	$this->layout->content = View::make('users.login');
    }

    /*
    * function to sign in user
    */
    public function postSignin(){
    	$email = Input::get('useremail');
    	$userDet = User :: where('email',$email)
    	->where('enable',1)
    	->lists('id','userType');
        $key = "";
        if(!empty($userDet)){
        $key_array = array_keys($userDet);
        $key = $key_array[0]; 
        }
    	if(count($userDet)>0){
            Session::put('userId', $userDet[$key]);
            Session::put('userType', $key);
    		return Redirect::to('users/start');
        
    	}else{
    		return Redirect::to('users/login')
        ->with('message', 'Invalid login!');
    	}
    }

    public function getStartpage(){
    	$this->layout->content = View::make('users.start');
    }

    public function getExamPage($uid = ""){
        $userDet = User::find($uid);
        if($userDet != ''){
         $refl1 = new ReflectionObject($userDet);
         $prop1 = $refl1->getProperty('attributes');
         $prop1->setAccessible(true);
         $user = $prop1->getValue($userDet);
      
         // fetching name of saved category and language
                $language1 = Language::where('id',$user['language'])
                ->lists('language');
                if(!empty($language1)){
                $user['langName'] = $language1[0] ;
                }
                $category1 = Category::where('id',$user['category'])
                ->lists('category');
                if(!empty($category1)){
                $user['categoryName']= $category1[0];
                 }

                 $examQuestions = array();

                 $qsList = Question::where('languageId', '=', $user['language'])
                ->get();
                
                if($qsList != ''){
                    foreach ($qsList as $ques) {
                    $refl2 = new ReflectionObject($ques);
                    $prop2 = $refl2->getProperty('attributes');
                    $prop2->setAccessible(true);
                    $question = $prop2->getValue($ques);
                    array_push($examQuestions, $question);
                    }
                    
            }

            $settings = GeneralSetting ::lists('value','type');
           
       $this->layout->content = View::make('users.exam',
        array('user'=>$user,'queslist'=> $examQuestions,'settings'=>$settings)); 
   }else{
        return Redirect::to('users/start')->with('message', 'Action not allowed!');
   }
    }

    public function postNextques(){
        $lang_id = Input::get('l_id');
        $qIndex = (int)Input::get('next_count')-1;
        $user_id = Input::get('uid');

        $examQuestions = array();

                 $qsList = Question::where('languageId', '=', $lang_id)
                ->get();
                
                if($qsList != ''){
                    foreach ($qsList as $ques) {
                    $refl2 = new ReflectionObject($ques);
                    $prop2 = $refl2->getProperty('attributes');
                    $prop2->setAccessible(true);
                    $question = $prop2->getValue($ques);
                    array_push($examQuestions, $question);
                    }
                }

            $current_qs = $examQuestions[$qIndex];
            if(!empty($current_qs)){
            return Response ::json(array("status"=>true,'question'=>$current_qs));
            }else{
                return Redirect::to('users/exam/'.$user_id)->with('message', 'No more Questions!');
            }

    }


}