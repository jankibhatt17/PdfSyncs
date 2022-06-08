<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Notifications\CommentEmailNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MeetingController extends Controller
{
    use MediaUploadingTrait;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required',
            'content'       => 'required',
            'author_name'   => 'required',
            'author_email'  => 'required|email',
            "files.*" => "mimes:pdf|max:10000"
        ]);
        
       

        $request->request->add([
            'category_id'   => 1,
            'status_id'     => 1,
            'priority_id'   => 1
        ]);

        $meeting = Meeting::create($request->all());
        if($request->file('files')!=null ){
        foreach ($request->file('files') as $file) {
            // $meeting->addMedia(public_path($file))->toMediaCollection('attachments');
           

            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            
    
            $path=$file->move(public_path('pdfjs-2.1.266-dist/web'), $name);
            DB::table('media')->insert(
                [
                    'model_type'=>'App/Meeting',
                    'model_id'=>$meeting->id,
                    'uuid'=>'null',
                    'collection_name'=>'attachments',
                    'name'=>$name,
                    'file_name'=>$name,
                    'mime_type'=>'application/pdf',
                    'disk'=>'public',
                    'conversions_disk'=>'public',
                    'size'=> '56765',
                    'manipulations'=>'[]',
                    'custom_properties'=>'[]',
                    'responsive_images'=>'[]',
                    'order_column'=>'8',
                ]
             );
         }
        }
       
        //  foreach($request->files as $file)
        //  {
        //     $file->move(public_path('pdfjs-2.1.266-dist/web'), $file);
        //  }

        return redirect()->back()->withStatus('Your meeting has been submitted, we will be in touch. You can view meeting status <a href="'.route('meetings.show', $meeting->id).'">here</a>');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Meeting  $meeting
     * @return \Illuminate\Http\Response
     */
    public function show(Meeting $meeting)
    {
        $meeting->load('comments');

        return view('tickets.show', compact('meeting'));
    }

    public function storeComment(Request $request, Meeting $meeting)
    {
        $request->validate([
            'comment_text' => 'required'
        ]);

        $comment = $meeting->comments()->create([
            'author_name'   => $meeting->author_name,
            'author_email'  => $meeting->author_email,
            'comment_text'  => $request->comment_text
        ]);

      
        return redirect()->back()->withStatus('Your comment added successfully');
    }
}
