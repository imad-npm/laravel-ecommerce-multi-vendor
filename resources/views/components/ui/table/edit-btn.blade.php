@props(['route'=>"","text"=>null])

  
<a  href="{{$route}}"
    class=" rounded text-primary font-medium mx-1 ">
   {{$text?? "Edit"}}

 </a>