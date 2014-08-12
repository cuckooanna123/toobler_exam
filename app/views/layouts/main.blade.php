<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <title>InternalExam</title>
    {{ HTML::style('packages/bootstrap/css/bootstrap.min.css') }}
    {{ HTML::style('css/main.css')}}
    {{ HTML::script('js/jquery.js')}}
    {{ HTML::script('js/jquery.validate.js')}}
  </head>
 
  <body>
    
 <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <ul class="nav">  
                @if(Session::has('userId'))

                    @if(Session::get('userType') == 'admin')
                    <li>{{ HTML::link('admin/dashboard', 'Dashboard') }}</li>  
                    <li>{{ HTML::link('admin/settings', 'Settings') }}</li>
                    <li>{{ HTML::link('languages/list', 'Languages') }}</li>
                    <li>{{ HTML::link('admin/userlist', 'Users') }}</li>
                    @endif
                    <li>{{ HTML::link('admin/logout','Logout', array('class' => 'btn btn-warning'))}}</li>        
                @else
                   <li>{{ HTML::link('admin/login', 'Admin Login') }}</li> 
                   <li>{{ HTML::link('users/login', 'User Login') }}</li> 
                @endif
            </ul>  
        </div>
    </div>
</div>

    <div class="container">
        <div class="row">
            @if(Session::has('message'))
                <p class="alert">{{ Session::get('message') }}</p>
            @endif
        </div>
    </div>

    {{ $content }}
  </body>
  