  <?php 
      // to display selected option in objective questions.
        $stat1 = false;
        $stat2 = false;
        $stat3 = false;
        $stat4 = false;
    if(!empty($response)){
    // setting status for each radio button
    if($response['answer_descriptive'] == NULL) {
      
      if($response['answer_option'] == "Option1"){
        $stat1 = true;
        
      }else if($response['answer_option'] == "Option2"){
        $stat2 = true;
        
      }else if($response['answer_option'] == "Option3"){
        $stat3 = true;
      }else if($response['answer_option'] == "Option4"){
        $stat4 = true;
      }
    }
  }
    ?>

<!-- block displaying exam -->
<div class="container-fluid span12 outer">
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
                {{$user['categoryName'] }}
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
                {{ count($queslist) }}
                 
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
      <tr class="timer">
      <th> Time Remaining:</th>
        <td>
        <div id="defaultCountdown"></div>
        </td>
      </tr>
	</table>
</div>
<?php 
$maxCount = count($queslist);
if(!empty($queslist))
$ques = $queslist[0];
?>
 <div class="container-fluid span12 make-grid">
	<div class="row-fluid ques_div">
		<div class="offset3 span6 " >
      @if(isset($ques))
	    <b>1. {{ $ques['question'] }}</b>
      {{ Form::hidden('qid', Input::old( 'qid', $ques['id']),array('id'=>'qid'))}}
      {{ Form::hidden('qtype', Input::old( 'qtype', $ques['questionType']),array('id'=>'qtype'))}}

	    @if($ques['questionType'] == 1)
	     @if($ques['option1'] != "")
      {{ Form::label('opt1', $ques['option1']) }}
      {{ Form::radio('answer', 'Option1', $stat1, array('class'=>'input-block-level','id'=>'opt1')) }}
      @endif
      @if($ques['option2'] != "")
      {{ Form::label('opt2', $ques['option2']) }}
      {{ Form::radio('answer', 'Option2', $stat2, array('class'=>'input-block-level','id'=>'opt2')) }}
      @endif
      @if($ques['option3'] != "")
      {{ Form::label('opt3', $ques['option3']) }}
      {{ Form::radio('answer', 'Option3', $stat3, array('class'=>'input-block-level','id'=>'opt3')) }}
      @endif
      @if($ques['option4'] != "")
      {{ Form::label('opt4', $ques['option4']) }}
      {{ Form::radio('answer', 'Option4', $stat4, array('class'=>'input-block-level','id'=>'opt4')) }}
      @endif
	    
    	@else
      @if(!empty($response))
    	{{ Form::textarea('descriptive_answer', $response['answer_descriptive'], array('class'=>'input-block-level des-field','placeholder'=>'Descriptive Answer','id'=>'des_answer')) }}
      @else
      {{ Form::textarea('descriptive_answer', null, array('class'=>'input-block-level des-field','placeholder'=>'Descriptive Answer','id'=>'des_answer')) }}
      @endif <!-- end response check -->
    	@endif <!-- end qtype check -->
		</div>
 
</div>

    <!-- hidden values -->
    <input type="hidden" value="1" id="qcount">
    <input type="hidden" value="{{$user['id']}}" id="user_id">
    <input type="hidden" value="{{$ques['languageId']}}" id="langId">
    <input type="hidden" value="{{$ques['categoryId']}}" id="catId">
    <input type="hidden" value="{{$maxCount}}" id="max_count">
    <input type="hidden" value="{{ $user['fullname'] }}" id="user_fullname">
    <input type="hidden" value="{{ $settings['max_exam_time'] }}" id="exam_time">

<!-- button dispaly -->
<div class="tab-content">
    <div class="tab-pane active" id="tab1">
        <a class="btn btn-primary btnNext">Next</a>
    </div>
    <div class="tab-pane" id="tab2">
        <a class="btn btn-primary btnPrevious">Previous</a>
        <a class="btn btn-primary btnNext">Next</a>
    </div>
    <div class="tab-pane" id="tab3">
        <a class="btn btn-primary btnPrevious">Previous</a>
        <a class="btn btn-success btnFinished">Submit</a>
	  </div>
