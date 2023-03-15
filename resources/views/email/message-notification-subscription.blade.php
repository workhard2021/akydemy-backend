@include('template.header')
  <div class="container-body">
    <!-- <h4>{{$msg["title"]}}</h4> -->
    <div>
       {{$msg["description"]?? ''}}
    </div><br/>
    <a id="consulter_compte" href="{{config('app.frontend_url').'/user/user-index'}}">Votre compte</a>
  </div><br/>
@include('template.footer')