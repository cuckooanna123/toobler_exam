{{ Form::open(array('url'=>'admin/change', 'class'=>'form-signin','id'=>'form_forgot')) }}
    <h2 class="form-signin-heading">Change Password</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
 
    {{ Form::text('email', null, array('class'=>'input-block-level required email', 'placeholder'=>'Email Address')) }}
    {{ Form::password('password', array('class'=>'input-block-level required','id'=>'password','minlength'=>'6', 'maxlength'=>'12','placeholder'=>'Password')) }}
    {{ Form::password('password_confirmation', array('class'=>'input-block-level required', 'minlength'=>'6','maxlength'=>'12',
    'equalTo'=>"#password",'placeholder'=>'Password')) }}
 
    {{ Form::submit('Save', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}



<script type="text/javascript">
  $(document).ready(function(e){

    $('#form_forgot').validate();

    });

  </script>