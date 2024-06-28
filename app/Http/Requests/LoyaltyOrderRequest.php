<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoyaltyOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'barcode' => 'required',
            'phone_number' => 'required|numeric|between:998000000000,998999999999',
            'cashback' => 'required|numeric|max:999999',
            'date' => 'required|date_format:Y-m-d H:i'
        ];
    }
}
