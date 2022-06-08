@extends('layouts.admin')
@section('content')
@can('ticket_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.meetings.create") }}">
            Aggiungi Meeting
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
    Meeting lista
    </div>
    <button style="display:none;" type="button" class="btn btn-primary reload float-right mb-3">Reload</button>

    <div class="card-body">
         <table id="ID" class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Ticket">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    
                    <th>
                        {{ trans('cruds.meeting.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.meeting.fields.status') }}
                    </th>
                    
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table> 


    </div>
</div>

@endsection
@section('scripts')
@parent
<script>





    $(function () {
let filters = `
<form class="form-inline" id="filtersForm">
  <div class="form-group mx-sm-3 mb-2">
    <select class="form-control" name="status">
      <option value="">All Stato </option>
      @foreach($statuses as $status)
        <option value="{{ $status->id }}"{{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
      @endforeach
    </select>
  </div>
  
</form>`;
$('.card-body').on('change', 'select', function() {
  $('#filtersForm').submit();
})
  let dtButtons = []
@can('ticket_delete')

  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.meetings.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan
  let searchParams = new URLSearchParams(window.location.search)
  let dtOverrideGlobals = {
    buttons: dtButtons,
    serverSide: true,
    retrieve: true,
    aaSorting: [],

    ajax: {
      url: "{{ route('admin.meetings.index') }}",
      
      data: {
        'status': searchParams.get('status')
        
      }
    },

    columns: [
       
      { data: 'placeholder', name: 'placeholder' },
// { data: 'id', name: 'id' },
{
    data: 'title',
    name: 'title', 
    render: function ( data, type, row) {
        return '<a href="'+row.view_link+'">'+data+'</a>';
    }
},
{ 
  data: 'status_name', 
  name: 'status.name', 
  render: function ( data, type, row) {
      return '<span style="color:'+row.status_color+'">'+data+'</span>';
  }
},


{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };   
//   $(".reload" ).click(function() {
//     $('#ID').DataTable().ajax.reload();

// }); 
$(".datatable-Ticket").one("preInit.dt", function () {
 $(".dataTables_filter").after(filters);
});
  $('.datatable-Ticket').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
});


window.onload =function meetingstart(){
// id= $('#meeting_id').val();
// top.location.reload();
// $('#ID').DataTable().ajax.reload();
// $('#ID').dataTable().api().ajax.reload();
 
 setTimeout(meetingstart, 5000);
 
};







</script>
@endsection