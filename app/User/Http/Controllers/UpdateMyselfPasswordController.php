<?php declare(strict_types=1);

namespace App\User\Http\Controllers;

use Illuminate\Http\Response;
use App\Structure\Http\Controllers\Controller;

use App\User\Http\Requests\UpdateMyselfPasswordRequest;

use App\User\Actions\UpdateUserPassword\UpdateUserPassword;

class UpdateMyselfPasswordController extends Controller
{
    /**
     * Update authenticated user password (myself password)
     *
     * @param App\User\Http\Requests\UpdateMyselfPasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateMyselfPasswordRequest $request): Response
    {
        dispatch_sync(new UpdateUserPassword(
            auth()->user()->id,
            $request->input('password')
        ));
        return response()->noContent();
    }
}
