<?php declare(strict_types=1);

namespace App\Company\Http\Controllers\Member;

use Illuminate\Http\JsonResponse;
use App\Structure\Http\Controllers\Controller;
use App\Company\Http\Requests\AddCompanyMemberRequest;
use App\Company\Actions\AddCompanyMember\AddCompanyMember;

class AddCompanyMemberController extends Controller
{
    /**
     * Add member to the company
     *
     * @param App\Company\Http\Requests\AddCompanyMemberRequest $request
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function __invoke(AddCompanyMemberRequest $request): JsonResponse
    {
        dispatch_sync(
            new AddCompanyMember(
				$request->input('company_id'),
				$request->input('user_id'),
			)
        );
		return api()->success([
			'message' => __('Success! The user has been added as a member of this company.')
		], 200);
    }
}
