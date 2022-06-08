<?php

namespace App\Http\Requests;

use App\Meeting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'title'       => [
                'required',
            ],
            'status_id'   => [
               
                'integer',
            ],
            'priority_id' => [
               
                'integer',
            ],
            'category_id' => [
               
                'integer',
            ],
        ];
    }
}
