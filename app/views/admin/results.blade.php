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
        <input type="hidden" id="uid" value="<?php echo $user['id'];?>">
			<td>{{ $user['email'] }}</td>
            <td>{{ $user['fullname'] }}</td>
            <td>
               {{ HTML::link('#','Result Details', array('class' => 'btn btn-primary res-det','id' =>$user['id'])) }} 
            </td>
           
            <td> 
                {{ HTML::link('#','Download Pdf', array('class' => 'btn btn-primary pdf-det','id' =>$user['id'])) }} 
                
            </td>
           
            <td> 
                <!--- {{ HTML::link('#','Email Result', array('class' => 'btn btn-info email-det','id' =>$user['id'],'data-toggle'=>"modal",'data-target'=>"#offer-icon" )) }} -->
                <a data-toggle="modal" href="#" id="<?php echo $user['id'];?>"  class="btn btn-primary openBtn">Email Result</a>

            <div class="modal" id="myModal<?php echo $user['id'];?>" style="display: none;" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close close-modal" id="<?php echo $user['id'];?>"  data-dismiss="modal" aria-hidden="true">×</button>
                      <h4 class="modal-title">Email Result To</h4>
                    </div>
                    <div class="modal-body" id="modal-body<?php echo $user['id'];?>">
                      <input type="text" id="email1" value="<?php echo $user['email'];?>">
                      <input type="hidden" id="e-count" value="1">
                      <a href="#" class="btn btn-primary mv-right add-btn">Add Email</a>
                    </div>
                    <div class="modal-footer">
                      <a href="#" data-dismiss="modal" id="<?php echo $user['id'];?>" class="btn close-modal">Close</a>
                      <a href="#" class="btn btn-primary send" id="<?php echo $user['id'];?>">Send Email</a>
                    </div>
                  </div>
                </div>
            </div>

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
        
       $('.openBtn').click(function(e){
        var id = e.target.id;
        console.log("open..."+id);
        $('#myModal'+id).show();
        });

       $('.close-modal').click(function(e){
        var id = e.target.id;
        $('#myModal'+id).hide();
        });

       $('.add-btn').click(function(e){
        var id = e.target.id;
        var e_count = $('#e-count').val();
        var count = parseInt(e_count)+1;
        $('#e-count').val(count);
        var input = "<br><span id='span-email"+count+"'><input type='text'  id='email"+count+"' placeholder='enter email' value=''>"
        +"<button type='button' class='remove' id='email"+count+"' >×</button></span>";
        //console.log(input);
        $('.modal-body'+id).append(input);
        });

       $(document).on("click","'.remove",function(e){
        var rm_id = e.target.id;
        var e_count = $('#e-count').val();
        var count = parseInt(e_count)-1;
        $('#e-count').val(count);
        $('#span-'+rm_id).remove();
       });

       $('.send').click(function(e){
        var e_count = parseInt($('#e-count').val());
         console.log(e_count);
        var emailArray = new Array();
        var i;

        for (i = 1; i <= e_count; i++) {
        var email = $('#email'+i).val();
        console.log(email);
        emailArray.push(email);
        }
        //console.log(emailArray);
        var uid = $('#uid').val();

        var params = {
        uid:uid,
        emailArray: emailArray
      };


      // to save last loaded question's answer on finish button click
      $.post('/result/mail',params,function(data){
            console.log(data);
            if(data.status){
            console.log("mail send");
              }
             });

        });
        
    });

  </script>