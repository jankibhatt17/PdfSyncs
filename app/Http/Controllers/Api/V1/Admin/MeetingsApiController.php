<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Http\Resources\Admin\MeetingResource;
use App\Meeting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class MeetingsApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('ticket_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MeetingResource(Meeting::with(['status', 'priority', 'category', 'assigned_to_user'])->get());
    }

    public function store(StoreMeetingRequest $request)
    {
        $meeting = Meeting::create($request->all());

        if ($request->input('attachments', false)) {
            $meeting->addMedia(storage_path('tmp/uploads/' . $request->input('attachments')))->toMediaCollection('attachments');
        }

        return (new MeetingResource($meeting))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Meeting $meeting)
    {
        abort_if(Gate::denies('ticket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new MeetingResource($meeting->load(['status', 'priority', 'category', 'assigned_to_user']));
    }

    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        $meeting->update($request->all());

        if ($request->input('attachments', false)) {
            if (!$meeting->attachments || $request->input('attachments') !== $meeting->attachments->file_name) {
                $meeting->addMedia(storage_path('tmp/uploads/' . $request->input('attachments')))->toMediaCollection('attachments');
            }
        } elseif ($meeting->attachments) {
            $meeting->attachments->delete();
        }

        return (new MeetingResource($meeting))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Meeting $meeting)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $meeting->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
