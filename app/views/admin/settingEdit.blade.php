{{ Form::open(array('url'=>'admin/updatesettings', 'class'=>'form-signin','id'=>'edit_Settings')) }}
    <h2 class="form-signin-heading">Edit Settings</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    @foreach($settings as $item)
    <?php

    if($item['type']== "max_exam_time"){
        $item_value = $item['time_min'];
    }else{
      $item_value = $item['value'];  
    }
     ?>
    @if($item['type'] == "max_exam_time")
    {{ Form::label('exam_time', 'Maximum exam time in minutes'); }} 
    {{ Form::text('exam_time',Input::old( $item['type'], $item_value), array('class'=>'input-block-level required number')) }}
    {{ Form::hidden('item_id1', Input::old( 'item_id', $item['id']))}}
    {{ Form::hidden('item_type1', Input::old( 'item_type', $item['type']))}}
    @else
    {{ Form::label('max_qs_count', 'Maximum number of questions for exam'); }} 
    {{ Form::text('max_qs_count',Input::old( 'max_qs_count', $item_value), array('class'=>'input-block-level required number')) }}
    {{ Form::hidden('item_id2', Input::old( 'item_id', $item['id']))}}
    {{ Form::hidden('item_type2', Input::old( 'item_type', $item['type']))}}
    @endif
    

    @endforeach
 
 
    {{ Form::submit('Save', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}



<script type="text/javascript">
  $(document).ready(function(e){

    $('#edit_Settings').validate();

    });

  </script>