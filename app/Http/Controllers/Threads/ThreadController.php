<?php

namespace App\Http\Controllers\Threads;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThreadRequest;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
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
}
