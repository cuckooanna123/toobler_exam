{{ Form::open(array('url'=>'admin/settings', 'class'=>'form-signin','id'=>'form_Settings')) }}
    <h2 class="form-signin-heading">General Settings</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    
    {{ Form::label('exam_time', 'Maximum exam time in minutes'); }} 
    {{ Form::text('exam_time', null, array('class'=>'input-block-level required number', 'placeholder'=>'Exam Time in Minutes')) }}
    
 
    {{ Form::submit('Save', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}



<script type="text/javascript">
  $(document).ready(function(e){

    $('#form_Settings').validate();

    });

  </script>