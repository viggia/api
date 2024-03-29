<?php declare(strict_types=1);

namespace App\Company\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Resources\CompanyResource;
use App\Company\Http\Requests\UpdateCompanyInformationRequest;
use App\Company\Actions\UpdateCompanyInformation\UpdateCompanyInformation;

class UpdateCompanyInformationController extends Controller
{
    /**
     * Update current user company information
     *
     * @param App\Company\Http\Requests\UpdateCompanyInformationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateCompanyInformationRequest $request): JsonResponse
    {
        $this->authorize('update', auth()->user()->currentCompany);

        $companyUpdated = dispatch_sync(new UpdateCompanyInformation(
            $request->input('company_id'),
            $request->input('name')
        ));
        return (new CompanyResource($companyUpdated))->response($request);
    }
}
