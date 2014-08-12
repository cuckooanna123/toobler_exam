<h1>Languages</h1>
 
<p class="wel_msg">Welcome to your  Languages List! {{ HTML::link('languages/create','Add Language', array('class' => 'btn btn-info')) }}</p>

	<table class="table table-striped table-bordered">
        <thead>
        <tr>
        <th>Category</th>
        <th>Language</th>
        <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($languages as $language)
        <tr id="row{{$language['id']}}">
        
			<td>{{ $language['CategoryName'] }}</td>
            <td>{{ $language['language'] }}</td>
            <td>
                @if((int)$language['status'] === 1)
                Active
                @else
                Inactive
                @endif
            </td>
            <td>{{ HTML::link('#','Edit', array('class' => 'btn btn-info edit-lang','id' =>$language['id'])) }}</td>
            <td>{{ HTML::link('#', 'View Questions', array('class'=>'btn btn-info listlang-qst','id' =>$language['id']))}}</td>
            <td>{{ HTML::link('#', 'Delete', array('class'=>'btn btn-danger del-lang','id' =>$language['id']))}}</td>        
          
        </tr>
        @endforeach
        </tbody>
    </table>
    <p> <?php echo $lang->links(); ?></p>

     

  <script type="text/javascript">
  $(document).ready(function(e){

    // making pagination links horizontal
    $(".pagination").attr("id","navlist");

        // ajax post call for delete
        $('.del-lang').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            $.post('delete/',{id:id},function(data){
            console.log(data);
            if(data.status){
                     $('<p class="alert">'+data.msg+'</p>').insertAfter('.wel_msg');
                     $('#row'+id).remove();
                 }
             });
        });

        $('.edit-lang').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            window.location="http://localhost:8000/languages/edit/"+id;
        });

        $('.listlang-qst').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            window.location="http://localhost:8000/questions/list/"+id;
        });

        

        
    });

  </script>
    