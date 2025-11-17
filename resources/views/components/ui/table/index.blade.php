
@props(['columns'=>[],"actions"=>true])

<div class="rounded-lg  bg-white border  shadow-md mb-5 mt-5 
  overflow-hidden">
<table class=" w-full">
<thead >   <tr class="bg-slate-100  ">
    @foreach ($columns as $column)
 
        <th class="py-3 px-2 ">{{$column}}</th>
     
    @endforeach 
 
    @if ($actions)
         <th>Actions</th>
    @endif
    
  </tr>
</thead>
<tbody>
  @if ($slot->isEmpty())
      <tr class="h-24  ">
        <td colspan="100%" class="text-center">No Content .</td>
      </tr>
  @endif
  {{$slot}}
</tbody>

</table></div>