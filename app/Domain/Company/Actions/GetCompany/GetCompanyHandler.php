<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\GetCompany;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\CompanyDto;
use App\Domain\Company\Actions\GetCompany\GetCompanyCommand;

final class GetCompanyHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\GetCompany\GetCompanyCommand $command
     * @return CompanyDto
     */
    public function handle(GetCompanyCommand $command): CompanyDto
    {
        $company = Company::where('id', $command->id)->firstOrFail();
        return CompanyDto::fromModel($company);
    }
}
