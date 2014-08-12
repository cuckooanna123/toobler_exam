<h1>Settings</h1>
 
<p class="wel_msg">Welcome to your  Settings!</p>

@if (count($settings) === 0)
{{ HTML::link('admin/settingsadd','Add Settings', array('class' => 'btn btn-info add-setting')) }}
@else

<table class="table table-striped ">
	@foreach($settings as $setting)
	<tr id="row{{$setting['id']}}">
				<th>
				@if($setting['type'] === "max_exam_time")
                Maximum Exam Time
                @else
                {{$setting['type']}}
                @endif
					
				</th>
				<td>
					{{$setting['value']}}
				</td>
				<td>
				{{ HTML::link('#','Edit', array('class' => 'btn btn-info edit_settings','id' =>$setting['id'])) }}
				</td>
				<td>
				{{ HTML::link('#','Delete', array('class' => 'btn btn-danger del_settings','id' =>$setting['id'])) }}
				</td>
			</tr>
			@endforeach
	</table>
@endif


    <script type="text/javascript">
  $(document).ready(function(e){

    $('.edit_settings').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            window.location="http://localhost:8000/admin/settingsedit/"+id;
        });

     // ajax post call for delete
        $('.del_settings').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            $.post('delete/',{id:id},function(data){
            console.log(data);
            if(data.status){
            		 $(document).find('.alert').hide();
                     $('<p class="alert">'+data.msg+'</p>').insertAfter('.wel_msg');
                     $('#row'+id).remove();

                 }
             });
        });


    });

  
</script>