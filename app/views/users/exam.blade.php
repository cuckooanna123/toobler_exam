<div class="container-fluid span9 make-grid" >
   <table class="table table-striped ">
	<tr>
		<th>Name:</th>
				<td>
				{{ $user['fullname']}}
				</td>
			</tr>
		<tr>
		<th>Category:</th>
				<td>
				@if(isset($user['categoryName']))
                {{ $user['categoryName'] }}
                 @endif
				</td>
			</tr>
			<tr>
		<th>Language:</th>
				<td>
				@if(isset($user['langName']))
                {{ $user['langName'] }}
                 @endif
				</td>
			</tr>
			<tr>
		<th>Max Question Count:</th>
				<td>
				@if(count($queslist) > 0)
                {{ count($queslist) }}
                 @endif 
				</td>
			</tr>
			<tr>
		<th>Max Exam Time:</th>
				<td>
				@if(count($settings) > 0)
                {{ $settings['max_exam_time'] }}
                @endif 
				</td>
			</tr>
	</table>
</div>
<?php 
$ques = $queslist[0];
/*echo "<pre>";
print_r($ques);
exit;*/

$maxCount = count($queslist);
?>
 <div class="container-fluid span12 make-grid">
	<div class="row-fluid">
		<div class="offset3 span6 ques_div" id="qs{{$ques['id']}}">
	    <b>1. {{ $ques['question'] }}</b>
	    @if($ques['questionType'] == 1)
	     @if($ques['option1'] != "")
      {{ Form::label('opt1', $ques['option1']) }}
      {{ Form::radio('answer', $ques['option1'], false, array('class'=>'input-block-level','id'=>'opt1')) }}
      @endif
      @if($ques['option2'] != "")
      {{ Form::label('opt2', $ques['option2']) }}
      {{ Form::radio('answer', $ques['option2'], false, array('class'=>'input-block-level','id'=>'opt2')) }}
      @endif
      @if($ques['option3'] != "")
      {{ Form::label('opt3', $ques['option3']) }}
      {{ Form::radio('answer', $ques['option3'], false, array('class'=>'input-block-level','id'=>'opt3')) }}
      @endif
      @if($ques['option4'] != "")
      {{ Form::label('opt4', $ques['option4']) }}
      {{ Form::radio('answer', $ques['option4'], false, array('class'=>'input-block-level','id'=>'opt4')) }}
      @endif
	    
    	@else
    	{{ Form::textarea('descriptive_answer', null, array('class'=>'input-block-level des-field','placeholder'=>'Descriptive Answer')) }}
    	@endif
		</div>
		<input type="hidden" value="1" id="qcount">
    <input type="hidden" value="$user['id']" id="user_id">
		<input type="hidden" value="{{$ques['languageId']}}" id="langId">
		<input type="hidden" value="{{$maxCount}}" id="max_count">
</div>

<div class="tab-content">
    <div class="tab-pane active" id="tab1">
        <a class="btn btn-primary btnNext">Next</a>
    </div>
    <div class="tab-pane" id="tab2">
        <a class="btn btn-primary btnNext">Next</a>
        <a class="btn btn-primary btnPrevious">Previous</a>
    </div>
    <div class="tab-pane" id="tab3">
        <a class="btn btn-primary btnPrevious">Previous</a>
	</div>
</div>
</div> 

	

	


<script type="text/javascript">
  $(document).ready(function(e){

    // next button click
  	$('.btnNext').click(function(){

  		var tabId = $( this ).parent().attr('id');
  		var qcount = $('#qcount').val();
  		var next_count = parseInt(qcount)+1;
  		$('#qcount').val(next_count);
  		var max_count = $('#max_count').val();
  		var lang_id = $('#langId').val();
      var uid = $('#user_id').val();
  		// tab dipaly
  		if(next_count < max_count){
  			if(tabId == 'tab1'){
  			$('#tab2').addClass("active");
  			$('#tab1').removeClass("active");
  			}
  		}else if(next_count == max_count){
  			if(tabId == 'tab2'){
    			$('#tab3').addClass("active");
    			$('#tab2').removeClass("active");
  			}else if(tabId == 'tab1'){
          $('#tab3').addClass("active");
          $('#tab1').removeClass("active");
        }
  		}
  		var params = {
  			l_id:lang_id,
  			next_count:next_count,
        uid:uid
  		};
      // to load next question
  		$.post('/users/nextQues',params,function(data){
            //console.log(data);
          // calling function to generate question html
            qusetion = generateHtml(data,next_count);
				     $('.ques_div').html(qusetion);
			
             });

	});



  $('.btnPrevious').click(function(){
  		var tabId = $( this ).parent().attr('id');
  		var qcount = $('#qcount').val();
  		var next_count = parseInt(qcount)-1;
  		$('#qcount').val(next_count);
  		var max_count = $('#max_count').val();
  		var lang_id = $('#langId').val();
      var uid = $('#user_id').val();
  		
  		if(next_count >1){
  			if(tabId == 'tab3'){
  			$('#tab2').addClass("active");
  			$('#tab3').removeClass("active");
  			}
  		}else if(next_count == 1){
  			if(tabId == 'tab2'){
    			$('#tab1').addClass("active");
    			$('#tab2').removeClass("active");
  			}else if(tabId == 'tab3'){
          $('#tab1').addClass("active");
          $('#tab3').removeClass("active");
        }
  		}

      var params = {
        l_id:lang_id,
        next_count:next_count,
        uid:uid
      };
      // to load next question
      $.post('/users/nextQues',params,function(data){
            //console.log(data);
          // calling function to generate question html
            qusetion = generateHtml(data,next_count);

             $('.ques_div').html(qusetion);
      
             });
  });

}); // closing document ready

// function to generate question html
function generateHtml(data,qscount){
      
      var qusetion = "";
            // html to display next question
            if(data.status){
                var ques = data.question;
              qusetion +='<b>'+qscount+'. '+ques.question+'</b><br>';
              if(ques.questionType == 1){
                if(ques.option1 != ""){
                 var opt1 ='<label for="opt1">'+ques.option1+'</label>'+
                 '<input type="radio" class="radio" name="answer" value="'+ques.option1+'" id="opt1" />';
                 qusetion +=opt1;
                }
                if(ques.option2 != ""){
                 var opt2 ='<label for="opt2">'+ques.option2+'</label>'+
                 '<input type="radio" class="radio" name="answer" value="'+ques.option2+'" id="opt2" />';
                 qusetion +=opt2;
                }
                if(ques.option3 != ""){
                 var opt3 ='<label for="opt3">'+ques.option3+'</label>'+
                 '<input type="radio" class="radio" name="answer" value="'+ques.option3+'" id="opt3" />';
                 qusetion +=opt3;
                }
                if(ques.option4 != ""){
                 var opt4 ='<label for="opt4">'+ques.option4+'</label>'+
                 '<input type="radio" class="radio" name="answer" value="'+ques.option4+'" id="opt4" />';
                 qusetion +=opt4;
                }

              }else{
                qusetion +='<textarea rows="5" cols="50" placeholder="Type your answer"></textarea>';
              }
              return qusetion;
            }
    }

  </script>

