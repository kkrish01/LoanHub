<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use App\Models\Admin;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function LoanRequest(Request $request, int $id){
        $admin = Admin::find($id);
        if($admin){
            $loan = Loan::find($request->loanid);
            if($loan){
                if($loan->status == 'PENDING'){
                    $currentDate = Carbon::now();

                    $SingleAmount = intval($loan->amount / $loan->term);
                    $LastAmount = $SingleAmount + ($loan->amount - ($loan->term * $SingleAmount));
                    $amount = $SingleAmount;
                    for ($i = 1; $i <= $loan->term; $i++) {

                        if($i == $loan->term){
                            $amount = $LastAmount;
                        }
                        $nextDate = $currentDate->addMonth(1);
                        $payment = Payment::create([
                            'loanid' => $loan->id,
                            'adminid' => $id,
                            'amount' => $amount,
                            'date' => $nextDate->format('Y-m-d'),
                        ]);
                        $currentDate = $nextDate;
                    }
                    // update in loan table
                    $loan = Loan::find($loan->id);
                    if($loan){
                        $loan->update([
                            'status' => 'APPROVED'
                        ]);
                        return response()->json([
                            'status' => 200,
                            'message' => "Loan has approved."
                        ],200);
                    }
                }else{
                    return response()->json([
                        'status' => 500,
                        'message' => "Loan is already approved."
                    ],500);
                }
                
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Loan is not found.'
                ],404);
            }
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Admin is exist.'
            ],404);
        }
    }

}
