@can($viewGate)
@if ( Auth::user()->roles[0]->id==1 && DB::table('media')->where('model_id',$row->id)->exists())
<a class="btn btn-xs btn-primary" href="/pdfview/{{ $row->id }}" >
Visualizza
</a>
@elseif( Auth::user()->roles[0]->id==2 && DB::table('meetings')->where('id',$row->id)->exists())

<a class="btn btn-xs btn-primary" href="/pdfview/{{ $row->id }}" >
Visualizza
</a>

 @php 
//$value = DB::table('media')
  //              ->where('model_id',$row->id)
    //            ->pluck('name');
                     
  //echo $value;

  //@endphp
  





              




@else

@endif    
@endcan









@can($editGate)
    <a class="btn btn-xs btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
        {{ trans('global.edit') }}
    </a>
    <a class="btn btn-xs btn-dark" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
    lista PDF
    </a>
@endcan
@can($deleteGate)
    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
    </form>
@endcan