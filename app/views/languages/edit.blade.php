{{ Form::open(array('url'=>'languages/update', 'class'=>'form-signup','id'=>'edit_language')) }}
    <h2 class="form-signin-heading">Edit Language</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <?php 
    $statusArray = array("1"=>"active",
    					 "0"=>"inactive");
    //print_r($language);
    ?>
 	{{ Form::select('category', $categories, $language['categoryId'],
     array('class'=>'input-block-level required'))}}

    {{ Form::text('language', Input::old( 'language', $language['language']), 
    array('class'=>'input-block-level required', 'placeholder'=>'Language Name')) }}

    {{ Form::select('status', $statusArray, $language['status'],
     array('class'=>'input-block-level required'))}}

    {{ Form::hidden('lang_id', Input::old( 'lang_id', $language['id']))}}

    {{ Form::submit('Update', array('class'=>'btn btn-large btn-primary btn-block'))}}
    
{{ Form::close() }}

<script type="text/javascript">
  $(document).ready(function(e){

    $('#edit_language').validate();

    });

  </script>
