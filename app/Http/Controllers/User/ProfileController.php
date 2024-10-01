<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserCredits;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    //
    function getProfilePage(Request $request){
        $creditPoint =0;
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
            $userData  = Auth::user();
        }
            
        $headerData['creditPoint'] = $creditPoint;  
        return view('personal.profile')->with(compact('headerData','userData'));
    }

    function getSettingPage(Request $request){
        $creditPoint =0;
        $headerData = array(); 
        if(Auth::check()){ 
            $data = UserCredits::getCreditPoint(Auth::user()->id); 
           
            if(!empty($data)){
                $creditPoint =$data->credits;
                
            }
            $userData  = Auth::user();
        }
            
        $headerData['creditPoint'] = $creditPoint;  
        return view('personal.settings')->with(compact('headerData','userData'));
    }

    function renderSettingPage(Request $request)
    {
        try {
            $perPage = 10; // Number of users per page
            $currentPage = $request->input('page', 1); // Get the current page or default to 1
            
            $paginationData = User::getUserDetailsWithRemainingCredits($perPage, $currentPage);

            return response()->json([
                'success'     => true,
                'message'     => 'success',
                'data'        => $paginationData['data'],
                'total'       => $paginationData['total'],
                'perPage'     => $paginationData['perPage'],
                'currentPage' => $paginationData['currentPage']
            ])->header('Content-Type', 'application/json; charset=UTF-8');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in renderSettingPage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ])->header('Content-Type', 'application/json; charset=UTF-8');
        }
    }
    function renderMessagesPage(Request $request)
    {
        try {
            $perPage = 10; // Number of users per page
            $currentPage = $request->input('page', 1); // Get the current page or default to 1
            
            $paginationData = User::getUserDetailsWithRemainingCredits($perPage, $currentPage,true);

            return response()->json([
                'success'     => true,
                'message'     => 'success',
                'data'        => $paginationData['data'],
                'total'       => $paginationData['total'],
                'perPage'     => $paginationData['perPage'],
                'currentPage' => $paginationData['currentPage']
            ])->header('Content-Type', 'application/json; charset=UTF-8');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in renderSettingPage: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ])->header('Content-Type', 'application/json; charset=UTF-8');
        }
    }


   // Update Personal Info
   public function updatePersonalInfo(Request $request)
   {
    try {
        $validator = Validator::make($request->all(), [
            'mobile' => 'nullable|string',  // Make mobile optional
            'dob'    => 'nullable|date',    // Make date of birth optional
            'gender' => 'nullable|string',  // Make gender optional
        ]);
 
        if ($validator->fails()) {
            return response()->json(['success' => false,'message'=>$validator->errors()->first()])->header('Content-Type','application/json; charset=UTF-8');
        }

        // Only update fields that are present in the request
        $updateData = [];
        if ($request->has('mobile')) {
            $updateData['mobile_number'] = $request->input('mobile');
        }
        if ($request->has('dob')) {
            $updateData['date_of_birth'] = $request->input('dob');
        }
        if ($request->has('gender')) {
            $updateData['gender'] = $request->input('gender');
        }

        // Update the user's personal information
        if (!empty($updateData)) {
            if(User::insertDataAndgetId($updateData,Auth()->user()->id)){ 
                return response()->json(['success' => true, 'message' => 'Personal information updated successfully.','data'=>Auth()->user()])->header('Content-Type','application/json; charset=UTF-8');
            }else{
                return response()->json(['success' => false, 'message' => 'Something went wrong while updating the data!'])->header('Content-Type','application/json; charset=UTF-8');  
            }
        }
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('lead finder error: ' . $e->getMessage());
        return response()->json(['success' => false,'message'=> $e->getMessage()])->header('Content-Type', 'application/json; charset=UTF-8');  
    
    }
   }

   // Update work exp Info
   public function updateWorkExperience(Request $request)
   {
    try {
        $validator = Validator::make($request->all(), [
            'work_experience' => 'string|max:1000',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['success' => false,'message'=>$validator->errors()->first()])->header('Content-Type','application/json; charset=UTF-8');
        }
 
        // Only update fields that are present in the request
        $updateData = [];
        if ($request->has('work_experience')) {
            $updateData['work_experience_description'] = $request->input('work_experience');
        }

        // Update the user's personal information
        if (!empty($updateData)) {
            $id = Auth()->user()->id;
            if(User::insertDataAndgetId($updateData,$id)){ 
                $userData = User::find($id);
                return response()->json(['success' => true, 'message' => 'Work Experienced updated!.','data'=>$userData])->header('Content-Type','application/json; charset=UTF-8');
            }else{
                return response()->json(['success' => false, 'message' => 'Something went wrong while updating the data!'])->header('Content-Type','application/json; charset=UTF-8');    
            }
        }

        // Update personal info logic here 
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('lead finder error: ' . $e->getMessage());
        return response()->json(['success' => false,'message'=> $e->getMessage()])->header('Content-Type', 'application/json; charset=UTF-8');  
    
    }
   }


    //Update Password
   public function updatePassword(Request $request)
   {    
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password'     => 'required|string|min:8',
                'confirm_password' => 'required|string|min:8|same:new_password',
            ]);
     
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }
     
            $user = auth()->user();
     
            // Check current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Current password is incorrect.'])->header('Content-Type','application/json; charset=UTF-8');
            }
     
            // Ensure the new password is not the same as the current password
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'New password cannot be the same as the current password.'])->header('Content-Type','application/json; charset=UTF-8');
            }
            // Update the password
            $updateData  = [];
            $updateData['password']                               = Hash::make($request->new_password);
            $updateData['confirm_password']                       = '1';
            $updateData['is_password_update_from_profile_screen'] = '1';
            $updateData['password_updated_at']                    = Carbon::now();
            $updateData['password_update_ip']                     = $request->ip();;
     
            // Update the user's personal information
            if (!empty($updateData)) {
                $id = Auth()->user()->id;
                if(User::insertDataAndgetId($updateData,$id)){ 
                    $userData = User::find($id);
                    return response()->json(['success' => true, 'message' => 'Password updated successfully!.','data'=>$userData])->header('Content-Type','application/json; charset=UTF-8');
                }else{
                    return response()->json(['success' => false, 'message' => 'Something went wrong while updating the password!'])->header('Content-Type','application/json; charset=UTF-8');    
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('lead finder error: ' . $e->getMessage());
            return response()->json(['success' => false,'message'=> $e->getMessage()])->header('Content-Type', 'application/json; charset=UTF-8');  
        
        }
        
   }

   public function destroy($id)
    {
        try {
            // Find user by ID
            $user = User::findOrFail($id);

            // Delete user
            $user->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ], 200)->header('Content-Type','application/json; charset=UTF-8');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in delete the user: ' . $e->getMessage());
            // Return error response if deletion fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user. ' . $e->getMessage(),
            ], 500)->header('Content-Type','application/json; charset=UTF-8');
        }
    }


    ######### DASHBOARD ############
    public function getOverallCreditsReport()
    {
        try{
            $credits                 = UserCredits::join('users', 'users.id', '=','user_id')->withTrashed()->whereNull('users.deleted_at')->get(); 
            $totalCredits            = $credits->whereNull('user_credits.deleted_at')->sum('credits'); // Sum of all credits
            $usedCredits             = UserCredits::getUsedCredits()??0; // Soft deleted credits
            $availableCredits        = $totalCredits;  
            $creditAvailableOfAdmin  = 0;
            if(env('API_PLATFORM')=='debouncee'){
                $creditAvailableOfAdmin = getDebounceCreditBalance();

            }elseif(env('API_PLATFORM')=='bouncify'){
                $creditAvailableOfAdmin = getBouncifyCreditBalance();
            }
        
             return response()->json([
                'success' => true,
                'message' => 'success',
                'data'    => ['adminCreditsTotal'=>$creditAvailableOfAdmin, 'totalCredits'=> $totalCredits, 'usedCredits'=> $usedCredits,  'availableCredits' => $availableCredits]
            ], 200)->header('Content-Type','application/json; charset=UTF-8');

        }catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in delete the user: ' . $e->getMessage());
            // Return error response if deletion fails
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user. ' . $e->getMessage(),
            ], 500)->header('Content-Type','application/json; charset=UTF-8');
        }
         
    }

}
