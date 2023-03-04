@include('template.header')
<div class="container-body">
  @if(isset($data['first_name']) &&  isset($data['last_name']))
    <div>
      {!! $data['first_name']?? '' !!} {!! $data['last_name']?? '' !!}
   </div>
   @endif
   <div>
      {!! $data['description']?? '' !!}
   </div>
</div>
@include('template.footer')