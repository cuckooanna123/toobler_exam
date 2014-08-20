<?php 

class DocumentsController extends BaseController
{

protected $layout = "layouts.main";

/**
* function to display users list with exam result
*/
public function resultPage(){
	$users = User::where('userType', '=', "user")
        ->paginate(2);

        $userArray = array();
             foreach ($users as $user) {
                $refl5 = new ReflectionObject($user);
                $prop5 = $refl5->getProperty('attributes');
                $prop5->setAccessible(true);
                $user = $prop5->getValue($user);

            array_push($userArray,$user);
            }
        $this->layout->content = View::make('admin.results',array('userArray'=>$userArray,'users'=>$users));    
}

/**
* function display users exam result
*/
public function resultDetails($uid = ""){

	$res_data = ExamResult::where('user_id', '=', $uid)->first();

	 
		if($res_data != null){
		 $refl1 = new ReflectionObject($res_data);
         $prop1 = $refl1->getProperty('attributes');
         $prop1->setAccessible(true);
         $result = $prop1->getValue($res_data);
         

         // fetching name of saved category and language
                $language1 = Language::where('id',$result['language_id'])
                ->lists('language');
                if(!empty($language1)){
                $result['langName'] = $language1[0] ;
                }
                $category1 = Category::where('id',$result['category_id'])
                ->lists('category');
                if(!empty($category1)){
                $result['categoryName']= $category1[0];
                 }
                 $user1 = User::where('id',$result['user_id'])
                ->lists('fullname');
                if(!empty($user1)){
                $result['fullname'] = $user1[0] ;
                }

                /* echo "<pre>";
         print_r($result);exit;*/

         $this->layout->content = View::make('admin.resdetails',array('result'=>$result));
     }else{
     	return Redirect::to('result/list')->with('message', 'No exam results found !');
     }
         	   

}

/**
* function to download result pdf for each user
* @author ans
*/

public function downloadFile($uid ="") {

	$res_data = ExamResult::where('user_id', '=', $uid)->first();

	 
		if($res_data != null){
		 $refl1 = new ReflectionObject($res_data);
         $prop1 = $refl1->getProperty('attributes');
         $prop1->setAccessible(true);
         $result = $prop1->getValue($res_data);
         

         // fetching name of saved category and language
                $language1 = Language::where('id',$result['language_id'])
                ->lists('language');
                if(!empty($language1)){
                $result['langName'] = $language1[0] ;
                }
                $category1 = Category::where('id',$result['category_id'])
                ->lists('category');
                if(!empty($category1)){
                $result['categoryName']= $category1[0];
                 }
                 $user1 = User::where('id',$result['user_id'])
                ->lists('fullname');
                if(!empty($user1)){
                $result['fullname'] = $user1[0] ;
                }


 	$html = '<html><body>'
    	.'<h3>Result Details</h3>'
		.'<div class="container-fluid span12 make-grid">'
	 	.'<table class="table table-striped ">'
		.'<tr><th>Name:</th><td>'.$result['fullname'].'</td></tr>'
		.'<tr><th>Language: </th><td>'.$result['langName'].'</td></tr>'
		.'<tr><th>Category:</th><td>'.$result['categoryName'].'</td>'
		.'</tr><tr><th>Question count:</th><td>'.$result['total_qs_count'].'</td></tr>'
		.'<tr><th>Number of correct answers:</th><td>'.$result['correct_count'].'</td></tr>'
		.'<tr><th>Number of wrong answers:</th><td>'.$result['wrong_count'].'</td></tr>'
		.'<tr><th>Total Marks:</th><td>'.$result['total_marks'].'</td></tr>';
	if($result['descriptive_count']>0)
	$html .='<tr><th>Descriptive answer count:</th><td>'.$result['descriptive_count'].'</td></tr>';
	$html .='</table></div>'
            . '</body></html>';
        }else{
        	$html = '<html><body>'
    		.'<h3>No data Found</h3>'
            . '<p>No exam result found for this user</p>'
            . '</body></html>';
        }
            return PDF::load($html, 'A4', 'portrait')->download('exam_result');
    //return PDF::load(View::make('layouts.test'), 'A4', 'portrait')->download('my_tbl');
		
}
/**
* function to view result pdf for each user
* @author ans
*/
public function showPdf($uid = ''){
   $res_data = ExamResult::where('user_id', '=', $uid)->first();

	 
		if($res_data != null){
		 $refl1 = new ReflectionObject($res_data);
         $prop1 = $refl1->getProperty('attributes');
         $prop1->setAccessible(true);
         $result = $prop1->getValue($res_data);
         

         // fetching name of saved category and language
                $language1 = Language::where('id',$result['language_id'])
                ->lists('language');
                if(!empty($language1)){
                $result['langName'] = $language1[0] ;
                }
                $category1 = Category::where('id',$result['category_id'])
                ->lists('category');
                if(!empty($category1)){
                $result['categoryName']= $category1[0];
                 }
                 $user1 = User::where('id',$result['user_id'])
                ->lists('fullname');
                if(!empty($user1)){
                $result['fullname'] = $user1[0] ;
                }


 	$html = '<html><body>'
    	.'<h3>Result Details</h3>'
		.'<div class="container-fluid span12 make-grid">'
	 	.'<table class="table table-striped ">'
		.'<tr><th>Name:</th><td>'.$result['fullname'].'</td></tr>'
		.'<tr><th>Language: </th><td>'.$result['langName'].'</td></tr>'
		.'<tr><th>Category:</th><td>'.$result['categoryName'].'</td>'
		.'</tr><tr><th>Question count:</th><td>'.$result['total_qs_count'].'</td></tr>'
		.'<tr><th>Number of correct answers:</th><td>'.$result['correct_count'].'</td></tr>'
		.'<tr><th>Number of wrong answers:</th><td>'.$result['wrong_count'].'</td></tr>'
		.'<tr><th>Total Marks:</th><td>'.$result['total_marks'].'</td></tr>';
	if($result['descriptive_count']>0)
	$html .='<tr><th>Descriptive answer count:</th><td>'.$result['descriptive_count'].'</td></tr>';
	$html .='</table></div>'
            . '</body></html>';
        }else{
        	$html = '<html><body>'
    		.'<h3>No data Found</h3>'
            . '<p>No exam result found for this user</p>'
            . '</body></html>';
        }
         return PDF::load($html, 'A4', 'portrait')->show();


}
	
public function resultMail(){

    $emailArray = Input::get('emailArray');
    $uid = Input::get('uid');
    

    $res_data = ExamResult::where('user_id', '=', $uid)->first();

    if($res_data != null){
         $refl1 = new ReflectionObject($res_data);
         $prop1 = $refl1->getProperty('attributes');
         $prop1->setAccessible(true);
         $result = $prop1->getValue($res_data);
         

         // fetching name of saved category and language
                $language1 = Language::where('id',$result['language_id'])
                ->lists('language');
                if(!empty($language1)){
                $result['langName'] = $language1[0] ;
                }
                $category1 = Category::where('id',$result['category_id'])
                ->lists('category');
                if(!empty($category1)){
                $result['categoryName']= $category1[0];
                 }
                 $user1 = User::where('id',$result['user_id'])
                ->lists('fullname');
                if(!empty($user1)){
                $result['fullname'] = $user1[0] ;
                }

             
            $subject = "Exam Result";

            // smtp mailing function

            foreach ($emailArray as $email) {
                
                Mail::send('admin.resultmail',array('result'=>$result), function($message) use ($email,$subject)
                {
                    $message->to($email, 'you')->subject($subject);
                });
            }
           
          
           return Response ::json(array("status"=>true));

}

}

}
