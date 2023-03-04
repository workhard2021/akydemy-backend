<footer>
  <div class="item-reseaux">
    <a href="{{config('app.youtube')}}">
      <img src="images/youtube.png"  alt="..."/>
    </a>
    <a href="{{config('app.linkedin')}}">
       <img src="images/linkedin.png"  alt="..."/>
    </a>
    <a href="{{config('app.twitter')}}">
         <img src="images/twitter.png"  alt="..."/>
    </a>
    <a href="{{config('app.facebook')}}">
       <img src="images/facebook.png"  alt="..."/>
    </a>
  </div>
  <p class="nom-site">&copy; <a href="{{config('app.frontend_url')}}">{{config('app.name')}}</a> - {{now()->format('Y')}} </p>
</footer>
</body>
</html>