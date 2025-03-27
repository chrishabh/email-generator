<?php

namespace App\Http\Controllers\ApiKey;

use App\Enums\CustomApiEnum;
use App\Http\Controllers\Controller;
use App\Models\ApiKeyLogs;
use App\Models\ApiKeys;
use App\Models\UserCredits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class GenerateCustomEndpointController extends Controller
{
    //
    public static function renderApiGenerateKeyPage(Request $request){
        $creditPoint ='Free';
        $headerData = array(); 
        if(Auth::check()){ 
            $userId               = Auth::user()->id;
            // $data                 = UserCredits::getCreditPoint($userId); 
            // $apiKeys              = ApiKeys::where('user_id', $userId)->where('is_deleted','0')->WhereNull('deleted_at')->orderBy('id','DESC')->paginate(5);
            // if(!empty($data)){
            //     $creditPoint =$data->credits;
                
            // }
        }
        $apiKeys=[];  
        $headerData['creditPoint']         = $creditPoint;
        return view('ApiKey.generate-api-endpoint')->with(compact('headerData','apiKeys'));
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
            ];
    
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){
                $response = response()->json(['success'=>false,'error'=>$validator],401);  
                $response->headers->set('Content-Type', 'application/json; charset=UTF-8'); 
                return $response;
            }
           
            $key    = Str::uuid()->toString();
            $userid = Auth::user()->id; 
            $apiKey = ApiKeys::create([
                'user_id' => $userid,
                'name'    => $request->name,
                'key'     => $key,
                'action'  => CustomApiEnum::CREATED
            ]);
    
           ApiKeyLogs::create(['api_key_id' => $apiKey->id,'key_name'=>$apiKey->name,'key'=>$apiKey->key, 'action' => CustomApiEnum::CREATED]);
           return response()->json(['success'=>true,'data'=>['apiKeyId'=>$apiKey->id],'message' => 'API Key created successfully'],200)->header('Content-Type', 'application/json; charset=UTF-8');
             
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('lead finder error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage(),'success'=>false])->header('Content-Type', 'application/json; charset=UTF-8');  
       
        }
         
    }

    public function index(Request $request)
    {  
        $perPage = 10; // Number of users per page
        $currentPage = $request->input('page', 1); // Get the current page or default to 1 
        $keys = ApiKeys::getDataWithPagination($perPage ,$currentPage);
        return response()->json(['success'=>true,'keys'=>$keys,'message' => 'API Key created successfully'],200)->header('Content-Type', 'application/json; charset=UTF-8');
    }

    public function destroy(Request $request){
        try {

            $rules = [
                'key' => 'required | numeric',
            ];
    
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){
                $response = response()->json(['success'=>false,'message'=>$validator],401);  
                $response->headers->set('Content-Type', 'application/json; charset=UTF-8'); 
                return $response;
            }

            $id                 = $request['key'];
            $apiKey             = ApiKeys::where('user_id', Auth::id())->where('id', $id)->where('is_deleted', '0')->where('deleted_at',null)->firstOrFail();
            $apiKey->delete(); 
            ApiKeyLogs::create(['api_key_id' => $id,'key_name'=>$apiKey->name, 'key'=>$apiKey->key, 'action' => CustomApiEnum::DELETED]);
            return response()->json(['message' => 'key deleted successfully!!','success'=> true])->header('Content-Type', 'application/json; charset=UTF-8');  
    
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('custom api deleted error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage(),'success'=>false])->header('Content-Type', 'application/json; charset=UTF-8');  
    
        }
        
    }
    public function regenerate(Request $request){
        try {

            $rules = [
                'key' => 'required | numeric',
            ];
    
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){
                $response = response()->json(['success'=>false,'message'=>$validator],401);  
                $response->headers->set('Content-Type', 'application/json; charset=UTF-8'); 
                return $response;
            }

            $id                 = $request['key'];
            $apiKey             = ApiKeys::where('user_id', Auth::id())->findOrFail($id);
            $userId             = $apiKey->user_id;
            $name               = $apiKey->name;
            $status             = $apiKey->status;  

            $apiKey->deleteReason = CustomApiEnum::REGENERATED;
            $apiKey->delete();

            $key    = Str::uuid()->toString(); 
            $apiKey = ApiKeys::create([
                'user_id' => $userId,
                'name'    => $name,
                'key'     => $key,
                'status'  => $status,
                'action'  => CustomApiEnum::REGENERATED
            ]);
            ApiKeyLogs::create(['api_key_id' => $id,'key_name'=>$apiKey->name, 'key'=>$apiKey->key, 'action' => CustomApiEnum::REGENERATED]);

            return response()->json(['message' => "$name key regenerated successfully!!",'success'=> true,'newKey'=> $key])->header('Content-Type', 'application/json; charset=UTF-8');  
    
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('lead finder error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage(),'success'=>false])->header('Content-Type', 'application/json; charset=UTF-8');  
    
        }
        
    }
}
