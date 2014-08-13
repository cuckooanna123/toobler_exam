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

    /**
    * function to save current answer and return next question
    * @author ans
    */
    public function postNextques(){

        $lang_id = Input::get('l_id');
        $cat_id = Input::get('catId');
        $qIndex = (int)Input::get('next_count')-1;
        $user_id = Input::get('uid');
        $qid = Input::get('qid');
        $qtype = Input::get('qtype');
        $des_ans = Input::get('des_ans');
        $obj_ans = Input::get('obj_ans');

        $answer_data = array();
        $answer_data['lang_id'] = $lang_id;
        $answer_data['cat_id'] = $cat_id;
        $answer_data['user_id'] = $user_id;
        $answer_data['qid'] = $qid;
        $answer_data['des_ans'] = $des_ans;
        $answer_data['obj_ans'] = $obj_ans;
        $answer_data['qtype'] = $qtype;
        $statu = $this->saveAnswer($answer_data);

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

    public function saveAnswer($data = array()){
       // print_r($data);
        $answerStatus ="";
        $uid = $data['user_id'];
        $qid = $data['qid'];
        $qtype = $data['qtype'];
        $obj_ans = $data['obj_ans'];
        if($qtype == 0){
            $answerStatus = 2;
        }else if($qtype == 1){
            
            $qstDet = Question::find($qid);
            
                    $refl3 = new ReflectionObject($qstDet);
                    $prop3 = $refl3->getProperty('attributes');
                    $prop3->setAccessible(true);
                    $question = $prop3->getValue($qstDet);
                    //print_r($question);exit;
                    if($question['answerOption'] == $obj_ans){
                        $answerStatus = 1;
                    }else{
                     $answerStatus = 0;   
                    }
             }
             //echo "stat:".$answerStatus;exit;
        $savedItem = $this->isAnswerSaved($uid,$qid);
        if($savedItem == null){
            $dataItem = new ExamData;
            $dataItem->userid = $uid;
            $dataItem->qid = $qid;
            $dataItem->answer_option = $obj_ans;
            $dataItem->answer_descriptive = $data['des_ans'];
            $dataItem->category = $data['cat_id'];
            $dataItem->language = $data['lang_id'];
            $dataItem->answer_status = $answerStatus;
            $status = $dataItem->save();
            return $status;
        }else{
            $savedItem->userid = $uid;
            $savedItem->qid = $qid;
            $savedItem->answer_option = $obj_ans;
            $savedItem->answer_descriptive = $data['des_ans'];
            $savedItem->category = $data['cat_id'];
            $savedItem->language = $data['lang_id'];
            $savedItem->answer_status = $answerStatus;
            $status = $savedItem->save();
            return $status;
        }
        
     }

     public function isAnswerSaved($uid="",$qid=""){
        $answerList = ExamData::where('userid', '=', $uid)
                ->where('qid', '=', $qid)
                ->first();
                if(count($answerList) != 0){
                    return $answerList;
                }else{
                    return null;
                }
                
     }


}