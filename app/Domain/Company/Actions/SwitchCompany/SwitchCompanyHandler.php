<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\SwitchCompany;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\User\Models\User;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\CompanyDto;
use App\Domain\Company\Actions\SwitchCompany\SwitchCompanyCommand;

final class SwitchCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\SwitchCompany\SwitchCompanyCommand $command
     * @return \App\Domain\Company\Actions\CompanyDto
     */
    public function handle(SwitchCompanyCommand $command): CompanyDto
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