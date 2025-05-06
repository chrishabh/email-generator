<?php

namespace App\Http\Controllers\Integeration;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Models\IntegrationTool;
use App\Models\singleVerification;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IntegrationController extends Controller
{
    public function index()
    {   
        $creditPoint ='Free';
        $integrated = Integration::with('tool')->where('status','verified')->paginate(10); 
        $headerData['creditPoint']         = $creditPoint;  
        return view('Integeration.index')->with(compact('headerData','integrated')); 

    }


    public function getListOfIntegeratedTools(Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $integrations = Integration::with('tool')
            ->where('status', 'verified')
            ->paginate($perPage);

        return response()->json([
            'data' => $integrations->items(),
            'total' => $integrations->total()
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
        $perPage = $validated['perPage'] ?? 10;  
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

}
