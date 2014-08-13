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
		<div class="offset3 span6" id="qs{{$ques['id']}}">
	    <b>{{ $ques['question'] }}</b>
	    @if($ques['questionType'] == 1)
	    @if($ques['option1'] != "")
	    {{ Form::label('opt1', $ques['option1']) }}
    	{{ Form::radio('answer', $ques['option1'], false, array('class'=>'input-block-level','id'=>'opt1')) }}
    	@endif
    	@if($ques['option2'] != "")
	    {{ Form::label('opt1', $ques['option2']) }}
    	{{ Form::radio('answer', $ques['option2'], false, array('class'=>'input-block-level','id'=>'opt1')) }}
    	@endif
    	@if($ques['option3'] != "")
	    {{ Form::label('opt1', $ques['option3']) }}
    	{{ Form::radio('answer', $ques['option3'], false, array('class'=>'input-block-level','id'=>'opt1')) }}
    	@endif
    	@if($ques['option4'] != "")
	    {{ Form::label('opt1', $ques['option4']) }}
    	{{ Form::radio('answer', $ques['option4'], false, array('class'=>'input-block-level','id'=>'opt1')) }}
    	@endif
	    
    	@else
    	{{ Form::textarea('descriptive_answer', null, array('class'=>'input-block-level des-field','placeholder'=>'Descriptive Answer')) }}
    	@endif
		</div>
		<input type="hidden" value="1" id="qcount">
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

  	$('.btnNext').click(function(){
  		var tabId = $( this ).parent().attr('id');
  		var qcount = $('#qcount').val();
  		var next_count = parseInt(qcount)+1;
  		$('#qcount').val(next_count);
  		var max_count = $('#max_count').val();
  		var lang_id = $('#langId').val();
  		// alert("test:"+tabId);
  		if(next_count < max_count){
  			if(tabId == 'tab1'){
  			$('#tab2').addClass("active");
  			$('#tab1').removeClass("active");
  			}
  		}else if(next_count == max_count){
  			if(tabId == 'tab2'){
  			$('#tab3').addClass("active");
  			$('#tab2').removeClass("active");
  			}
  		}
  		var params = {
  			l_id:lang_id,
  			next_count:next_count
  		};
  		$.post('/users/nextQues',params,function(data){
            //console.log(data);
            
            /*$.each(data.languages, function(){
            if(this.status == 1){
            opts +='<option value='+this.id+'>'+this.language+'</option>';
            }
			
			});
				$('#lang_list').html(opts);*/
			
             });

	});

  $('.btnPrevious').click(function(){
  		var tabId = $( this ).parent().attr('id');
  		var qcount = $('#qcount').val();
  		var next_count = parseInt(qcount)-1;
  		$('#qcount').val(next_count);
  		var max_count = $('#max_count').val();
  		var lang_id = $('#langId').val();
  		// alert("test:"+tabId);
  		if(next_count >1){
  			if(tabId == 'tab3'){
  			$('#tab2').addClass("active");
  			$('#tab3').removeClass("active");
  			}
  		}else if(next_count == 1){
  			if(tabId == 'tab2'){
  			$('#tab1').addClass("active");
  			$('#tab2').removeClass("active");
  			}
  		}
});

  	});
  </script>

