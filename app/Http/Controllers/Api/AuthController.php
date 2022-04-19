<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
// use App\Mail\AccountVerificationEMail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Registartion Method
    public function register(Request $request)
    {
        try{

            if(!$request->has('name'))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Name is Required!',
                    'data' => null,
                ], 200);
            }

            if(!$request->has('email'))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Email is Required!',
                    'data' => null,
                ], 200);
            }

            $user = User::where('email', $request->email)->first();
            if(!empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'This Email has Already Been Taken!',
                    'data' => null,
                ], 200);
            }

            if(!$request->has('phone'))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Phone Number is Required!',
                    'data' => null,
                ], 200);
            }

            if(!$request->has('password'))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Password is Required!',
                    'data' => null,
                ], 200);
            }

            if(!$request->has('password_confirmation'))
            {
                return response([
                    'status' => 400,
                    'message' => 'Password Confirmation is Required!',
                    'data' => null,
                ], 200);
            }

            if($request->password != $request->password_confirmation)
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Password & Confirm Password Does Not Matched!',
                    'data' => null,
                ], 200);
            }            

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);   
            
            if($request->has('middle_name') && $request->middle_name != "")
            {
                $user->middle_name = $request->middle_name;
            }
            
            if($request->has('last_name') && $request->last_name != "")
            {
                $user->last_name = $request->last_name;
            }
            
            if($user->save())
            {                
                // \Mail::to($request->email)->send(new AccountVerificationEMail($code));

                $user1 = User::where('email', $request->email)->first();

                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Welcome to Social CEO!',
                        'data' => $user1->makeHidden(['created_at', 'updated_at', 'type', 'token']),
                    ], 200);
                }               
            }
        } catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => 'There is some trouble to proceed your action!',
                'data' => null,
            ], 200);
        }
    }

    // Login Method
    public function login(Request $request)
    {
        try{
            $loginData = $request->validate([
                'email' => 'email|required',
                'password' => 'required|max:255'
            ]);
            
            if(!auth()->attempt($loginData))
            {
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Invalid Credentials',
                        'data' => null,
                    ], 200);
                }           
            }
    
            // $accessToken = auth()->user()->createToken('authToken')->accessToken;  
            
            $user = auth()->user();
            $user->token = $request->token;
            if($user->save())
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'Welcome to Social CEO!',
                    'data' => auth()->user()->makeHidden(['type', 'created_at', 'updated_at']),
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

    public function forgot_password(Request $request)
    {
        try{
            $user = User::where('email', $request->email)->first();
            
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User with this Email Does Not Exists!',
                    'data' => null,
                ], 200);
            }

            $email = $request->email;

            $code = rand(1000, 9999);

            \Mail::to($email)->send(new ForgotPassword($code));

            $user->verification_code = $code;
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'A Password Recovery Code has been Sent to you Email!',
                'data' => null,
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

    public function verify_code(Request $request)
    {
        try{
            $user = User::where('email', $request->email)->first();
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User with this Email Does Not Exists!',
                    'data' => null,
                ], 200);
            }
            if($request->code == $user->verification_code)
            {
                $user->email_verified_at = Carbon::now();
                if($user->save())
                {
                    $user1 = User::where('email', $request->email)->first();
                }
                return response()->json([
                    'status' => 200,
                    'message' => 'Code Verified Successfully!',
                    'data' => $user1->makeHidden(['created_at', 'updated_at', 'verification_code', 'type', 'token']),
                ], 200);
            }else{
                return response()->json([
                    'status' => 400,
                    'message' => 'Invalid Verification Code!',
                    'data' => null,
                ], 200);
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => null,
            ], 200);
        }
    }

    public function reset_password(Request $request)
    {
        try{
            $user = User::where('email', $request->email)->first();

            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User with this Email Does Not Exists!',
                    'data' => null,
                ], 200);
            }

            if(!$request->has('password'))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Password is Required!',
                    'data' => null,
                ], 200);
            }

            if(!$request->has('confirm_password'))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Confirm Password is Required!',
                    'data' => null,
                ], 200);
            }

            if($request->password != $request->confirm_password)
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Password and Confirm Password Does Not Matched!',
                    'data' => null,
                ], 200);
            }else{
                $user->password = bcrypt($request->password);
                if($user->save())
                {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Password Changed Successfully!',
                        'data' => $user->makeHidden(['created_at', 'updated_at', 'verification_code', 'type', 'token']),
                    ], 200);
                }
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => null,
            ], 200);
        }
    }

    public function update_profile_image(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User Does Not Exists!',
                    'data' => null,
                ], 200);
            }else{
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

                            \Storage::disk('profile_images')->put($imageName, $data); // this disk is defined in config/filesystems.php under Disks section

                            $img_path = 'profile_images/'.$imageName;                   
                        }else{
                            return response()->json([
                                'status' => 400,
                                'message' => 'Please Choose a Valid Image!',
                                'data' => null,
                            ], 200);   
                        }         
                    }

                    $user->profile_image = $img_path;
                
                    if($user->save())
                    {
                        $user = User::find($request->user_id);
                        
                        if($request->expectsJson())
                        {
                            return response()->json([
                                'status' => 200,
                                'message' => 'Profile Image Updated Successfully!',
                                'data' => $user->makeHidden(['created_at', 'updated_at', 'verification_code', 'type', 'token']),
                            ], 200);
                        }
                    }
                }else{
                    return response()->json([
                        'status' => 400,
                        'message' => 'Choose an Image to Update!',
                        'data' => null,
                    ], 200);
                }                
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

    public function update_profile(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User with this ID Does Not Exists!',
                    'data' => null,
                ], 200);
            }else{
                if($request->has('name'))
                {
                    $user->name = $request->name;
                }
                if($request->has('phone'))
                {
                    $user->phone = $request->phone;
                }
                if($request->has('email'))
                {
                    $user->email = $request->email;
                }               
                
                if($request->has('notifications'))
                {
                    $user->notifications = $request->notifications;
                }

                if($request->has('is_online'))
                {
                    $user->is_online = $request->is_online;
                }

                if($user->save())
                {
                    $updatedUser = User::find($request->user_id);
                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Profile Updated Successfully!',
                            'data' => $updatedUser->makeHidden(['email_verified_at', 'type', 'created_at', 'updated_at', 'token']),
                        ], 200);
                    }
                }
            }
        }catch(\Exception $e)
        {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
                'data' => null,
            ], 200);
        }
    }

    public function verify_password(Request $request)
    {
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

            if(Hash::check($request->password, $user->password))
            {   
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Password Matched Successfully!',
                        'data' => null,
                    ], 200);
                }
            }else{
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 400,
                        'message' => 'You Entered Wrong Password!',
                        'data' => null,
                    ], 200);
                }
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

    public function change_password(Request $request)
    {
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

            if($request->password !== $request->confirm_password)
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'Password and Confirm Password does not Matched!',
                    'data' => null,
                ], 200);
            }else{
                $user->password = bcrypt($request->password);
                if($user->save())
                {
                    if($request->expectsJson())
                    {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Password Changed Successfully! You Need to Logged in Again',
                            'data' => null,
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
                    'data' => null,
                ], 200);
            }
        }
    }

    public function remove_payment_method(Request $request)
    {
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
            $user->payment_method_name = null;
            $user->name_on_card = null;
            $user->card_number = null;
            $user->expiry_date = null;
            $user->cvv = null;
            if($user->save())
            {
                $user1 = User::find($request->user_id);
                
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Payment Method Information Removed from your Account!',
                        'data' => $user1->makeHidden(['created_at', 'updated_at', 'email_verified_at', 'type', 'token']),
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
                    'data' => null,
                ], 200);
            }
        }
    }

    public function update_payment_method(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User with this ID does not exists!',
                    'data' => null,
                ], 200);
            }
            
            if($request->has('payment_method_name'))
            {
                $user->payment_method_name = $request->payment_method_name;
            }
            
            if($request->has('name_on_card'))
            {
                $user->name_on_card = $request->name_on_card;
            }

            if($request->has('card_number'))
            {
                $user->card_number = $request->card_number;
            }

            if($request->has('expiry_date'))
            {
                $user->expiry_date = $request->expiry_date;
            }

            if($request->has('cvv'))
            {
                $user->cvv = $request->cvv;
            }

            if($user->save())
            {
                $user1 = User::find($request->user_id);
                if($request->expectsJson())
                {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Payment Method Info Updated Successfully!',
                        'data' => $user1->makeHidden(['created_at', 'updated_at', 'email_verified_at', 'type', 'token']),
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
                    'data' => null,
                ], 200);
            }
        }
    }

    public function edit_profile(Request $request)
    {
        try{
            $user = User::find($request->user_id);
            if(empty($user))
            {
                return response()->json([
                    'status' => 400,
                    'message' => 'User with this ID does not exists!',
                    'data' => null,
                ], 200);
            }
            if($request->has('desired_gender'))
            {
                $user->sexual_orientation = $request->desired_gender;
            }
            if($request->has('latitude') && $request->has('longitude'))
            {
                
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
}
