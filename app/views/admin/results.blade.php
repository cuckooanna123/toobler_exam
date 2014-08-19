<h1>Exam Results</h1>

@if (count($userArray) > 0)
	<table class="table table-striped table-bordered">
        <thead>
        <tr>
        <th>Email</th>
        <th>Fullname</th>
        <th>Details</th>
        <th>Download</th>
        <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($userArray as $user)
        <tr id="row{{$user['id']}}">
        
			<td>{{ $user['email'] }}</td>
            <td>{{ $user['fullname'] }}</td>
            <td>
               {{ HTML::link('#','Result Details', array('class' => 'btn btn-info res-det','id' =>$user['id'])) }} 
            </td>
           
            <td> 
                {{ HTML::link('#','download pdf', array('class' => 'btn btn-info pdf-det','id' =>$user['id'])) }} 
                
            </td>
           
            <td> 
                 {{ HTML::link('#','email', array('class' => 'btn btn-info email-det','id' =>$user['id'])) }} 
             </td>
            
        </tr>
        @endforeach
        </tbody>
    </table>
    <p> <?php echo $users->links(); ?></p>
    @else
    No users found
    @endif


<script type="text/javascript">
  $(document).ready(function(e){

    // making pagination links horizontal
    $(".pagination").attr("id","navlist");

       $('.res-det').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            window.location="http://localhost:8000/result/details/"+id;
        });

       $('.pdf-det').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            window.location="http://localhost:8000/download/pdf/"+id;
        });
        

        
    });

  </script>