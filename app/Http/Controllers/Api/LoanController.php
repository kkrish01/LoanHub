<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    public function show($id){
        $user = User::find($id);
        if($user){
            $loan = Loan::where('userid', $id)->get();
            if($loan->count() > 0){
                return response()->json([
                    'status' => 200,
                    'message' => $loan
                ],200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Loans are not available.'
                ],404);
            }
            
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Such users not found.'
            ],404);
        }
    }

    public function LoanRequest(Request $request, int $id){
        $user = User::find($id);
        if($user){
            $validator = Validator::make($request->all(),[
                'amount' => 'required|min:3',
                'term' => 'required|min:1'
            ]);
            if ($validator->fails()){
                return response()->json([
                    'status' => 422,
                    'message' => $validator->messages()
                ],422);
            }else{
                $loan = Loan::create([
                    'userid' => $id,
                    'amount' => $request->amount,
                    'term' => $request->term,
                    'due_amount' => $request->amount
                ]);

                if($loan){
                    return response()->json([
                        'status' => 200,
                        'message' => "Loan request sent to the admin."
                    ],200);
                }else{
                    return response()->json([
                        'status' => 500,
                        'message' => 'Something went wrong.'
                    ],500);
                }

            }
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Such users not found.'
            ],404);
        }
    }
}
