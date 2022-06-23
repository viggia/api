<?php declare(strict_types=1);

namespace App\UI\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCompanyMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return ( auth()->user()->id == auth()->user()->currentCompany->owner_user_id );
    }
	
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'numeric', 'exists:companies,id'],
            'user_id' => ['required', 'numeric', 'exists:users,id'],
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
            'company_id.exists' => __('A empresa informada não existe em nosso banco de dados.'),
            'user_id.required' => __('É necessário informar o ID do usuário a ser adicionado como membro da empresa.'),
            'user_id.exists' => __('O usuário informado não existe em nosso banco de dados.'),
        ];
    }
}
