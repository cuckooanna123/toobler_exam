{{ Form::open(array('url'=>'admin/userupdate', 'class'=>'form-signup','id'=>'edit_user')) }}
    <h2 class="form-signin-heading">Edit User</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <?php 
    //echo "<pre>";
    //print_r($user['enable']);
    if($user['enable'] == 1){
        $enable = true;
    }else{
        $enable = false; 
    }
    ?>

 	{{ Form::text('useremail', Input::old( 'useremail', $user['email']), array('class'=>'input-block-level email required', 'placeholder'=>'User Email'))}}
    {{ Form::text('fullname', Input::old( 'fullname', $user['fullname']), array('class'=>'input-block-level required','placeholder'=>'User Fullname')) }}
    {{ Form::select('category', $categories, $user['category'], array('class'=>'input-block-level required','id'=>'qn_category'))}}
    {{ Form::select('language', $languages, $user['language'], array('class'=>'input-block-level required','id'=>'lang_list'))}}
    {{ Form::label('enable', 'Is user enabled')}}
    {{ Form::checkbox('enable', 1,$enable) }} 
    
    {{ Form::hidden('user_id', Input::old( 'user_id', $user['id']))}}
    {{ Form::hidden('user_cat', Input::old( 'user_cat', $user['category']),array('id'=>"user_cat"))}}
    {{ Form::submit('Update', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}

<script type="text/javascript">
  $(document).ready(function(e){

    $('#edit_user').validate();
    var user_cat = $("#user_cat").val();
    console.log("cat:"+user_cat);

    // to make "select category" default option
    if(user_cat == ''){
    $('select[name^="category"] option[value="0"]').attr("selected","selected");
    }


    $('#qn_category').on('change', function() {
          var cat_id = this.value;
          var opts = "";
           $.post('/admin/fetchlanguages',{cat_id:cat_id},function(data){
            
            $.each(data.languages, function(){
            if(this.status == 1){
            opts +='<option value='+this.id+'>'+this.language+'</option>';
            }
            
            });
                $('#lang_list').html(opts);
            
             });
        });

    });

  </script>