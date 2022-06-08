@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.meeting.title') }}
    </div>

    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    
                    <tr>
                        <th>
                            {{ trans('cruds.meeting.fields.created_at') }}
                        </th>
                        <td>
                            {{ $meeting->date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meeting.fields.title') }}
                        </th>
                        <td>
                            {{ $meeting->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meeting.fields.content') }}
                        </th>
                        <td>
                            {!! $meeting->content !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meeting.fields.attachments') }}
                        </th>
                        <td>
                            @foreach ($attachments as $attachments)
                    
                    <div class="row">
                    <a href="/pdfview/{{$attachments}}/{{  $meeting->id }}" class="mb-1" style="font-size:7px">&nbsp&nbsp{{$attachments}}</a>
                    </div>
                    
                     
                    @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.meeting.fields.status') }}
                        </th>
                        <td>
                            {{ $meeting->status->name ?? '' }}
                        </td>
                    </tr>
                    
                     
                    
                </tbody>
            </table>
        </div>
        @isset($_GET['pdfname'])
        <div class="mb-2">
            <h1>Test</h1>
        </div>
        @endisset
       
        <a class="btn btn-default my-2" href="{{ route('admin.meetings.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        @if(Auth::user()->roles[0]->id==1)
       
        <a href="{{ route('admin.meetings.edit', $meeting->id) }}" class="btn btn-primary">
            @lang('global.edit') @lang('cruds.meeting.title_singular')
        </a>

        @endif
        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
     
     
        <div>
       
</div>
    </div>
</div>
@endsection