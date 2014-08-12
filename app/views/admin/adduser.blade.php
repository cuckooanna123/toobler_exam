{{ Form::open(array('url'=>'admin/saveuser', 'class'=>'form-signup','id'=>'add_user')) }}
    <h2 class="form-signin-heading">Add User</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

 	{{ Form::text('useremail', null, array('class'=>'input-block-level email required', 'placeholder'=>'User Email','id'=>'user_mail'))}}
    {{ Form::text('fullname', null, array('class'=>'input-block-level required','placeholder'=>'User Fullname')) }}
    
    {{ Form::submit('Add', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}

<script type="text/javascript">
  $(document).ready(function(e){

    $('#add_user').validate();

    $('#user_mail').on('change', function() {
          var email = $('#user_mail').val();
          console.log(email);
           $.post('/admin/emailexist',{email:email},function(data){
            if(data.status){
                $('.row').html('<p class="alert">Email already existing!</p>');
            }
           });
        });

    });

  $('#user_mail').on('click', function(e) {
   // $('#user_mail').val('');
    $('.alert').remove();
  });

  </script>