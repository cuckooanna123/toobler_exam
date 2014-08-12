{{ Form::open(array('url'=>'admin/updatesettings', 'class'=>'form-signin','id'=>'edit_Settings')) }}
    <h2 class="form-signin-heading">General Settings</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <?php
    if($item['type']== "max_exam_time"){
        $item_value = $item['time_min'];
    }else{
      $item_value = $item['value'];  
    }
     ?>
    
    {{ Form::label('exam_time', 'Maximum exam time in minutes'); }} 
    {{ Form::text('exam_time',Input::old( 'exam_time', $item_value), array('class'=>'input-block-level required number', 'placeholder'=>'Exam Time in Minutes')) }}
    {{ Form::hidden('item_id', Input::old( 'item_id', $item['id']))}}
    {{ Form::hidden('item_type', Input::old( 'item_type', $item['type']))}}
 
 
    {{ Form::submit('Save', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}



<script type="text/javascript">
  $(document).ready(function(e){

    $('#edit_Settings').validate();

    });

  </script>