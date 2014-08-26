<?php 
class AdminController extends AdminbaseController
{


    public function __construct() {

        $this->beforeFilter('login_check',
         array('except' => array('getLogin','postSignin','getForgot','postChange','getClearsession')));
        $this->beforeFilter('admin_check',
         array('except' => array('getLogin','postSignin','getForgot','postChange','getLogout','getClearsession')));
    }

    // loading main layout
    protected $layout = "layouts.main"; 

    // load login form
    public function getLogin() {
    $this->layout->content = View::make('admin.login');
    }

    /** 
    * function to check login
    * @author ans
    */
    public function postSignin() {
        //echo "test".Input::get('email'); exit;
        $validator = Validator::make(Input::all(), User::$rules);
     
        if ($validator->passes()) {
        $credentials  = array(
            'email'=>Input::get('email'),
            'password'=>Input::get('password')
            );
        if (Auth::attempt($credentials)) {
        	//echo "email:".Auth::user()->userType;exit;
        	Session::put('userId', Auth::user()->id);
            Session::put('userType', Auth::user()->userType);
        	//echo $type = Session::get('userType');exit;
            return Redirect::to('admin/dashboard')->with('message', 'You are now logged in!');
        } else {
            return Redirect::to('admin/login')
                ->with('message', 'Your username/password combination was incorrect')
                ->withInput();
        } 
        }else {
        // validation has failed, display error messages
         return Redirect::to('admin/login')->with('message', 'The following errors occurred')
         ->withErrors($validator)->withInput();

    }   
    }

    

    //dashboard view
    public function getDashboard() {
	$this->layout->content = View::make('admin.dashboard');
    }
    /**
    * function to load forgot password view
    */
    public function getForgot(){
        $this->layout->content = View::make('admin.forgot');
    }
    /**
    * function to save change in password
    * @author ans
    */
    public function postChange(){
        $validator1 = Validator::make(Input::all(), User::$chrules);
        
        if ($validator1->passes()) {
            //var_dump(Input::all());exit;
            $userDet = User::where('email', '=', Input::get('email'))->first();
            if($userDet == ''){
                return Redirect::to('admin/forgot')->with('message', 'no such user exist!');
            }else{
                    $refl2 = new ReflectionObject($userDet);
                    $prop2 = $refl2->getProperty('attributes');
                    $prop2->setAccessible(true);
                    $user = $prop2->getValue($userDet);
           
            $user = User::find($user['id']);
            $user->password = Hash::make(Input::get('password'));
            $user->save();
            return Redirect::to('admin/forgot')->with('message', 'Your password updated');
            }
        }else {

        // validation has failed, display error messages
         return Redirect::to('admin/forgot')->with('message', 'The following errors occurred')
         ->withErrors($validator1)->withInput();

        } 
    }

     /**
     * function to logout
     * @author ans 
     */
    public function getLogout($timeOut = false) {
     if(Session::get('userType') == 'admin'){
        $isAdmin = true;
     }else{
       $isAdmin = false; 
     }
     $timeOut = Input::get('timeOut');
     // to give diff message time out
     if($timeOut){
        $msg = "You have reached the allowed time limit and hence logged out!";
     }else{
        $msg = 'Your are now logged out!';
     }

    Session::forget('userType');
    Session::forget('userId');
    Session::forget('isFinished');
    Auth::logout();
    if($isAdmin){
       return Redirect::to('admin/login')->with('message', $msg); 
    }else{
        return Redirect::to('users/login')->with('message', $msg); 
    }
    
    }

   


    /**
    * display admin settings page
    */
    public function getSettings(){
       $allSettings = GeneralSetting::all();
       
        $settingsArray = array();
       foreach ($allSettings as $settings) {
                    $refl3 = new ReflectionObject($settings);
                    $prop3 = $refl3->getProperty('attributes');
                    $prop3->setAccessible(true);
                    $setting = $prop3->getValue($settings);
                    array_push($settingsArray, $setting);
                    
       }
      $this->layout->content = View::make('admin.settings')->with('settings',$settingsArray);
    }

    public function getSettingsadd(){
        $this->layout->content = View::make('admin.settingsForm');
    }

    public function postSettings(){

        $validator2 = Validator::make(Input::all(), GeneralSetting::$rules);

        if ($validator2->passes()) {

        $min = (int)Input::get('exam_time'); 
        $qcount = (int)Input::get('max_qs_count');

        $type_array = array('max_exam_time','max_ques_count');

        $hour = floor($min / 60);
        $min -= $hour * 60;
        $timeStamp = sprintf('%02d:%02d:00', $hour, $min); 

        $value_array = array($timeStamp,$qcount);

           for ($i=0; $i <count($type_array) ; $i++) { 
                // save settings
                $setting = new GeneralSetting;
                $setting->type = $type_array[$i];
                $setting->value = $value_array[$i];
                $setting->save();
           }
           
            return Redirect::to('admin/settings')->with('message', 'Exam time upadated successfully!');
        }else{
             return Redirect::to('admin/settings')->with('message', 'The following errors occurred')
                ->withErrors($validator2)->withInput();

        }
      
    }

