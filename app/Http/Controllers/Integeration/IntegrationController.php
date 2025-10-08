<?php

namespace App\Http\Controllers\Integeration;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Models\IntegrationTool;
use App\Models\singleVerification;
use App\Models\UserCredits;
use Exception;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IntegrationController extends Controller
{
    public function index()
    {   
        $userId               = Auth::user()->id;
        $data                 = UserCredits::getCreditPoint($userId); 
        if(!empty($data)){
            $creditPoint =$data->credits;
            
        }
        $integrated = Integration::with('tool')->where('status','verified')->whereNull('deleted_at')->paginate(10); 
        $headerData['creditPoint']         = $creditPoint??0;
        $sessionData = session()->all();

        // Log session data for debugging
        //  pp($sessionData);
        return view('Integeration.index')->with(compact('headerData','integrated')); 

    }


    public function getListOfIntegeratedTools(Request $request)
    {
        $rules = [
            'perPage' => 'nullable|integer|min:1|max:100', // Ensure perPage is an integer between 1 and 100
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            $response = response()->json(['success'=>false,'error'=>$validator],401);  
            $response->headers->set('Content-Type', 'application/json; charset=UTF-8'); 
            return $response;
        }

        $perPage = $validated['perPage'] ?? 10;  
        $integrations = Integration::with('tool')
            ->where('status', 'verified')->where('user_id',Auth::user()->id)->whereNull('deleted_at')->orderBy('id', 'DESC')->paginate($perPage);

        return response()->json([
            'data'          => $integrations->items(),
            'total'         => $integrations->total(), 
            'currentPage'   => $integrations->currentPage(),
            'lastPage'      => $integrations->lastPage(),
            "success"       => true,
            'message'       => 'Tools fetched successfully',
        ]);
    }

    public function availableTools(Request $request)
    { 
        $rules = [
            'perPage' => 'nullable|integer|min:1|max:100', // Ensure perPage is an integer between 1 and 100
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            $response = response()->json(['success'=>false,'error'=>$validator],401);  
            $response->headers->set('Content-Type', 'application/json; charset=UTF-8'); 
            return $response;
        }
        $perPage = $validated['perPage'] ?? 20;  
        $tools   = IntegrationTool::paginate($perPage);
        $toolsData = $tools->getCollection()->map(function ($tool) {
            // Add the icon_url using the mutator
            $tool->icon_url = $tool->icon_url; 
            return $tool;
        });
        
        return response()->json([
            "success" =>true,
            "message"=>'Tools fetched successfully',
            'data' => $toolsData,
            'total' => $tools->total(),
            'currentPage' => $tools->currentPage(),
            'lastPage' => $tools->lastPage(),
        ])->header('Content-Type','application/json; charset=UTF-8');;
    }

    public function publicIntegrationPage(Request $request)
    { 
        $tools   = IntegrationTool::whereNull('deleted_at')->get();
        $tools->transform(function ($tool) {
            // Add the icon_url using the mutator
            $tool->icon_url = "integration/integerated-icon/".$tool->icon_url; 
            return $tool;
        });
        $tools = $tools->toArray();
        return view('publicIntegration.integration')->with(compact('tools')); 

        
    }

    public function removeIntegration($id)
    {
        try {
            $integration = Integration::find($id);
            if (!$integration) {
                return response()->json([
                    'success'  => false,
                    'error'   => 'Integration not found.',
                    'message' => 'Integration not found.'
                ], 404);
            }

            $integration->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'Integration Removed successfully.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public static function getClayIntegration(){
        $userId               = Auth::user()->id;
        $data                 = UserCredits::getCreditPoint($userId); 
        if(!empty($data)){
            $creditPoint =$data->credits;
            
        }
        $integrated = Integration::with('tool')->where('status','verified')->whereNull('deleted_at')->paginate(10); 
        $headerData['creditPoint']         = $creditPoint??0;
        $sessionData = session()->all();
        return view('Integeration.clay')->with(compact('headerData')); 

    }

}
