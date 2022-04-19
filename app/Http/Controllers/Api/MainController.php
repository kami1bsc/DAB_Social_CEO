<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Str;
use App\Like;
use App\Tag;
use App\Post;
use App\Comment;
use App\SavedPost;
use App\Notification;

class MainController extends Controller
{
    public function create_post(Request $request)
    {
        // dd($request->all());
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 400,
                        'message' => 'User with this ID does not exists!',
                        'data' => null,
                    ], 200);
                }
            }

            $post = new Post;

            $post->user_id = $request->user_id;
            $post->post_type = $request->post_type;

            if($request->has('image'))
            {
                $base64_image = $request->image;

                if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                    $data = substr($base64_image, strpos($base64_image, ',') + 1);
                    $data = base64_decode($data);     
                    $img = preg_replace('/^data:image\/\w+;base64,/', '', $base64_image);
                    $type = explode(';', $base64_image)[0];
                    $type = explode('/', $type)[1]; // png or jpg etc                

                    if($type == 'png' || $type == 'PNG' || $type == 'jpg' || $type == 'JPG' || $type == 'jpeg' || $type == 'JPEG')
                    {
                        $imageName = Str::random(10).'.'.$type;                   

                        \Storage::disk('post_images')->put($imageName, $data); // this disk is defined in config/filesystems.php under Disks section

                        $img_path = 'post_images/'.$imageName;   
                        $post->image = $img_path;                
                    }else{
                        return response()->json([
                            'status' => 400,
                            'message' => 'Please Choose a Valid Image!',
                            'data' => null,
                        ], 200);   
                    }         
                }              
            }
            
            // if($request->has('video'))
            // {
            //     $base64_image = $request->video;
                
            //     if (preg_match('/^data:video\/(\w+);base64,/', $base64_image)) {
            //         $data = substr($base64_image, strpos($base64_image, ',') + 1);
            //         $data = base64_decode($data);     
            //         $img = preg_replace('/^data:image\/\w+;base64,/', '', $base64_image);
            //         $type = explode(';', $base64_image)[0];
            //         $type = explode('/', $type)[1]; // png or jpg etc                
                    
            //         if($type == 'mp4' || $type == 'MP4' || $type == 'mov' || $type == 'MOV' || $type == 'wmv' || $type == 'WMV' || $type == 'flv' || $type == 'FLV' || $type == 'avi' || $type == 'AVI' || $type == 'mkv' || $type == 'MKV')
            //         {
            //             $imageName = Str::random(10).'.'.$type;                   

            //             \Storage::disk('post_videos')->put($imageName, $data); // this disk is defined in config/filesystems.php under Disks section

            //             $img_path = 'post_videos/'.$imageName;    
            //             $post->video = $img_path;               
            //         }else{
            //             return response()->json([
            //                 'status' => 400,
            //                 'message' => 'Please Choose a Valid Video!',
            //                 'data' => null,
            //             ], 200);   
            //         }         
            //     }
            // }
            
            if($request->has('post_file'))
            {
                if($request->post_file->getClientOriginalExtension() == 'PNG' ||$request->post_file->getClientOriginalExtension() == 'png' || $request->post_file->getClientOriginalExtension() == 'JPG' || $request->post_file->getClientOriginalExtension() == 'jpg' || $request->post_file->getClientOriginalExtension() == 'jpeg' || $request->post_file->getClientOriginalExtension() == 'JPEG' || $request->post_file->getClientOriginalExtension() == 'MKV' ||$request->post_file->getClientOriginalExtension() == 'mkv' || $request->post_file->getClientOriginalExtension() == 'AVI' || $request->post_file->getClientOriginalExtension() == 'avi' || $request->post_file->getClientOriginalExtension() == 'FLV' || $request->post_file->getClientOriginalExtension() == 'flv' || $request->post_file->getClientOriginalExtension() == 'MP4' ||$request->post_file->getClientOriginalExtension() == 'mp4' || $request->post_file->getClientOriginalExtension() == 'WMV' || $request->post_file->getClientOriginalExtension() == 'wmv' || $request->post_file->getClientOriginalExtension() == 'MOV' || $request->post_file->getClientOriginalExtension() == 'mov')
                {
                    $newfilename = md5(mt_rand()) .'.'. $request->post_file->getClientOriginalExtension();
                    $request->file('post_file')->move(public_path("/post_files"), $newfilename);
                    $new_path1 = 'post_files/'.$newfilename;
                    $post->post_file = $new_path1;
                }else{
                    return back()->with('error', 'Please Choose a Valid File');
                }     
            }
            
            if($request->has('title'))
            {
                $post->title = $request->title;
            }

            if($request->has('description'))
            {
                $post->description = $request->description;
            }

            if($request->has('network_post_type'))
            {
                $post->network_post_type = $request->network_post_type;
            }

            if($request->has('who_can_collaborate'))
            {
                $post->who_can_collaborate = $request->who_can_collaborate;
            }

            if($request->has('category'))
            {
                $post->category = $request->category;
            }

            if($request->has('price'))
            {
                $post->price = $request->price;
            }

            if($request->has('link_of_product_or_service'))
            {
                $post->link_of_product_or_service = $request->link_of_product_or_service;
            }

            if($request->has('event_date'))
            {
                $post->event_date = $request->event_date;
            }
            
            if($request->has('available_seats'))
            {
                $post->available_seats = $request->available_seats;
            }

            if($request->has('location'))
            {
                $post->location = $request->location;
            }

            if($request->has('tags'))
            {
                $post->tags = $request->tags;
            }

            if($request->has('service_fee'))
            {
                $post->service_fee = $request->service_fee;
            }

            if($request->has('startup_investment'))
            {
                $post->startup_investment = $request->startup_investment;
            }

            if($request->has('pledge_goal_amount'))
            {
                $post->pledge_goal_amount = $request->pledge_goal_amount;
            }

            if($request->has('startup_end_date'))
            {
                $post->startup_end_date = $request->startup_end_date;
            }

            if($request->has('investment_type'))
            {
                $post->investment_type = $request->investment_type;
            }

            if($request->has('next_step_for_startup'))
            {
                $post->next_step_for_startup = $request->next_step_for_startup;
            }

            if($request->has('feel_about_next_step'))
            {
                $post->feel_about_next_step = $request->feel_about_next_step;
            }

            if($request->has('startup_option_1'))
            {
                $post->startup_option_1 = $request->startup_option_1;
            }

            if($request->has('startup_option_2'))
            {
                $post->startup_option_2 = $request->startup_option_2;
            }

            if($request->has('startup_option_3'))
            {
                $post->startup_option_3 = $request->startup_option_3;
            }

            if($request->has('how_far_along_startup'))
            {
                $post->how_far_along_startup = $request->how_far_along_startup;
            }

            if($request->has('money_need_for_startup'))
            {
                $post->money_need_for_startup = $request->money_need_for_startup;
            }

            if($request->has('planned_time_for_startup'))
            {
                $post->planned_time_for_startup = $request->planned_time_for_startup;
            }

            if($request->has('do_you_have_enough_money'))
            {
                $post->do_you_have_enough_money = $request->do_you_have_enough_money;
            }

            if($request->has('access_to_startup_network'))
            {
                $post->access_to_startup_network = $request->access_to_startup_network;
            }

            if($post->save())
            {
                if($request->post_type == 'connect')
                {
                    $message = 'Post to Connect Successfully!';
                }else if($request->post_type == 'network')
                {
                    $message = 'Post for Network Created Successfully!';
                }else if($request->post_type == 'event')
                {
                    $message = 'Event Posted Successfully!';
                }else if($request->post_type == 'startup')
                {
                    $message = 'Startup Posted Successfully';
                }else{
                    $message = 'Post Created Successfully!';
                }

                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 200,
                        'message' => $message,
                        'data' => $post->makeHidden(['created_at', 'updated_at']),
                    ], 200);
                }
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => $e->getMessage(),
                ], 200);
            }
        }
    }

    public function get_connect_posts(Request $request)
    {
        try{
            $posts = Post::where('post_type', 'connect')->orderBy('created_at', 'desc')->get(['id', 'user_id', 'post_type', 'image', 'description', 'created_at']);
            
            if($posts->count() > 0)
            {
                foreach($posts as $post)
                {
                    $post['total_likes'] = Like::where('post_id', $post->id)->count();
                    
                    $liked = Like::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($liked))
                    {
                        $post['is_liked'] = 'true';
                    }else{
                        $post['is_liked'] = 'false';
                    }
                    
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    $post['comments'] = Comment::where('post_id', $post->id)->get(['id', 'user_id','comment']); 
                    
                    if(sizeof($post['comments']) > 0)
                    {
                        foreach($post['comments'] as $comment)
                        {
                            $user = User::find($comment->user_id);
                            $comment->user_name = $user->name;
                            $comment->profile_image = $user->profile_image;
                        }
                    }
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id','name', 'profile_image', 'token']);
                }
            }

            return response()->json([
                'status' => $posts->count() > 0 ? 200 : 400,
                'message' => $posts->count() > 0 ? 'Posts for Connect Found!' : 'No Post for Connect Found!',
                'data' => $posts->count() > 0 ? $posts : null,
            ], 200);       
            
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_network_posts(Request $request)
    {
        try{
            $posts = Post::where('post_type', 'network')->orderBy('created_at', 'desc')->get(['id', 'user_id', 'post_type', 'image', 'description', 'network_post_type', 'who_can_collaborate', 'created_at']);
            
            if($posts->count() > 0)
            {
                foreach($posts as $post)
                {
                    $post['total_likes'] = Like::where('post_id', $post->id)->count();  
                    
                    $liked = Like::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($liked))
                    {
                        $post['is_liked'] = 'true';
                    }else{
                        $post['is_liked'] = 'false';
                    }
                    
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }

            return response()->json([
                'status' => $posts->count() > 0 ? 200 : 400,
                'message' => $posts->count() > 0 ? 'Posts for Network Found!' : 'No Post for Network Found!',
                'data' => $posts->count() > 0 ? $posts : null,
            ], 200);
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_buy_and_sell_posts(Request $request)
    {
        try{
            $posts = Post::where('post_type', 'buy_and_sell')->orderBy('created_at', 'desc')->get(['id', 'user_id', 'post_type', 'image', 'title', 'description', 'category', 'price', 'location','link_of_product_or_service','created_at']);
            
            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }
            
            return response()->json([
                'status' => $posts->count() > 0 ? 200 : 400,
                'message' => $posts->count() > 0 ? 'Buy & Sell Posts Found!' : 'No Post for Buy & Sell Found!',
                'data' => $posts->count() > 0 ? $posts : null,
            ], 200);           
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_events_posts(Request $request)
    {
        try{
            $posts = Post::where('post_type', 'event')->orderBy('created_at', 'desc')->get(['id', 'user_id', 'post_type', 'image', 'title', 'category', 'price', 'location','event_date', 'description', 'tags', 'service_fee', 'created_at']);
        
            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }
            
            return response()->json([
                'status' => $posts->count() > 0 ? 200 : 400,
                'message' => $posts->count() > 0 ? 'Events Posts Found!' : 'No Event Post Found!',
                'data' => $posts->count() > 0 ? $posts : null,
            ], 200);
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_podcast_posts(Request $request)
    {
        try{
            $posts = Post::where('post_type', 'podcast')->orderBy('created_at', 'desc')->get(['id', 'user_id', 'post_type', 'image', 'title', 'category', 'description', 'tags', 'service_fee', 'created_at']);
        
            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }
            
            return response()->json([
                'status' => $posts->count() > 0 ? 200 : 400,
                'message' => $posts->count() > 0 ? 'Podcast Posts Found!' : 'No Podcast Post Found!',
                'data' => $posts->count() > 0 ? $posts : null,
            ], 200);
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_startup_posts(Request $request)
    {
        try{
            $posts = Post::where('post_type', 'startup')->orderBy('created_at', 'desc')->get(['id', 'user_id', 'post_type', 'image', 'title', 'startup_investment', 'location','pledge_goal_amount', 'startup_end_date', 'description', 'investment_type', 'next_step_for_startup', 'backers','feel_about_next_step', 'startup_option_1', 'startup_option_2', 'startup_option_3', 'how_far_along_startup', 'money_need_for_startup', 'planned_time_for_startup', 'do_you_have_enough_money', 'access_to_startup_network', 'service_fee']);
            
            
            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }

                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);

                }
            }
            
            
            return response()->json([
                'status' => $posts->count() > 0 ? 200 : 400,
                'message' => $posts->count() > 0 ? 'Startup Posts Found!' : 'No Startup Post Found!',
                'data' => $posts->count() > 0 ? $posts : null,
            ], 200);
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => $e->getMessage(),
            ], 200);
        }
    }

    public function like_post(Request $request)
    {
        try{
            $liked_by = User::find($request->liked_by);
            if(empty($liked_by))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Liked By does not exists!',
                    'data' => null,
                ], 200);
            }

            $post = Post::find($request->post_id);
            if(empty($post))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Post does not exists!',
                    'data' => null,
                ], 200);
            }

            $liked = Like::where('user_id', $request->liked_by)
            ->where('post_id', $request->post_id)
            ->first();
            
            if(!empty($liked))
            {
                $liked->delete();
                
                return response()->json([
                    'status' => 200,
                    'message' => 'You Unliked Post!',
                    'data' => 'unliked',
                ]);
            }else{
                $like = new Like;
                $like->user_id = $request->liked_by;
                $like->post_id = $request->post_id;
                if($like->save())
                {
                    
                    //Start FCM Android Code 
                        
                    $token = User::find($post->user_id)->token;
                    
                    $notification_title = $liked_by->name." liked your Post!";
                    $json_data = array('priority'=>'HIGH','to'=>$token,'data'=>array('title'=>'Women in Business', 'message' => 'Like Notification', 'notification_type' => 'like', 'notification_title' => $notification_title));
                    
                    $data = json_encode($json_data);
                    //FCM API end-point
                    $url = 'https://fcm.googleapis.com/fcm/send';
                    //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
                    $server_key = 'AAAApxIA3H0:APA91bGMVzJHjZ4yBJSwKL4HKW_m5naCbG_BiXQ-ZJwIfNQ5ZEo1i82_UG91INBG-NDEcgYHXKBqiCKY81uyCinW6mUWs0P3GtIJSyA_FKJchb_dOkHFTUzTCsiuVpeFCk4EWCILU7gH';
                    //header with content_type api key
                    $headers = array(
                        'Content-Type:application/json',
                        'Authorization:key='.$server_key
                    );
                    //CURL request to route notification to FCM connection server (provided by Google)
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    $result = curl_exec($ch);
                    if ($result === FALSE) {
                        die('Oops! FCM Send Error: ' . curl_error($ch));
                    }
                    curl_close($ch);
                    
                    //End FCM Android Code
                    
                    $notification = new Notification;
                    $notification->user_id = $post->user_id;
                    $notification->notification_type = $post->post_type;
                    $notification->notification = $liked_by->name.' Liked your Post';
                    $notification->save();
                    
                    return response()->json([
                        'status' => 200,
                        'message' => 'You Liked Post!',
                        'data' => 'liked',
                    ], 200);
                }
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => $e->getMessage(),
            ], 200);
        }
    }

    public function comment_on_post(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $post = Post::find($request->post_id);
            if(empty($post))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Post does not exists!',
                    'data' => null,
                ], 200);
            }

            $comment = new Comment;
            $comment->user_id = $request->user_id;
            $comment->post_id = $request->post_id;
            $comment->comment = $request->comment;
            if($comment->save())
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'You Commented on This Post!',
                    'data' => null,
                ], 200);
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => $e->getMessage(),
            ], 200);
        }
    }

    public function save_post(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $post = Post::find($request->post_id);
            if(empty($post))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Post does not exists!',
                    'data' => null,
                ], 200);
            }

            $saved = SavedPost::where('user_id', $request->user_id)
            ->where('post_id', $request->post_id)
            ->first();
            
            if(!empty($saved))
            {
                if($saved->delete())
                {
                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Post Unsaved Successfully!',
                            'data' => null,
                        ], 200);
                    }
                }
            }else{
                $post = new SavedPost;
                $post->user_id = $request->user_id;
                $post->post_id = $request->post_id;
                if($post->save())
                {
                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Post Saved Successfully!',
                            'data' => null,
                        ], 200);
                    }
                }
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => $e->getMessage(),
            ], 200);
        }
    }

    public function get_saved_connect_posts(Request $request)
    {
        try{
            
            $res = array();
            
            $user = User::find($request->user_id);

            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $posts = Post::where('post_type', 'connect')
            // ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')->get(['id', 'user_id', 'post_type', 'image', 'description', 'created_at']);
        
            if($posts->count() > 0)
            {
                foreach($posts as $post)
                {
                    $post['total_likes'] = Like::where('post_id', $post->id)->count();
                    
                    $liked = Like::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($liked))
                    {
                        $post['is_liked'] = 'true';
                    }else{
                        $post['is_liked'] = 'false';
                    }
                    
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    $post['comments'] = Comment::where('post_id', $post->id)->get(['id', 'comment']); 
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }
            
            foreach($posts as $post)
            {
                if($post->is_saved == 'true')
                {
                    array_push($res, $post);
                }
            }
            
            
            return response()->json([
                'status' => !empty($res) ? 200 : 400,
                'message' => !empty($res) ? 'Saved Posts for Connect Found!' : 'No Saved Post for Connect Found!',
                'data' => !empty($res) ? $res : null,
            ], 200);    
            
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_saved_network_posts(Request $request)
    {
        try{
            $res = array();
            
            $user = User::find($request->user_id);

            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $posts = Post::where('post_type', 'network')
            // ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'user_id', 'post_type', 'image', 'description', 'network_post_type', 'who_can_collaborate', 'created_at']);
            
            if($posts->count() > 0)
            {
                foreach($posts as $post)
                {
                    $post['total_likes'] = Like::where('post_id', $post->id)->count();
                    
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }

            foreach($posts as $post)
            {
                if($post->is_saved == 'true')
                {
                    array_push($res, $post);
                }
            }
            
            return response()->json([
                'status' => !empty($res) ? 200 : 400,
                'message' => !empty($res) ? 'Saved Posts for Network Found!' : 'No Saved Post for Network Found!',
                'data' => !empty($res) ? $res : null,
            ], 200);   
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_saved_buy_and_sell_posts(Request $request)
    {
        try{
            $res = array();
            
            $user = User::find($request->user_id);

            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $posts = Post::where('post_type', 'buy_and_sell')
            // ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'user_id', 'post_type', 'image', 'title', 'description', 'category', 'price', 'link_of_product_or_service','created_at']);
            
            
            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }
            
            foreach($posts as $post)
            {
                if($post->is_saved == 'true')
                {
                    array_push($res, $post);
                }
            }
            
            
            // foreach($posts as $post)
            // {
            //     if($post->is_saved == 'true')
            //     {
            //         array_push($res, $post);
            //     }
            // }
            
            return response()->json([
                'status' => !empty($res) ? 200 : 400,
                'message' => !empty($res) ? 'Saved Posts for Buy and Sell Found!' : 'No Saved Post for Buy and Sell Found!',
                'data' => !empty($res) ? $res : null,
            ], 200);               
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_saved_events_posts(Request $request)
    {
        try{
            $res = array();
            
            $user = User::find($request->user_id);

            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $posts = Post::where('post_type', 'event')
            // ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'user_id', 'post_type', 'image', 'title', 'category', 'price', 'event_date', 'description', 'tags', 'service_fee', 'created_at']);
            
            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }
            
            foreach($posts as $post)
            {
                if($post->is_saved == 'true')
                {
                    array_push($res, $post);
                }
            }
            
            return response()->json([
                'status' => !empty($res) ? 200 : 400,
                'message' => !empty($res) ? 'Saved Posts for Events Found!' : 'No Saved Post for Events Found!',
                'data' => !empty($res) ? $res : null,
            ], 200);   
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_saved_podcast_posts(Request $request)
    {
        try{
            $res = array();
            
            $user = User::find($request->user_id);

            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $posts = Post::where('post_type', 'podcast')
            // ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'user_id', 'post_type', 'image', 'title', 'category', 'description', 'tags', 'service_fee', 'created_at']);
            
            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }
            
            foreach($posts as $post)
            {
                if($post->is_saved == 'true')
                {
                    array_push($res, $post);
                }
            }
            
            return response()->json([
                'status' => !empty($res) ? 200 : 400,
                'message' => !empty($res) ? 'Saved Posts for Podcast Found!' : 'No Saved Post for Podcast Found!',
                'data' => !empty($res) ? $res : null,
            ], 200);   
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_saved_startups_posts(Request $request)
    {
        try{
            $res = array();
            
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $posts = Post::where('post_type', 'startup')
            // ->where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'user_id', 'post_type', 'image', 'title', 'startup_investment', 'pledge_goal_amount', 'startup_end_date', 'description', 'investment_type', 'next_step_for_startup', 'feel_about_next_step', 'startup_option_1', 'startup_option_2', 'startup_option_3', 'how_far_along_startup', 'money_need_for_startup', 'planned_time_for_startup', 'do_you_have_enough_money', 'access_to_startup_network', 'service_fee']);
            
            if(!empty($posts))
            {
                foreach($posts as $post)
                {
                    $saved = SavedPost::where('user_id', $request->user_id)
                    ->where('post_id', $post->id)
                    ->first();
                    
                    if(!empty($saved))
                    {
                        $post['is_saved'] = 'true';
                    }else{
                        $post['is_saved'] = 'false';
                    }
                    
                    $post['user_details'] = User::where('id', $post->user_id)->first(['id', 'name', 'profile_image', 'token']);
                }
            }
            
            foreach($posts as $post)
            {
                if($post->is_saved == 'true')
                {
                    array_push($res, $post);
                }
            }
            
            return response()->json([
                'status' => !empty($res) ? 200 : 400,
                'message' => !empty($res) ? 'Saved Posts for Startups Found!' : 'No Saved Post for Startups Found!',
                'data' => !empty($res) ? $res : null,
            ], 200);   
        
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => $e->getMessage(),
            ], 200);
        }
    }

    public function user_details(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $user['user_posts'] = Post::where('user_id', $user->id)->whereIn('post_type', ['network', 'connect'])->orderBy('created_at', 'desc')->get(['id', 'post_type', 'image']);

            if($request->expectsjson())
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'User Details Found!',
                    'data' => $user->makeHidden(['created_at', 'updated_at', 'email_verified_at', 'type']),
                ], 200);
            }

        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function nearby_members(Request $request)
    {
        try{
            $user = User::find($request->user_id);

            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            if($request->has('latitude') && $request->has('longitude'))
            {
                $lati = $request->latitude;
                $longi = $request->longitude;            
                // $distance = $request->distance;

                $nearby = \DB::select("SELECT id, name, profile_image, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) )
                * cos( radians( longitude ) - radians(?) ) + sin( radians(?) )
                * sin( radians( latitude ) ) ) ) AS distance FROM users WHERE NOT id = ?
                HAVING distance < 30 ORDER BY distance LIMIT 0 , 20", [$lati, $longi, $lati, $user->id]);  
                
                if(!empty($nearby))
                {
                    foreach($nearby as $n)
                    {
                        $n->distance = round($n->distance, 2);
                    }
                }               

                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => !empty($nearby) ? 200 : 400,
                        'message' => !empty($nearby) ? 'Nearby Members Found!' : 'No Nearby Member Found!',
                        'data' => !empty($nearby) ? $nearby : null,
                    ], 200);
                }
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Latitude & Longitude are Required!',
                    'data' => null,
                ], 200);
            }         

        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => $e->getMessage(),
            ], 200);
        }
    }

    public function active_members(Request $request)
    {
        try{
            $user = User::find($request->user_id);

            if(empty($user))
            {
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 400,
                        'message' => 'User does not exists!',
                        'data' => null,
                    ], 200);
                }
            }

            $users = User::where('is_online', '1')
            ->where('id', '!=', $request->user_id)
            ->get(['id', 'name', 'profile_image', 'token']);
            
            return response()->json([
                'status' => $users->count() > 0 ? 200 : 400,
                'message' => $users->count() > 0 ? 'Active Users Found!' : 'No Active User Found!',
                'data' => $users->count() > 0 ? $users : null,
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function upcoming_events()
    {
        try{
            $events = Post::where('post_type', 'event')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get(['id', 'title', 'image', 'event_date', 'price']);

            return response()->json([
                'status' => $events->count() > 0 ? 200 : 400,
                'message' => $events->count() > 0 ? 'Upcoming Events Found!' : 'No Upcoming Event Found!',
                'data' => $events->count() > 0 ? $events : null,
            ], 200);
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_all_notifications(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $notifications = Notification::where('user_id', $request->user_id)->get(['id', 'user_id', 'notification_type', 'notification', 'created_at']);
            
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => $notifications->count() > 0 ? 200 : 400,
                    'message' => $notifications->count() > 0 ? 'Notifications Found!' : 'No Notification Found!',
                    'data' => $notifications->count() > 0 ? $notifications : null,
                ], 200);
            }

        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_buy_and_sell_notifications(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $notifications = Notification::where('user_id', $request->user_id)
            ->where('notification_type', 'buy_and_sell')
            ->get();
           
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => $notifications->count() > 0 ? 200 : 400,
                    'message' => $notifications->count() > 0 ? 'Buy and Sell Notifications Found!' : 'No Notification Found!',
                    'data' => $notifications->count() > 0 ? $notifications->makeHidden(['created_at', 'updated_at']) : null,
                ], 200);
            }

        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_events_notifications(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $notifications = Notification::where('user_id', $request->user_id)
            ->where('notification_type', 'event')
            ->get();
           
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => $notifications->count() > 0 ? 200 : 400,
                    'message' => $notifications->count() > 0 ? 'Events Notifications Found!' : 'No Notification Found!',
                    'data' => $notifications->count() > 0 ? $notifications->makeHidden(['created_at', 'updated_at']) : null,
                ], 200);
            }

        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    public function get_startups_notifications(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User does not exists!',
                    'data' => null,
                ], 200);
            }

            $notifications = Notification::where('user_id', $request->user_id)
            ->where('notification_type', 'startup')
            ->get();
           
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => $notifications->count() > 0 ? 200 : 400,
                    'message' => $notifications->count() > 0 ? 'Startup Notifications Found!' : 'No Notification Found!',
                    'data' => $notifications->count() > 0 ? $notifications->makeHidden(['created_at', 'updated_at']) : null,
                ], 200);
            }

        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }
    
    public function post_comments(Request $request)
    {
        try{
            $post = Post::find($request->post_id);
            
            if(empty($post))
            {
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Post does not exists!',
                        'data' => null,
                    ], 200);
                }
            }
            
            $comments = Comment::where('post_id', $request->post_id)->get();
            
            if($comments->count() > 0)
            {
                foreach($comments as $comment)
                {
                    $user = User::find($comment->user_id);
                    $comment->user_name = $user->name;
                    $comment->profile_image = $user->profile_image;
                }
            }

            if($request->expectsJson())
            {
                return  response()->json([
                    'status' => $comments->count() > 0 ? 200 : 400,
                    'message' => $comments->count() > 0 ? 'Comments Found!' : 'No Comment Found!',
                    'data' => $comments->count() > 0 ? $comments->makeHidden(['created_at', 'updated_at']) : null,
                ], 200);
            }
            
        }catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => null,
                ], 200);
            }
        }
    }

    public function post_details(Request $request)
    {
        try{
            $post = Post::find($request->post_id);

            if(empty($post))
            {
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 400,
                        'message' => 'User does not exists!',
                        'data' => null,
                    ], 200);
                }
            }

            $saved = SavedPost::where('user_id', $request->user_id)
            ->where('post_id', $post->id)
            ->first();

            if(!empty($saved))
            {
                $post->is_saved = 'true';
            }else{
                $post->is_saved = 'false';
            }

            $post->total_likes = Like::where('post_id', $post->id)->count();
            $post->likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);

            if(!empty($post->likes))
            {
                foreach($post->likes as $like)
                {
                    $user = User::find($like->user_id);
                    $like->user_name = $user->name;
                    $like->profile_image = $user->profile_image;
                }
            }

            $post->total_comments = Comment::where('post_id', $post->id)->count();
            $post->comments = Comment::where('post_id', $post->id)->get(['id', 'user_id', 'post_id', 'comment']);

            if(!empty($post->comments))
            {
                foreach($post->comments as $comment)
                {
                    $user = User::find($comment->user_id);
                    $comment->user_name = $user->name;
                    $comment->profile_image = $user->profile_image;
                }
            }

            if($request->ExpectsJson())
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'Post details found!',
                    'data' => $post->makeHidden(['updated_at']),
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->ExpectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => null,
                ], 200);
            }
        }
    }

    public function buy_and_sell_filter(Request $request)
    {
        try{       
            if($request->category == 'All')
            {
                if($request->is_free == 'true')
                {
                    $posts = Post::where('post_type', 'buy_and_sell')
                    ->where('price', 'free')
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'description', 'category', 'price', 'link_of_product_or_service','created_at']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Posts Found!' : 'No Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }

                //price

                if($request->has('min_price') && $request->has('max_price'))
                {
                    $posts = Post::where('post_type', 'buy_and_sell')
                    ->where('price', '<=', (Int)$request->max_price)
                    ->where('price', '!=', 'free')
                    ->where('price', '>=', (Int)$request->min_price)
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'description', 'category', 'price', 'link_of_product_or_service','created_at']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Posts Found!' : 'No Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }
            }else{
                if($request->is_free == 'true')
                {
                    $posts = Post::where('post_type', 'buy_and_sell')
                    ->where('price', 'free')
                    ->where('category', $request->category)
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'description', 'category', 'price', 'link_of_product_or_service','created_at']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Posts Found!' : 'No Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }

                //price

                if($request->has('min_price') && $request->has('max_price'))
                {
                    $posts = Post::where('post_type', 'buy_and_sell')
                    ->where('price', '<=', (Int)$request->max_price)
                    ->where('price', '!=', 'free')
                    ->where('price', '>=', (Int)$request->min_price)
                    ->where('category', $request->category)
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'description', 'category', 'price', 'link_of_product_or_service','created_at']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Posts Found!' : 'No Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }
            }
        }catch(\Exception $e)
        {
            if($request->ExpectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => null,
                ], 200);
            }
        }
    }

    public function event_posts_filters(Request $request)
    {
        try{
            if($request->category == 'All')
            {
                if($request->is_free == 'true')
                {
                    $posts = Post::where('post_type', 'event')
                    ->where('price', 'free')
                    ->where('event_location', $request->location)
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'category', 'price', 'event_date', 'location','description', 'tags', 'service_fee', 'created_at']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Event Posts Found!' : 'No Event Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }

                //price

                if($request->has('min_price') && $request->has('max_price'))
                {                
                    $posts = Post::where('post_type', 'event')
                    ->where('price', '<=', (Int)$request->max_price)
                    ->where('price', '!=', 'free')
                    ->where('price', '>=', (Int)$request->min_price)
                    ->where('location', $request->location)
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'category', 'price', 'event_date', 'event_location','description', 'tags', 'service_fee', 'created_at']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Posts Found!' : 'No Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }
            }else{
                if($request->is_free == 'true')
                {
                    $posts = Post::where('post_type', 'event')
                    ->where('price', 'free')
                    ->where('event_location', $request->location)
                    ->where('category', $request->category)
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'category', 'price', 'event_date', 'event_location','description', 'tags', 'service_fee', 'created_at']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Event Posts Found!' : 'No Event Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }

                //price

                if($request->has('min_price') && $request->has('max_price'))
                {
                    $posts = Post::where('post_type', 'event')
                    ->where('price', '<=', (Int)$request->max_price)
                    ->where('price', '!=', 'free')
                    ->where('price', '>=', (Int)$request->min_price)
                    ->where('category', $request->category)
                    ->where('event_location', $request->location)
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'description', 'category', 'price', 'link_of_product_or_service','created_at']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Event Posts Found!' : 'No Event Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => $e->getMessage(),
                ], 200);
            }
        }
    }

    public function startup_posts_filters(Request $request)
    {
        try{
            if($request->category == 'All')
            {
                //price
                if($request->has('min_investment') && $request->has('max_investment'))
                {             
                    $posts = Post::where('post_type', 'startup')
                    ->where('startup_investment', '<=', (Int)$request->max_investment)                
                    ->where('startup_investment', '>=', (Int)$request->min_investment)  
                    ->where('backers', $request->backers)              
                    ->get(['id', 'user_id', 'post_type', 'image', 'title', 'startup_investment', 'pledge_goal_amount', 'startup_end_date', 'description', 'investment_type', 'next_step_for_startup', 'backers','feel_about_next_step', 'startup_option_1', 'startup_option_2', 'startup_option_3', 'how_far_along_startup', 'money_need_for_startup', 'planned_time_for_startup', 'do_you_have_enough_money', 'access_to_startup_network', 'service_fee']);
                    
                    if($posts->count() > 0)
                    {
                        foreach($posts as $post)
                        {
                            $saved = SavedPost::where('user_id', $request->user_id)
                            ->where('post_id', $request->post_id)
                            ->first();

                            if(!empty($saved))
                            {
                                $post->is_saved_post = 'true';
                            }else{
                                $post->is_saved_post = 'false';
                            }

                            $likes = Like::where('post_id', $post->id)->get(['id', 'user_id', 'post_id']);
                            if($likes->count() > 0)
                            {
                                foreach($likes as $like)
                                {
                                    $user = User::find($like->user_id);
                                    $like->user_name = $user->name;
                                    $like->profile_image = $user->profile_image;
                                    
                                }
                            }
                            $post->likes = $likes;                            
                        }
                    }

                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => $posts->count() > 0 ? 200 : 400,
                            'message' => $posts->count() > 0 ? 'Startup Posts Found!' : 'No Startup Post Found!',
                            'data' => $posts->count() > 0 ? $posts : null,
                        ], 200);
                    }
                }
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => $e->getMessage(),
                ], 200);
            }
        }
    }
    
    public function reserve_event_seat(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            
            if(empty($user))
            {
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 400,
                        'message' => 'User does not exists!',
                        'data' => null,
                    ], 200);
                }
            }
            
            $event = Post::find($request->event_post_id);
            
            if(empty($event))
            {
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Event Post does not exists!',
                        'data' => null,
                    ], 200);
                }
            }
            
            
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'Your Seat Reservation Request has been Submitted!',
                    'data' => null,
                ], 200);
            }
        }catch(\Exception $e)
        {
            if($request->expectsJson())
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'There is some trouble to proceed your action!',
                    'data' => null,
                ], 200);
            }
        }
    }
    
    public function search_suggestions(Request $request)
    {
        try{
            $user = User::where('id', $request->user_id)->first('id');
            
            if(empty($user))
            {
                return response()->json([
                    'status' => false,
                    'message' => 'User does not Exists',
                ], 200);
            }
            
            $users = User::where('id', '!=', $request->user_id)->get(['id', 'name', 'profile_image']);
            
            return response()->json([
                'status' => true,
                'message' => $users->count() > 0 ? 'Search Suggestions Found' : 'No Search Suggestion Found',
                'data' => $users->count() > 0 ? $users : [],
            ], 200);
            
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'There is some trouble to proceed your action',
            ], 200); 
        }
    }
}