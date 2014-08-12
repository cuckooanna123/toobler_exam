{{ Form::open(array('url'=>'questions/save', 'class'=>'form-signup','id'=>'add_question')) }}
    <h2 class="form-signin-heading">Add Question</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

 	{{ Form::select('category', $categories, null, array('class'=>'input-block-level required','id'=>'qn_category'))}}
 	{{ Form::select('language', array(), null, array('class'=>'input-block-level required','id'=>'lang_list'))}}
    {{ Form::text('question', null, array('class'=>'input-block-level required', 'placeholder'=>'Enter Question')) }}

    {{ Form::label('objective-0', 'Objective') }}
    {{ Form::radio('qtype', 'objective', false, array('class'=>'input-block-level required','id'=>'objective-0')) }}
    {{ Form::label('objective-0', 'Descriptive') }}
	{{ Form::radio('qtype', 'descriptive', false, array('class'=>'input-block-level required','id'=>'descriptive-0')) }}

	 <div class="objective" style="display: none">
	 	{{ Form::text('option1', null, array('class'=>'input-block-level obj-field', 'placeholder'=>'Option1')) }}
	 	{{ Form::text('option2', null, array('class'=>'input-block-level obj-field', 'placeholder'=>'Option2')) }}
	 	{{ Form::text('option3', null, array('class'=>'input-block-level obj-field', 'placeholder'=>'Option3')) }}
	 	{{ Form::text('option4', null, array('class'=>'input-block-level obj-field', 'placeholder'=>'Option4')) }}

	 	{{ Form::label('correct_ans', 'Choose Correct Option:') }}
	 	{{ Form::label('opt1', 'Option1') }}
    	{{ Form::radio('answer', 'Option1', false, array('class'=>'input-block-level','id'=>'opt1')) }}
    	{{ Form::label('opt2', 'Option2') }}
		{{ Form::radio('answer', 'Option2', false, array('class'=>'input-block-level','id'=>'opt2')) }}
		{{ Form::label('opt3', 'Option3') }}
    	{{ Form::radio('answer', 'Option3', false, array('class'=>'input-block-level','id'=>'opt3')) }}
    	{{ Form::label('opt4', 'Option4') }}
		{{ Form::radio('answer', 'Option4', false, array('class'=>'input-block-level','id'=>'opt4')) }}
	 <!-- {{ Form::text('answer', null, array('class'=>'input-block-level obj-field', 'placeholder'=>'Correct Answer')) }} -->
	 </div>

	 <div class="descriptive" style="display: none">
	 	{{ Form::textarea('descriptive_answer', null, array('class'=>'input-block-level des-field','placeholder'=>'Descriptive Answer')) }}
	 </div>
	 
    {{ Form::submit('Add', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}

<script type="text/javascript">
  $(document).ready(function(e){

    $('#add_question').validate();

    // to make "select category" default option
    $('select[name^="category"] option[value="0"]').attr("selected","selected");

	$('#qn_category').on('change', function() {
		  var cat_id = this.value;
		  var opts = "";
		   $.post('/fetch',{cat_id:cat_id},function(data){
            //console.log(data);
            
            $.each(data.languages, function(){
            if(this.status == 1){
            opts +='<option value='+this.id+'>'+this.language+'</option>';
            }
			
			});
				$('#lang_list').html(opts);
			
             });
		});

	$('#objective-0').click(function(e){
		//$('.des-field').removeClass("required");
		//$('.obj-field').addClass("required");

		$('.objective').show();
		$('.descriptive').hide();
	});

	$('#descriptive-0').click(function(e){
		//$('.obj-field').removeClass("required");
		//$('.des-field').addClass("required");

		$('.objective').hide();
		$('.descriptive').show();
	});

	});

    

  </script>