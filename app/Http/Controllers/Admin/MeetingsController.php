<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyMeetingRequest;
use App\Http\Requests\StoreMeetingRequest;
use App\Http\Requests\UpdateMeetingRequest;
use App\Priority;
use App\Status;
use App\Meeting;
use App\User;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Mockery\Undefined;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class MeetingsController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Meeting::with(['status', 'priority', 'category', 'assigned_to_user', 'comments'])
                ->filterTickets($request)
                ->select(sprintf('%s.*', (new Meeting)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'ticket_show';
                $editGate      = 'ticket_edit';
                $deleteGate    = 'ticket_delete';
                $crudRoutePart = 'meetings';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });
            $table->addColumn('status_color', function ($row) {
                return $row->status ? $row->status->color : '#000000';
            });

            
            $table->addColumn('att', function ($row) {
                $att= [];
                $att=DB::table('media')
                ->where('model_id',$row->id)
                ->select('name')
                ->get();
                $sa=json_decode($att);
                $v=[];
                $i=0;
                foreach ($att as $att)
                {
                $v[$i]=$att->name;
                $i=$i+1;
                }
                // $row->$att=$v;
                return  $v;
            });





            $table->addColumn('priority_name', function ($row) {
                return $row->priority ? $row->priority->name : '';
            });
            $table->addColumn('priority_color', function ($row) {
                return $row->priority ? $row->priority->color : '#000000';
            });

            $table->addColumn('category_name', function ($row) {
                return $row->category ? $row->category->name : '';
            });
            $table->addColumn('category_color', function ($row) {
                return $row->category ? $row->category->color : '#000000';
            });

            $table->editColumn('author_name', function ($row) {
                return $row->author_name ? $row->author_name : "";
            });
            $table->editColumn('author_email', function ($row) {
                return $row->author_email ? $row->author_email : "";
            });
            $table->addColumn('assigned_to_user_name', function ($row) {
                return $row->assigned_to_user ? $row->assigned_to_user->name : '';
            });

            $table->addColumn('user', function ($row) {
                
                $Users_Array = [];
                $i=0;
             foreach ($row->user as $value) {
                
                
                $users = User::where('id',"$value")
                ->pluck('name');
                $Users_Array[$i]= $users;
                    $i=1+$i;

                
              } 
                
                return $Users_Array;
               // return $row->user ? $row->user : '';
            });
            $table->addColumn('comments_count', function ($row) {
                return $row->comments->count();
            });

            $table->addColumn('view_link', function ($row) {
                return route('admin.meetings.show', $row->id);
            });

            $table->rawColumns(['actions', 'placeholder', 'status', 'priority', 'category', 'assigned_to_user']);

            return $table->make(true);
        }

        $priorities = Priority::all();
        $statuses = Status::all();
        $categories = Category::all();
        $atts=[];
        $atts=DB::table('media')->where('model_id','73')->get();


        return view('admin.tickets.index', compact('priorities', 'statuses', 'categories'));
    }

    public function create()
    {
        abort_if(Gate::denies('ticket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $priorities = Priority::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

      


        $assigned_to_users = User::whereHas('roles', function($query) {
                $query->whereId(2);
            })
            ->pluck('name', 'id');

        return view('admin.tickets.create', compact('statuses', 'priorities', 'categories', 'assigned_to_users'));
    }

    public function store(StoreMeetingRequest $request)
    {
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
        // foreach ($request->input('attachments', []) as $file) {
        //     $meeting->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        // }

        return redirect()->route('admin.meetings.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('ticket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $statuses = Status::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $priorities = Priority::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $categories = Category::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $assigned_to_users = User::whereHas('roles', function($query) {
                $query->whereId(2);
            })
            ->get();


    $meeting=Meeting::find($id);
    // $meeting->user=
        $meeting->load('status', 'priority', 'category', 'assigned_to_user');
         $users=collect();
        foreach($meeting->user as $user_id){
            
       $users->push(User::find($user_id));
      
     }
     $pdfs=DB::table('media')->where('model_id',$meeting->id)->get();
// return $users;

        return view('admin.tickets.edit', compact('statuses', 'priorities', 'categories', 'assigned_to_users', 'meeting','users','pdfs'));
    }

    public function update(UpdateMeetingRequest $request, Meeting $meeting)
    {
        // dd($request);
        $meeting->update($request->all());
        $medias=DB::table('media')->where('model_id',$meeting->id)->get();

        foreach($medias as $media)
        { 
            if(empty($request->pdfs))
            {
                DB::table('media')->where('id',$media->id)->delete();
            }
        
         else if( !in_array( $media->id, $request->pdfs))
          {
             DB::table('media')->where('id',$media->id)->delete();
          }
        }

        if (count($meeting->attachments) > 0) {
            
            foreach ($meeting->attachments as $media) {
                if (!in_array($media->file_name, $request->input('attachments', []))) {
                    $media->delete();
                }
            }
        }
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

        // $media = $meeting->attachments->pluck('file_name')->toArray();

        // foreach ($request->input('attachments', []) as $file) {
        //     if (count($media) === 0 || !in_array($file, $media)) {
        //         $meeting->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('attachments');
        //     }
        // }

        return redirect()->route('admin.meetings.index');
    }

    public function show($id)
    {
        abort_if(Gate::denies('ticket_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $meeting=Meeting::find($id);
        $meeting->load('status', 'priority', 'category', 'assigned_to_user', 'comments');
        $attachments=DB::table('media')->where('model_id',$meeting->id)->pluck('name');
        $sa= $meeting->created_at;
        $meeting->date=Carbon::parse($sa)->format('d/m/Y');
        return view('admin.tickets.show', compact('meeting','attachments'));
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('ticket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $meeting=Meeting::find($id);
        $meeting->delete();

        return back();
    }

    public function massDestroy(MassDestroyMeetingRequest $request)
    {
        Meeting::whereIn('id', $request->input('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeComment(Request $request, Meeting $meeting)
    {
        $request->validate([
            'comment_text' => 'required'
        ]);
        $user = auth()->user();
        $comment = $meeting->comments()->create([
            'author_name'   => $user->name,
            'author_email'  => $user->email,
            'user_id'       => $user->id,
            'comment_text'  => $request->comment_text
        ]);

       
        return redirect()->back()->withStatus('Your comment added successfully');
    }
}
