 {{ Form::open(array('url'=>'users/start', 'class'=>'form-signin','id'=>'user_start')) }}
    {{ Form::submit('Start', array('class'=>'btn btn-large btn-primary btn-block start_exam','id'=>Session::get('userId')))}}
{{ Form::close() }}

<script type="text/javascript">
  $(document).ready(function(e){

       $('.start_exam').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            window.location="http://localhost:8000/users/exam/"+id;
        });

        

        
    });

  </script>