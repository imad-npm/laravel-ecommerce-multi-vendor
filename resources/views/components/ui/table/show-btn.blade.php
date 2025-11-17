
@props(['route'=>"","text"=>null])

<a  href="{{$route}}"
    class=" rounded text-secondary font-medium ">
   {{$text?? "show"}}

 </a>