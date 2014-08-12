<div class="container-fluid" style="border: solid 1px black; border-radius: 10px; max-width: 400px; padding: 15px;margin:5px">
    <div class="row-fluid" style="background-color: #f0f0f0; border-top-left-radius: 10px; border-top-right-radius: 10px;">
        <div class="span3">Name</div>
        <div class="span3">{{ $user['fullname']}}</div>
    </div>
    <div class="row-fluid">
        <div class="span3">Category</div>
        <div class="span3">
        	@if(isset($user['categoryName']))
                {{ $user['categoryName'] }}
                 @endif
        </div>
    </div>
    <div class="row-fluid">
        <div class="span3">Language</div>
        <div class="span3">
        	 @if(isset($user['langName']))
                {{ $user['langName'] }}
                 @endif
        </div>
    </div>
     <div class="row-fluid">
        <div class="span3">Maximum Question Count</div>
        <div class="span3">
        	 @if(isset($user['langName']))
                {{ $user['langName'] }}
                 @endif
        </div>
    </div>
</div>