    /**
    * function to edit settings
    */
     public function getSettingsedit(){

        $allSettings = GeneralSetting::all();

        //$item = GeneralSetting::find($id);
        if($allSettings != null){
            $settingsArray = array();
            foreach ($allSettings as $setting_item) {

            $refl4 = new ReflectionObject($setting_item);
            $prop4 = $refl4->getProperty('attributes');
            $prop4->setAccessible(true);
            $item = $prop4->getValue($setting_item);

                $item_type = $item['type'];
                if($item_type == 'max_exam_time'){
                $time_string = $item['value'];
                $timeArray =  explode(":",$time_string);
                $time_min= $timeArray[0]*60+$timeArray[1];

                $item['time_min'] = $time_min;
                }
                array_push($settingsArray,$item);

              }  
           /* echo "<pre>";
            print_r($settingsArray);exit;*/
            
           $this->layout->content = View::make('admin.settingEdit')->with('settings',$settingsArray);
        }else{
           return Redirect::to('admin/settings')->with('message', 'No such settings exist!');  
        }
    }

    /**
    * function to update general settings
    */
    public function postUpdatesettings(){
        
        $validator3 = Validator::make(Input::all(), GeneralSetting::$rules);

        if ($validator3->passes()) {

        $min = (int)Input::get('exam_time'); 
        $item_id1 = (int)Input::get('item_id1');
        $item_type1 = Input::get('item_type1');

        $qcount = (int)Input::get('max_qs_count'); 
        $item_id2 = (int)Input::get('item_id2');
        $item_type2 = Input::get('item_type2');

        $hour = floor($min / 60);
        $min -= $hour * 60;
        $timeStamp = sprintf('%02d:%02d:00', $hour, $min); 
       
            // save settings
            $item1 = GeneralSetting::find($item_id1);
            $item1->type = $item_type1;
            $item1->value = $timeStamp;
            $item1->save();

            // save settings
            $item2 = GeneralSetting::find($item_id2);
            $item2->type = $item_type2;
            $item2->value = $qcount;
            $item2->save();
        
            return Redirect::to('admin/settings')->with('message', 'Settings upadated successfully!');
        }else{
             return Redirect::to('admin/settings')->with('message', 'The following errors occurred')
                ->withErrors($validator3)->withInput();

        }
    }

    public function postDelete(){
        $settings_id = Input::get('id');
        if($settings_id != ''){
            $settings = GeneralSetting::find($settings_id);
            if($settings != null){
            $settings->delete();
            //return Redirect::to('languages/list')->with('message', 'Language deleted successfully!');
            return Response ::json(array("status"=>true,'msg'=>"deleted item successfully!"));
        }else{
           return Redirect::to('admin/settings')->with('message', 'Invalid settings id!'); 
        }
        }else{
            
            return Redirect::to('admin/settings')->with('message', 'Invalid settings id!'); 
        }
    }

    // function to load form to add user from admin side
    public function getNewuser(){

        $this->layout->content = View::make('admin.adduser');
    }

    /**
    * function  to save user added by admin
    */
    public function postSaveuser(){

    $validator4 = Validator::make(Input::all(), User::$userRules);
    $userMail = Input::get('useremail');
    
    if ($validator4->passes()) {
        $user = new User;
        $user->email = Input::get('useremail');
        $user->fullname = Input::get('fullname');
        $user->userType = "user";
        $user->save();

    return Redirect::to('admin/userlist')->with('message', 'User added succesfully!');
    }else{
        return Redirect::to('admin/newuser')->with('message', 'The following errors occurred')
                ->withErrors($validator4)->withInput(); 
    }
    }

    public function postEmailexist(){
        $userMail = Input::get('email');
        $userDet = User :: where('email',$userMail)
        ->lists('id','userType');
    if(count($userDet)>0){
        return Response ::json(array("status"=>true,'msg'=>"Email already exists!"));
    }else{
        return Response ::json(array("status"=>false));
    }
    }

    /* function to list all users*/
    public function getUserlist(){

        $users = User::where('userType', '=', "user")
        ->orderBy('id', 'DESC')
        ->paginate(2);

        $userArray = array();
             foreach ($users as $user) {
                $refl5 = new ReflectionObject($user);
                $prop5 = $refl5->getProperty('attributes');
                $prop5->setAccessible(true);
                $user = $prop5->getValue($user);

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
                
                array_push($userArray,$user);
            }
            $this->layout->content = View::make('admin.userlist',array('userArray'=>$userArray,'users'=>$users));
    }

    public function getEdituser($uid =""){
        //echo "<pre>";
        $userDet = User::find($uid);
        if($userDet != ''){
         $refl6 = new ReflectionObject($userDet);
         $prop6 = $refl6->getProperty('attributes');
         $prop6->setAccessible(true);
         $user = $prop6->getValue($userDet);
         

        $categories = Category::lists('category', 'id');
            $categories[0] = "select category";

        $languages = Language::where('categoryId',$user['category'])
        ->lists('language', 'id');

       /* echo "<pre>";
        print_r($languages);
        exit;*/
    
        $this->layout->content = View::make('admin.edituser',
            array('categories'=>$categories,'user'=>$user,'languages'=>$languages));
    }else{
        return Redirect::to('admin/userlist')
        ->with('message', 'No such user!');
    }
    }
    /**
    * function to update user details
    */
        public function postUserupdate(){
 
        $uid = Input::get('user_id');
        $user = User::find($uid);
        if($user != ''){
        $user->email = Input::get('useremail');
        $user->fullname = Input::get('fullname');
        $user->category = Input::get('category');
        $user->language = Input::get('language');
        $user->enable = Input::get('enable');
        $user->save();
        return Redirect::to('admin/edituser/'.$uid)
        ->with('message', 'User data updated!');
        }else{
            return Redirect::to('admin/edituser/'.$uid)
        ->with('message', 'No such user!');
        }

        }

      // function to return all lanuages in a category
        public function postFetchlanguages(){

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