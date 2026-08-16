<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:100'],
            'price'             => ['required', 'numeric', 'min:0'],
            'currency'          => ['required', 'string', 'max:10'],
            'billing_cycle'     => ['required', 'in:monthly,yearly'],
            'max_quality'       => ['required', 'in:480p,720p,1080p,4k'],
            'screens_allowed'   => ['required', 'integer', 'min:1'],
            'is_active'         => ['boolean'],
            'stripe_product_id' => ['nullable', 'string', 'max:255'],
            'stripe_price_id'   => ['nullable', 'string', 'max:255'],
        ];
    }
}
