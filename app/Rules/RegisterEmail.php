<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class RegisterEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = \DB::table('users')->where('email','=',$value)->where('confirm_status', 0)->first();
        if($user){

            $dateReRegister =  Carbon::parse($user[0]->created_at)->addDays(7)->format('d/m/Y');
            $fail('Your {$attribute} has been previously registered but has not been verified via OTP. Please re-register at '. $dateReRegister);
        }
    }
}
