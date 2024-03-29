<?php declare(strict_types=1);

namespace App\Admin\Http\Controllers\User;

use Illuminate\Http\JsonResponse;

use App\Structure\Http\Controllers\Controller;

use App\Admin\Http\Resources\User\UserResource;
use App\Admin\Http\Requests\User\UpdateUserPersonalInformationRequest;

use App\User\Models\User;
use App\User\Actions\UpdateUserPersonalInformation\UpdateUserPersonalInformation;

class UpdateUserProfileInformationController extends Controller
{
    /**
     * Update another user profile information
     *
     * @param App\Admin\Http\Requests\User\UpdateUserPersonalInformationRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateUserPersonalInformationRequest $request): JsonResponse
    {
        $this->authorize('update', User::class);

        $userUpdated = dispatch_sync(new UpdateUserPersonalInformation(
            (int) $request->input('id'),
            $request->input('name'),
            $request->input('email')
        ));
        return (new UserResource($userUpdated))->response($request);
    }
}
