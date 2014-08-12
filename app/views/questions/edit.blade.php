{{ Form::open(array('url'=>'questions/update', 'class'=>'form-signup','id'=>'add_question')) }}
    <h2 class="form-signin-heading">Edit Question</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <?php 
   	// print_r($question);exit;
   	// setting status for each radio button
    if($question['questionType'] == 1) {
    	$objcheck = true;
    	$descheck = false;

    	if($question['answerOption'] == "Option1"){
    		$stat1 = true;
    		$stat2 = false;
    		$stat3 = false;
    		$stat4 = false;
    	}else if($question['answerOption'] == "Option2"){
    		$stat1 = false;
    		$stat2 = true;
    		$stat3 = false;
    		$stat4 = false;
    	}else if($question['answerOption'] == "Option3"){
    		$stat1 = false;
    		$stat2 = false;
    		$stat3 = true;
    		$stat4 = false;
    	}else if($question['answerOption'] == "Option4"){
    		$stat1 = false;
    		$stat2 = false;
    		$stat3 = false;
    		$stat4 = true;
    	}else{
    		$stat1 = false;
    		$stat2 = false;
    		$stat3 = false;
    		$stat4 = false;
    	}
    }else{
    	$objcheck = false;
    	$descheck = true;

    	$stat1 = false;
    	$stat2 = false;
    	$stat3 = false;
    	$stat4 = false;
    }
    ?>

 	{{ Form::select('category', $categories, $question['categoryId'], array('class'=>'input-block-level required','id'=>'qn_category'))}}
 	{{ Form::select('language', $languages, $question['languageId'], array('class'=>'input-block-level required','id'=>'lang_list'))}}
    {{ Form::text('question', Input::old( 'question', $question['question']), array('class'=>'input-block-level required', 'placeholder'=>'Enter Question')) }}

    {{ Form::label('objective-0', 'Objective') }}
    {{ Form::radio('qtype', 'objective', $objcheck, array('class'=>'input-block-level required','id'=>'objective-0')) }}
    {{ Form::label('objective-0', 'Descriptive') }}
	{{ Form::radio('qtype', 'descriptive', $descheck, array('class'=>'input-block-level required','id'=>'descriptive-0')) }}

	

	 <div class="objective" style="display: none">
	 	{{ Form::text('option1', Input::old( 'option1', $question['option1']), array('class'=>'input-block-level obj-field', 'placeholder'=>'Option1')) }}
	 	{{ Form::text('option2', Input::old( 'option2', $question['option2']), array('class'=>'input-block-level obj-field', 'placeholder'=>'Option2')) }}
	 	{{ Form::text('option3', Input::old( 'option3', $question['option3']), array('class'=>'input-block-level obj-field', 'placeholder'=>'Option3')) }}
	 	{{ Form::text('option4', Input::old( 'option4', $question['option4']), array('class'=>'input-block-level obj-field', 'placeholder'=>'Option4')) }}

	 	{{ Form::label('correct_ans', 'Choose Correct Option:') }}
	 	{{ Form::label('opt1', 'Option1') }}
    	{{ Form::radio('answer', 'Option1', $stat1, array('class'=>'input-block-level opts','id'=>'opt1')) }}
    	{{ Form::label('opt2', 'Option2') }}
		{{ Form::radio('answer', 'Option2', $stat2, array('class'=>'input-block-level opts','id'=>'opt2')) }}
		{{ Form::label('opt3', 'Option3') }}
    	{{ Form::radio('answer', 'Option3', $stat3, array('class'=>'input-block-level opts','id'=>'opt3')) }}
    	{{ Form::label('opt4', 'Option4') }}
		{{ Form::radio('answer', 'Option4', $stat4, array('class'=>'input-block-level opts','id'=>'opt4')) }}
	 	<!---{{ Form::text('answer', null, array('class'=>'input-block-level obj-field', 'placeholder'=>'Correct Answer')) }} -->
	 </div>

	 <div class="descriptive" style="display: none">
	 	{{ Form::textarea('descriptive_answer', Input::old( 'descriptive_answer', $question['answerOption']), array('class'=>'input-block-level des-field','placeholder'=>'Descriptive Answer')) }}
	 </div>
	 {{ Form::hidden('lang_id', Input::old( 'lang_id', $question['languageId']))}}
	 {{ Form::hidden('qs_id', Input::old( 'qs_id', $question['id']))}}
	 {{ Form::hidden('qstype', Input::old( 'qstype', $question['questionType']),array('class'=>'qstype'))}}
	 
    {{ Form::submit('Update', array('class'=>'btn btn-large btn-primary btn-block'))}}
{{ Form::close() }}

<script type="text/javascript">
  $(document).ready(function(e){

    $('#add_question').validate();

    // to display fields w.r.t qtype
    var qstype = $('.qstype').val();
    console.log(qstype);
    if(qstype == 1){
    	$('.objective').show();
		$('.descriptive').hide();
    }else{
    	$('.objective').hide();
		$('.descriptive').show();
    }

    //$('select[name^="category"] option[value="0"]').attr("selected","selected");

	$('#qn_category').on('change', function() {
		  var cat_id = this.value;
		  var opts = "";
		   $.post('fetch',{cat_id:cat_id},function(data){
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

		$(this).prop('checked',true);
		$('#descriptive-0').prop('checked',false);

		$('.objective').show();
		$('.descriptive').hide();
	});

	$('#descriptive-0').click(function(e){
		//$('.obj-field').removeClass("required");
		//$('.des-field').addClass("required");


		$(this).prop('checked',true);
		$('#objective-0').prop('checked',false);

		$('.objective').hide();
		$('.descriptive').show();
	});

	/*$('.opts').click(function(event){
		var target = event.currentTarget;
		//alert('test');
		 $(target).attr('checked',true);
		 $(target).siblings(".opts").attr('checked',false);
	});*/

	});

    

  </script>