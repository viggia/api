<?php declare(strict_types=1);

namespace App\UI\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SwitchCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $companyId = (int) $this->get('company_id');
        $userId = auth()->user()->id;
        return auth()->user()->belongsToCompany($companyId, $userId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'numeric', 'exists:companies,id']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'company_id.required' => __('É necessário informar o ID da empresa.'),
            'company_id.exists' => __('A empresa informada não existe em nosso banco de dados.')
        ];
    }
}
