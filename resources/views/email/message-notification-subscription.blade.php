@include('template.header')
  <div class="container-body">
    <h4>{{$msg["title"]}}</h4>
    <p>
       {{$msg["description"]}}
    </p>
    <a id="consulter_compte" href="{{config('app.frontend_url').'/user/profil'}}">Consultez votre compte</a>
  </div>
@include('template.footer')