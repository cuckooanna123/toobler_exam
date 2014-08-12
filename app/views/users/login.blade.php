{{ Form::open(array('url'=>'users/signin', 'class'=>'form-signin','id'=>'user_login')) }}
    <h2 class="form-signin-heading">User Login</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
 
    {{ Form::text('useremail', null, array('class'=>'input-block-level required email', 'placeholder'=>'Email Address')) }}
    
 
    {{ Form::submit('Login', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}


<script type="text/javascript">
  $(document).ready(function(e){

    $('#user_login').validate();

    });

  </script>

