{{ Form::open(array('url'=>'languages/add', 'class'=>'form-signup','id'=>'add_language')) }}
    <h2 class="form-signin-heading">Add Language</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

 	{{ Form::select('category', $categories, null, array('class'=>'input-block-level required'))}}
    {{ Form::text('language', null, array('class'=>'input-block-level required', 'placeholder'=>'Language Name')) }}
    
 
    {{ Form::submit('Add', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}

<script type="text/javascript">
  $(document).ready(function(e){

    $('#add_language').validate();

    });

  </script>