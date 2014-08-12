{{ Form::open(array('url'=>'admin/signin', 'class'=>'form-signin','id'=>'login_form')) }}
    <h2 class="form-signin-heading">Please Login</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
 
    {{ Form::text('email', null, array('class'=>'input-block-level required email', 'placeholder'=>'Email Address')) }}
    {{ Form::password('password', array('class'=>'input-block-level required', 'placeholder'=>'Password')) }}
 
    {{ Form::submit('Login', array('class'=>'btn btn-large btn-primary btn-block'))}}
    {{ HTML::link('admin/forgot','Forgot password') }}
{{ Form::close() }}


<script type="text/javascript">
  $(document).ready(function(e){

    $('#login_form').validate();

    });

  </script>
