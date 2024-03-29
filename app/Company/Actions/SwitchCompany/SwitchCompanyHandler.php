<?php declare(strict_types=1);

namespace App\Company\Actions\SwitchCompany;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\User\Models\User;
use App\Company\Models\Company;
use App\Company\Actions\CompanyDto;
use App\Company\Actions\SwitchCompany\SwitchCompany;

final class SwitchCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Company\Actions\SwitchCompany\SwitchCompany $command
     * @return \App\Company\Actions\CompanyDto
     */
    public function handle(SwitchCompany $command): CompanyDto
    {
        try {
            DB::beginTransaction();
                $company = Company::where('id', $command->companyId)->firstOrFail();
                auth()->user()->forceFill([
                    'current_company_id' => $command->companyId
                ])->save();
            DB::commit();
			return CompanyDto::fromModel($company);
		} catch(QueryException $e) {
			DB::rollBack();
			throw new Exception(__('An internal error occurred while storing information in the database.'), 500);
        } catch(ModelNotFoundException $e) {
			DB::rollBack();
			throw new Exception(__('The informed company does not exist in our database.'), 404);
        } catch(Exception $e) {
			DB::rollBack();
			throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}