</div>
@else
No questions yet.!
@endif <!-- end question exist check -->
</div> 

</div>
<script type="text/javascript">
  $(document).ready(function(e){

    // set counter for  maximum exam time
    var exam_time = $('#exam_time').val();
    var time = exam_time.split(':');
    var Until = '+'+time[0]+'h +'+time[1]+'m +'+time[2]+'s';
    //console.log(Until);
   // $('#defaultCountdown').countdown({until: Until});


    $('#defaultCountdown').countdown({until: Until,  
      onExpiry: liftOff}); 

      function liftOff() { 
      alert('You have reached the maximum time limit!'); 
      //timeOut();
       var params = {
      } 

      $.post('/users/clearsession',params,function(data){
            console.log(data); 
            if(data.status){
              homePage();
            }
     });
    
      } 
 
    
    function homePage(){
      window.location="http://localhost:8000/users/login";
    }

    // next button click
  	$('.btnNext').click(function(){

  		var tabId = $( this ).parent().attr('id');
  		var qcount = $('#qcount').val();
  		var next_count = parseInt(qcount)+1;
  		$('#qcount').val(next_count);
  		var max_count = $('#max_count').val();
  		var lang_id = $('#langId').val();
      var catId = $('#catId').val();
      var uid = $('#user_id').val();
      var qid = $('#qid').val();
      var qtype = $('#qtype').val();
      var des_answer = $(document).find('textarea#des_answer').val();
      var opt = $('input[name=answer]:checked').val();
      //console.log("show:"+opt);

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
        catId:catId,
  			next_count:next_count,
        uid:uid,
        qid:qid,
        qtype:qtype,
        des_ans: des_answer,
        obj_ans: opt
  		};
      // to load next question
  		$.post('/users/nextQues',params,function(data){
            //console.log(data);
          // calling function to generate question html
            qusetion = generateHtml(data,next_count,max_count);
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
      var catId = $('#catId').val();
      var uid = $('#user_id').val();
      var qid = $('#qid').val();
      var qtype = $('#qtype').val();
      var des_answer = $(document).find('textarea#des_answer').val();
      var opt = $('input[name=answer]:checked').val();
  		
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
        catId:catId,
        next_count:next_count,
        uid:uid,
        qid:qid,
        qtype:qtype,
        des_ans: des_answer,
        obj_ans: opt
      };
      // to load next question
      $.post('/users/nextQues',params,function(data){
            //console.log(data);

          // calling function to generate question html
            qusetion = generateHtml(data,next_count,max_count);
        
          // replace question html
             $('.ques_div').html(qusetion);
      
             });
  });


  // exam finish button click
  //$('.btnFinished').click(function(){
    $(document).on('click', '.btnFinished', function () {
      var tabId = $( this ).parent().attr('id');
      var qcount = $('#qcount').val();
      var next_count = parseInt(qcount)-1;
      $('#qcount').val(next_count);
      var max_count = $('#max_count').val();
      var user_fullname = $('#user_fullname').val();
      var lang_id = $('#langId').val();
      var catId = $('#catId').val();
      var uid = $('#user_id').val();
      var qid = $('#qid').val();
      var qtype = $('#qtype').val();
      var des_answer = $(document).find('textarea#des_answer').val();
      var opt = $('input[name=answer]:checked').val();

      var params = {
        l_id:lang_id,
        catId:catId,
        next_count:next_count,
        uid:uid,
        qid:qid,
        qtype:qtype,
        des_ans: des_answer,
        obj_ans: opt
      };

      var finish_params = {
        l_id:lang_id,
        catId:catId,
        uid:uid,
        total_qs_count: max_count
      };


          // to save last loaded question's answer on finish button click
      $.post('/users/savefinish',params,function(data){
            console.log(data);
            if(data.status){
            finishExam(finish_params,max_count,user_fullname);
              }
             });

      
      

  });

}); // closing document ready


