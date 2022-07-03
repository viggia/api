<?php declare(strict_types=1);

namespace App\Domain\Company\Actions\RemoveCompanyMember;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Actions\RemoveCompanyMember\RemoveCompanyMemberCommand;

final class RemoveCompanyMemberHandler
{
    /**
     * Executa a ação
     *
     * @param \App\Domain\Company\Actions\RemoveCompanyMember\RemoveCompanyMemberCommand $command
     * @return void
     */
    public function handle(RemoveCompanyMemberCommand $command): void
    {
        try {
            DB::beginTransaction();
				$company = Company::where('id', $command->companyId)->firstOrFail();
				$company->onlyCompanyMembers()->detach($command->userId);
            DB::commit();
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
