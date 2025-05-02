<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PublicController extends Controller
{
    /**
     * Delete from specify database
     * @return JsonResponse
    */
    public function triggerDelete(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'table'=>'required|string',
                'id'=>'required|int',
                'id_field'=>'nullable|string',
            ]);
            $field = $data['id_field'] ?? 'id';
            $result = DB::table($data['table'])
                ->where($field, $data['id'])
                ->delete();
            return response()->json([
                "status"=>"success",
                "result"=>$result
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }


    /**
     * disable accounts
     * @return JsonResponse
    */
    public function disableData(Request $request):JsonResponse
    {
        try {
            $data = $request->validate([
                'table'=>'required|string',
                'id'=>'required|int',
                'id_field'=>'nullable|string',
                'state'=>'required|string',
                'state_val'=>'required|string',
            ]);
            $field = $data['id_field'] ?? 'id';
            $result = DB::table($data['table'])
                ->where($field, $data['id'])
                ->update([$data['state'] =>$data['state_val']]);
            return response()->json([
                "status"=>"success",
                "result"=>$result
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            return response()->json(['errors' => $errors ]);
        }
        catch (\Illuminate\Database\QueryException $e){
            return response()->json(['errors' => $e->getMessage() ]);
        }
    }
}