// function to save marks on exam finish
function finishExam(params,max_count,user_fullname){

    $.post('/users/processData',params,function(data){
            //console.log(data);
            var res = "";
   // show message
          if(data.status){
             //$('.outer').html('<p class="alert">You completed with '+data.correct+' correct answers and '+data.wrong+' wrong answers</p>');

              res += "<h3>Exam Summary</h3>"+
              "<table class='table table-striped ''>"+
                        "<tr><th>Name:</th>"+
                        "<td>"+user_fullname+"</td></tr>"+
                        "<tr><th>Question count:</th>"+
                        "<td>"+max_count+"</td></tr>"+
                        "<tr><th>Correct answer:</th>"+
                        "<td>"+data.correct+"</td></tr>"+
                        "<tr><th>Wrong answer:</th>"+
                        "<td>"+data.wrong+"</td></tr>";
                        if(data.des_count>0){
                          res += "<tr><th>Descriptive question count:</th>"+
                        "<td>"+data.des_count+"</td></tr>";
                        }
                        res += "</table>";
                        $('.outer').html(res);
            
           }
      
             });
}

// function to generate question html
function generateHtml(data,qscount,max_count){
      
      var qusetion = "";
            // html to display next question
            if(data.status){
                var ques = data.question;
                var resp = data.response;
                // to display selected option in objective questions.
                  var objcheck1 = "";
                  var objcheck2 = "";
                  var objcheck3 = "";
                  var objcheck4 = "";
                if(resp.answer_descriptive == null){
                  if(resp.answer_option == "Option1"){
                    var objcheck1 = "checked";
                  }else if(resp.answer_option == "Option2"){
                    var objcheck2 = "checked";
                  }else if(resp.answer_option == "Option3"){
                    var objcheck3 = "checked";
                  }else if(resp.answer_option == "Option4"){
                    var objcheck4 = "checked";
                  }
                   }
           /* if(max_count == qscount){
             qusetion +='<a class="btn btn-success btnFinished top-right">Exam Finished</a>';
            }*/
             qusetion +='<div class="offset3 span6 " >';
             qusetion +='<b>'+qscount+'. '+ques.question+'</b><br>'+
              '<input type="hidden" value="'+ques.id+'" id="qid">'+
              '<input type="hidden" value="'+ques.questionType+'" id="qtype">';
              if(ques.questionType == 1){
                if(ques.option1 != ""){
                 var opt1 ='<label for="opt1">'+ques.option1+'</label>'+
                 '<input type="radio" class="radio" name="answer" value="Option1" id="opt1" '+objcheck1+'/>';
                 qusetion +=opt1;
                }
                if(ques.option2 != ""){
                 var opt2 ='<label for="opt2">'+ques.option2+'</label>'+
                 '<input type="radio" class="radio" name="answer" value="Option2" id="opt2" '+objcheck2+'/>';
                 qusetion +=opt2;
                }
                if(ques.option3 != ""){
                 var opt3 ='<label for="opt3">'+ques.option3+'</label>'+
                 '<input type="radio" class="radio" name="answer" value="Option3" id="opt3" '+objcheck3+'/>';
                 qusetion +=opt3;
                }
                if(ques.option4 != ""){
                 var opt4 ='<label for="opt4">'+ques.option4+'</label>'+
                 '<input type="radio" class="radio" name="answer" value="Option4" id="opt4" '+objcheck4+'/>';
                 qusetion +=opt4;
                }

              }else{
                
                console.log(resp.answer_descriptive);
                if(resp.answer_descriptive == null){
                 
                   qusetion +='<textarea  class="input-block-level des-field" id="des_answer" placeholder="Type your answer"></textarea>';
                  
                }
                else{
                  
                  qusetion +='<textarea  class="input-block-level des-field" id="des_answer" placeholder="Type your answer">'+resp.answer_descriptive+'</textarea>';
                }
                qusetion +='</div>';
              }
              return qusetion;
            }
    }

  </script>

