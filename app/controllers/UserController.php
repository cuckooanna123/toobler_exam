<?php 

class UserController extends BaseController
{

  public function __construct() {
	//$this->beforeFilter('csrf', array('on'=>'post'));	
	$this->beforeFilter('login_check', array('except' => array('getLogin','postSignin')));
    }

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

     // function to clear session on time out
    public function postClearsession(){
        Session::forget('userType');
        Session::forget('userId'); 
        Session::forget('isFinished'); 
        return Response ::json(array("status"=>true));
    }

    public function getStartpage(){
        if(Session::has('isFinished')){
             $this->layout->content = View::make('users.finished');
        }else{   
        $this->layout->content = View::make('users.start');
        }
    }
    /**
    * function to load exam page
    */
    public function getExamPage($uid = ""){

        if(!Session::has('isFinished')){

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
            $response = array();
            if(!empty($examQuestions)){
            $firstQst = $examQuestions[0];
            $current_qid = $firstQst['id'];
            

             $responseData = ExamData::where('userid', '=', $uid)
                ->where('qid', '=', $current_qid)
                ->first();
                if($responseData != ''){
                $refl4 = new ReflectionObject($responseData);
                $prop4 = $refl4->getProperty('attributes');
                $prop4->setAccessible(true);
                $response = $prop4->getValue($responseData);
            }
        }
                /*echo "<pre>";
                print_r($response);exit;*/

           
       $this->layout->content = View::make('users.exam',
        array('user'=>$user,'queslist'=> $examQuestions,
            'settings'=>$settings,'response'=>$response)); 
   }else{
        return Redirect::to('users/start')->with('message', 'Action not allowed!');
   }
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
        $user_id = Input::get('uid');

        $qIndex = (int)Input::get('next_count')-1; 
        $qid = Input::get('qid');
        $qtype = Input::get('qtype');
        $des_ans = Input::get('des_ans');
        $obj_ans = Input::get('obj_ans');

        // to save response
        $answer_data = array();
        $answer_data['lang_id'] = $lang_id;
        $answer_data['cat_id'] = $cat_id;
        $answer_data['user_id'] = $user_id;
        $answer_data['qid'] = $qid;
        $answer_data['des_ans'] = $des_ans;
        $answer_data['obj_ans'] = $obj_ans;
        $answer_data['qtype'] = $qtype;
        $status = $this->saveAnswer($answer_data);

        // fetching next question from list of questions of spec language
       $examQuestions = $current_qs = array();

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

            // fetch response data if previously saved.
            if(!empty($current_qs)){

                $current_qid = $current_qs['id'];
                $response = array();
                $responseData = ExamData::where('userid', '=', $user_id)
                ->where('qid', '=', $current_qid)
                ->first();

                if($responseData != ''){
                $refl4 = new ReflectionObject($responseData);
                $prop4 = $refl4->getProperty('attributes');
                $prop4->setAccessible(true);
                $response = $prop4->getValue($responseData);
                }

            return Response ::json(array("status"=>true,'question'=>$current_qs,
                'response'=>$response));
            }else{
                return Redirect::to('users/exam/'.$user_id)->with('message', 'No more Questions!');
            }

    }

    /*
    * function to save final question before finish action
    */
    public function postSavefinish(){


        $lang_id = Input::get('l_id');
        $cat_id = Input::get('catId');
        $user_id = Input::get('uid');

        $qid = Input::get('qid');
        $qtype = Input::get('qtype');
        $des_ans = Input::get('des_ans');
        $obj_ans = Input::get('obj_ans');

        // to save response
        $answer_data = array();
        $answer_data['lang_id'] = $lang_id;
        $answer_data['cat_id'] = $cat_id;
        $answer_data['user_id'] = $user_id;
        $answer_data['qid'] = $qid;
        $answer_data['des_ans'] = $des_ans;
        $answer_data['obj_ans'] = $obj_ans;
        $answer_data['qtype'] = $qtype;
        $status = $this->saveAnswer($answer_data);
        if($status){
            Session::put('isFinished', true);
        return Response ::json(array("status"=>true));
        }else{
        return Response ::json(array("status"=>false)); 
        }

    }
    /**
    * function to save user response to exam_data table.
    * @author ans
    */
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
        if($savedItem == null){ // save new entry
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
        }else{ // update entry
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
     /**
     * function  to check whether user's response for a question saved previously.
     * @author ans
     */
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


     public function postprocessData(){
        $lang_id = Input::get('l_id');
        $cat_id = Input::get('catId');
        $user_id = Input::get('uid');
        $total_qs_count = Input::get('total_qs_count');

        $des_count = 0;
        $correct_count = 0;
        $wrong_count = 0;
        
        $exam_data = ExamData::where('userid', '=', (int)$user_id)
                ->where('category', '=', (int)$cat_id)
                ->where('language', '=', (int)$lang_id)
                ->get();

            if($exam_data != ''){
                foreach ($exam_data as $exdata) {

                    $refl4 = new ReflectionObject($exdata);
                    $prop4 = $refl4->getProperty('attributes');
                    $prop4->setAccessible(true);
                    $response = $prop4->getValue($exdata);
                   // print_r($response);

                    if($response['answer_status'] == 2){
                        $des_count += 1;
                    }else if($response['answer_status'] == 1){
                        $correct_count += 1;
                    }else if($response['answer_status'] == 0){
                        $wrong_count += 1;
                    }
                }
                    $prev_result = $this->isResultSaved($user_id,$lang_id,$cat_id);
                    if($prev_result != null){

                    $prev_result->language_id = $lang_id;
                    $prev_result->user_id = $user_id;
                    $prev_result->category_id = $cat_id;
                    $prev_result->total_qs_count = $total_qs_count;

                    $prev_result->correct_count = $correct_count;
                    $prev_result->wrong_count = $wrong_count;
                    $prev_result->descriptive_count = $des_count;
                    $prev_result->total_marks = $correct_count;
                    $stat = $prev_result->save();
                    }else{

                    $result = new ExamResult;
                    $result->language_id = $lang_id;
                    $result->user_id = $user_id;
                    $result->category_id = $cat_id;
                    $result->total_qs_count = $total_qs_count;

                    $result->correct_count = $correct_count;
                    $result->wrong_count = $wrong_count;
                    $result->descriptive_count = $des_count;
                    $result->total_marks = $correct_count;
                    $stat = $result->save();
                    }
                    if($stat){
                    return Response ::json(array("status"=>true,'correct'=>$correct_count,
                    'wrong'=>$wrong_count,'des_count'=>$des_count));
                    }else{
                        return Response ::json(array("status"=>false));
                    }
            }

        }

         /**
     * function  to check whether result saved previously.
     * @author ans
     */
     public function isResultSaved($uid="",$lid="",$cid=""){
        $resultList = ExamResult::where('user_id', '=', $uid)
                ->where('language_id', '=', $lid)
                ->where('category_id', '=', $cid)
                ->first();
                if(count($resultList) != 0){
                    return $resultList;
                }else{
                    return null;
                }
                
     }


}