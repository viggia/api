<?php declare(strict_types=1);

namespace App\UI\User\Http\Controllers;

use App\UI\User\Http\Resources\UserCollection;
use App\Structure\Http\Controllers\Controller;
use App\Domain\User\Actions\GetUserList\GetUserListCommand;

class GetUserListController extends Controller
{
    /**
     * Get list of users
     *
     * @return \App\UI\User\Http\Resources\UserCollection
     */
    public function __invoke(): UserCollection
    {
        $users = dispatch_sync( new GetUserListCommand() );
        return (new UserCollection($users));
    }
}