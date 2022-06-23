<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompanyMemberInformation;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\CompanyMemberInformationDto;
use App\Domain\Company\Actions\GetCompanyMemberInformation\GetCompanyMemberInformationCommand;

final class GetCompanyMemberInformationHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\GetCompanyMemberInformation\GetCompanyMemberInformationCommand $command
     * @return \App\Domain\Company\Actions\CompanyMemberInformationDto
     */
    public function handle(GetCompanyMemberInformationCommand $command): CompanyMemberInformationDto
    {
        try {
            $company = Company::where('id', $command->companyId)->firstOrFail();
			$companyMember = $company->companyMemberById($command->companyMemberId);
			return CompanyMemberInformationDto::fromModel($companyMember);
        } catch(QueryException $e) {
            throw new Exception(__('An internal error occurred during our database search.'), 403);
        } catch(ModelNotFoundException $e) {
            throw new Exception(__('The informed company does not exist in our database.'), 404);
        } catch(Exception $e) {
            throw new Exception(__('An unknown internal error has occurred.'), 500);
        }
    }
}