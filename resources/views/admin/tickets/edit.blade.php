@extends('layouts.admin')

@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.meeting.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.meetings.update", [$meeting->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title">{{ trans('cruds.meeting.fields.title') }}*</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', isset($meeting) ? $meeting->title : '') }}" required>
                @if($errors->has('title'))
                    <em class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.meeting.fields.title_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                <label for="content">{{ trans('cruds.meeting.fields.content') }}</label>
                <textarea id="content" name="content" class="form-control ">{{ old('content', isset($meeting) ? $meeting->content : '') }}</textarea>
                @if($errors->has('content'))
                    <em class="invalid-feedback">
                        {{ $errors->first('content') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.meeting.fields.content_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('attachments') ? 'has-error' : '' }}">
                <label for="attachments">{{ trans('cruds.meeting.fields.attachments') }}</label>
                <input type="file" class="form-control @error('files') is-invalid @enderror" id="files" name="files[]" multiple />
                @error('files')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
                <p class="helper-block">
                    {{ trans('cruds.meeting.fields.attachments_helper') }}
                </p>
                <select id="pdf" name="pdfs[]" multiple="multiple" class="form-control select2"   multiple="true">
                    @foreach ($pdfs as $pdf)
                    <option value="{{ $pdf->id }}" selected>{{ $pdf->name }}</option> 
                    @endforeach
  
                </select>
            </div>
            <div class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }}">
                <label for="status">{{ trans('cruds.meeting.fields.status') }}*</label>
                <select name="status_id" id="status" class="form-control select2" required>
                    @foreach($statuses as $id => $status)
                        <option value="{{ $id }}" {{ (isset($meeting) && $meeting->status ? $meeting->status->id : old('status_id')) == $id ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                @if($errors->has('status_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('status_id') }}
                    </em>
                @endif
            </div>
            <!-- <div class="form-group {{ $errors->has('priority_id') ? 'has-error' : '' }}">
                <label for="priority">{{ trans('cruds.meeting.fields.priority') }}*</label>
                <select name="priority_id" id="priority" class="form-control select2" required>
                    @foreach($priorities as $id => $priority)
                        <option value="{{ $id }}" {{ (isset($meeting) && $meeting->priority ? $meeting->priority->id : old('priority_id')) == $id ? 'selected' : '' }}>{{ $priority }}</option>
                    @endforeach
                </select>
                @if($errors->has('priority_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('priority_id') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('category_id') ? 'has-error' : '' }}">
                <label for="category">{{ trans('cruds.meeting.fields.category') }}*</label>
                <select name="category_id" id="category" class="form-control select2" required>
                    @foreach($categories as $id => $category)
                        <option value="{{ $id }}" {{ (isset($meeting) && $meeting->category ? $meeting->category->id : old('category_id')) == $id ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                @if($errors->has('category_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('category_id') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('author_name') ? 'has-error' : '' }}">
                <label for="author_name">{{ trans('cruds.meeting.fields.author_name') }}</label>
                <input type="text" id="author_name" name="author_name" class="form-control" value="{{ old('author_name', isset($meeting) ? $meeting->author_name : '') }}">
                @if($errors->has('author_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('author_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.meeting.fields.author_name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('author_email') ? 'has-error' : '' }}">
                <label for="author_email">{{ trans('cruds.meeting.fields.author_email') }}</label>
                <input type="text" id="author_email" name="author_email" class="form-control" value="{{ old('author_email', isset($meeting) ? $meeting->author_email : '') }}">
                @if($errors->has('author_email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('author_email') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.meeting.fields.author_email_helper') }}
                </p>
            </div> -->
            @if(auth()->user()->isAdmin())
            
            @endif 
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection

@section('scripts')
<script>
    var uploadedAttachmentsMap = {}
Dropzone.options.attachmentsDropzone = {
    url: '{{ route('admin.tickets.storeMedia') }}',
    maxFilesize: 2, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
      uploadedAttachmentsMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedAttachmentsMap[file.name]
      }
      $('form').find('input[name="attachments[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($meeting) && $meeting->attachments)
          var files =
            {!! json_encode($meeting->attachments) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@stop