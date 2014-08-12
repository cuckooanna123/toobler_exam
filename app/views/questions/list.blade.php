<h2>Questions List</h2>

<?php 
$qs_type = 1;
if(isset($_GET['type']))
$qs_type = (int)$_GET['type'];
if($qs_type == 1){
    $type1 = true;
    $type2 = false;
}else{
    $type1 = false;
    $type2 = true;
}?>
<h5>Category:<i><?php echo $language['CategoryName']; ?></i></h5>
<h5>Language:<i><?php echo $language['language']; ?></i> {{ HTML::link('questions/add','Add Question', array('class' => 'btn btn-info')) }}</h5>

{{ Form::label('objective-1', 'Objective Questions') }}
{{ Form::radio('qtype', 'objectiveQs', $type1, array('class'=>'input-block-level required','id'=>'objective-1')) }}
{{ Form::label('descriptive-1', 'Descriptive Questions') }}
{{ Form::radio('qtype', 'descriptiveQs', $type2, array('class'=>'input-block-level required','id'=>'descriptive-1')) }}

<input type="hidden" name="lang_id" id="lang_id" value="<?php echo $language['id']; ?>">

@if (count($questions) > 0)
<table class="table table-striped table-bordered">
        <thead>
        <tr>
        <th>Question</th>
        <th>Question Type</th>
        @if( $qs_type === 1)
        <th>Options</th>
        @endif
        <th></th>
        <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($questions as $ques)
        <tr id="row{{$ques['id']}}">
        
			<td>{{ $ques['question'] }}</td>
            <td>
            	@if((int)$ques['questionType'] === 1)
                Objective
                @else
                Descriptive
                @endif
            </td>
            @if((int)$ques['questionType'] === 1)
            <td>
                <p>{{ $ques['option1'] }}</p>
                <p>{{ $ques['option2'] }}</p>
                <p>{{ $ques['option3'] }}</p>
                <p>{{ $ques['option4'] }}</p>
            </td>
            @endif
            <td>{{ HTML::link('#','Edit', array('class' => 'btn btn-info edit-qst','id' =>$ques['id'])) }}</td>
            <td>{{ HTML::link('#', 'Delete', array('class'=>'btn btn-danger del-qst','id' =>$ques['id']))}}</td>        
            </tr>
            @endforeach
        </tbody>
    </table>
    <p> <?php echo $qsList->links(); ?></p>
    @else
    No questions found
    @endif

 <script type="text/javascript">
  $(document).ready(function(e){

      // making pagination links horizontal
    $(".pagination").attr("id","navlist");

        // ajax post call for delete
        $('.del-qst').click(function(e){
            var id = e.target.id;
            var lang_id = $('#lang_id').val();
            console.log("lid:"+lang_id)
            e.preventDefault();
            $.post('/questions/delete',{id:id,lid:lang_id},function(data){
            console.log(data);
            if(data.status){
                     $('<p class="alert">'+data.msg+'</p>').insertAfter('.wel_msg');
                     $('#row'+id).remove();
                 }
             });
        });

        $('.edit-qst').click(function(e){
            var id = e.target.id;
            var lang_id = $('#lang_id').val();
            console.log("id:"+id)
            e.preventDefault();
            window.location="http://localhost:8000/questions/edit/"+id;
        });

        $('#objective-1').click(function(e){
            var lang_id = $('#lang_id').val();
            console.log("id:"+lang_id)
            e.preventDefault();
            window.location="http://localhost:8000/questions/list/"+lang_id+"?type=1";
        });

         $('#descriptive-1').click(function(e){
            var lang_id = $('#lang_id').val();
            console.log("id:"+lang_id)
            e.preventDefault();
            window.location="http://localhost:8000/questions/list/"+lang_id+"?type=0";
        });
  
    });

  </script>