<footer>
  <div class="item-reseaux">
    <a href="{{config('app.youtube')}}">
       <img src="{{url('images/youtube.png')}}" alt="..."/>
    </a>
    <a href="{{config('app.linkedin')}}">
       <img src="{{url('images/linkedin.png')}}" alt="..."/>
    </a>
    <a href="{{config('app.twitter')}}">
         <img src="{{url('images/twitter.png')}}" alt="..."/>
    </a>
    <a href="{{config('app.facebook')}}">
         <img src="{{url('images/facebook.png')}}" alt="..."/>
    </a>
  </div><br/>
  <p class="nom-site">&copy; <a href="{{config('app.frontend_url')}}">{{config('app.name')}}</a> - {{now()->format('Y')}} </p>
</footer>
</body>
</html>