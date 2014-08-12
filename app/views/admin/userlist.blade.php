<h1>Users List</h1>
 
<p class="wel_msg">Welcome to users list! {{ HTML::link('admin/newuser','Add User', array('class' => 'btn btn-info')) }}</p>

@if (count($userArray) > 0)
	<table class="table table-striped table-bordered">
        <thead>
        <tr>
        <th>Email</th>
        <th>Fullname</th>
        <th>Category</th>
        <th>Language</th>
        <th>Status</th>
        <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($userArray as $user)
        <tr id="row{{$user['id']}}">
        
			<td>{{ $user['email'] }}</td>
            <td>{{ $user['fullname'] }}</td>
            <td>
                @if(isset($user['categoryName']))
                {{ $user['categoryName'] }}
                 @endif
            </td>
           
            <td>
                @if(isset($user['langName']))
                {{ $user['langName'] }}
                 @endif
            </td>
           
            <td> @if($user['enable'] == 1)
                Active
                @else
                Inactive
                @endif
                 </td>
            <td>{{ HTML::link('#','Edit', array('class' => 'btn btn-info edit-user','id' =>$user['id'])) }}</td>
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

       $('.edit-user').click(function(e){
            var id = e.target.id;
            console.log("id:"+id)
            e.preventDefault();
            window.location="http://localhost:8000/admin/edituser/"+id;
        });

        

        
    });

  </script>