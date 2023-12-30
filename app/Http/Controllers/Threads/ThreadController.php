<?php

namespace App\Http\Controllers\Threads;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThreadRequest;
use App\Http\Resources\ThreadResource;
use App\Models\Thread;
use App\Models\Like;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index()
    {
        try {
            $threads = Thread::with('user')->latest()->get();
            $threads = ThreadResource::collection($threads);
            return response([
                'threads' => $threads
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function store(ThreadRequest $threadRequest)
    {
        try {
            $threadRequest->validated();
            $data = [
                'body' => $threadRequest->body
            ];
            
            //check image
            if($threadRequest->hasFile('image')){
                $threadRequest->validate([
                    'image' => 'image'
                ]);
                $imagePath = 'public/images/threads';
                $image = $threadRequest->file('image');
                $image_name = $image->getClientOriginalName();
                $path = $threadRequest->file('image')->storeAs($imagePath, rand(0, 0) . $image_name);

                $data['image'] = $path;
            }
            $save = auth()->user()->threads()->create($data);
            if($save){
                return response([
                    'message' => 'success'
                ], 201);
            }else{
                return response([
                    'message' => 'error'
                ], 500);

            }


        } catch (\Exception $e) {
           return response([
            'message' => $e->getMessage()
           ], 500);
        }

    }

    public function react($thread_id)
    {
        try {
            $thread = Like::whereThreadId($thread_id)->whereUserId(auth()->id())->first();
            if($thread){
                Like::whereThreadId($thread_id)->whereUserId(auth()->id())->delete();
                return response([
                    'message' => 'unliked'
                ], 200);
            }else{
                Like::create([
                    'user_id' => auth()->id(),
                    'thread_id' => $thread_id
                ]);
                return response([
                    'message' => 'liked'
                ], 201);
            }
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage()
               ], 500);
        }

    }
}
