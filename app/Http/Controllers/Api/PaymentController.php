<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use App\Models\Admin;
use App\Models\Payment;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function Repayment(Request $request, int $id){
        $user = User::find($id);
        if($user){
            // check loan is is valid or not
            $loan = Loan::where('id',$request->loanid)->where('userid',$id)->get();
            if($loan->count() > 0){
                // check loan is pending or approved
                $firstLoan = $loan->first();
                if($firstLoan->status == 'PENDING'){
                    return response()->json([
                        'status' => 200,
                        'message' => "Your Loan is pending."
                    ],200);
                }else if($firstLoan->status == 'PAID'){
                    return response()->json([
                        'status' => 200,
                        'message' => "Loan is already paid."
                    ],200);
                }else{
                    // get payment details
                    $payment = Payment::where('loanid',$request->loanid)->where('status',0)->first();
                    
                    // total amount 
                    $sumAmount = Payment::where('loanid', $request->loanid)
                    ->where('status', 0)
                    ->sum('amount');

                    // if payment is greater than the left loan amount
                    $sumAmount = $sumAmount - $payment->paid_amount;
                    if($request->payment > $sumAmount){
                        return response()->json([
                            'status' => 422,
                            'message' => "Payment amount exceeds loan and remaining balance."
                        ],422);
                    }else if($request->payment < ($payment->amount - $payment->paid_amount)){
                        $value = $payment->amount - $payment->paid_amount;
                        return response()->json([
                            'status' => 422,
                            'message' => "Please make a minimum payment of $value per month."
                        ],422);
                    }else{
                        // code for update in payment table
                        // $payment = Payment::find($payment->id);
                        $backup_paid_amount = $payment->paid_amount;
                        $payment->update([
                            'status' => 1,
                            'paid_amount' => $payment->amount,
                        ]);

                        // $leftAmount = $request->payment - ($payment->amount - $payment->paid_amount);
                        $leftAmount = $backup_paid_amount + $request->payment - $payment->amount;
                        while($leftAmount > 0){
                            $payment = Payment::where('loanid',$request->loanid)->where('status',0)->first();
                            
                            if($leftAmount >= $payment->amount){
                                // mean yha abhi bhi left amount bachne wala hai and status = 1 krna hai
                                $leftAmount =  $leftAmount - $payment->amount;
                                $payment->update([
                                    'paid_amount' => $payment->amount,
                                    'status' => 1
                                ]);
                            }else{
                                // yha status ko update nahi krna hai only paid_amount ko update kna hai
                                $paid_amount =  $leftAmount;
                                $payment->update([
                                    'paid_amount' => $leftAmount
                                ]);
                                $leftAmount = 0;
                            }
                        }
                        // after terminate the loop we need to update loan table and sent the success mesage
                        $paid_amount = $firstLoan->paid_amount + $request->payment;
                        $due_amount  = $firstLoan->due_amount - $request->payment;
                        $firstLoan->update([
                            'paid_amount' => $paid_amount,
                            'due_amount' => $due_amount
                        ]);
                        // need to update status = paid
                        if($request->payment == $sumAmount){
                            $firstLoan->update([
                                'status' => 'PAID'
                            ]);
                        }
                        return response()->json([
                            'status' => 200,
                            'message' => "Successfully repayment."
                        ],200);
                        
                    }
                }
                
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => "This is not your loan."
                ],404);
            }
            
        }else{
            return response()->json([
                'status' => 404,
                'message' => "This user is not exist."
            ],404);
        }
    }
}
