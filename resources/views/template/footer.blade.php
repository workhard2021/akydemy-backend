<footer>
  <div class="item-reseaux">
    <a href="{{config('app.youtube')}}">
       <img src="{{asset(images/youtube.png)}}" alt="..."/>
    </a>
    <a href="{{config('app.linkedin')}}">
       <img src="{{asset(images/linkedin.png)}}" alt="..."/>
    </a>
    <a href="{{config('app.twitter')}}">
         <img src="{{asset(images/twitter.png)}}" alt="..."/>
    </a>
    <a href="{{config('app.facebook')}}">
         <img src="{{asset(images/facebook.png)}}" alt="..."/>
    </a>
  </div>
  <p class="nom-site">&copy; <a href="{{config('app.frontend_url')}}">{{config('app.name')}}</a> - {{now()->format('Y')}} </p>
</footer>
</body>
</html>