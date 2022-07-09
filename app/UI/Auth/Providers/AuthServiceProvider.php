<?php declare(strict_types=1);

namespace App\UI\Auth\Providers;

use App\Domain\User\Actions\CreateUser\CreateUserCommand;
use App\Domain\User\Actions\CreateUser\CreateUserHandler;

use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Inicia os serviços
     *
     * @return void
     */
    public function boot(): void
    {
        // map routes
        $this->map();

        // commands and handlers
        $this->registerCommandsAndHandlers();
    }

    /**
     * Mapeia os comandos e handlers respectivos
     *
     * @return void
     */
    public function registerCommandsAndHandlers(): void
    {
        Bus::map([
            CreateUserCommand::class => CreateUserHandler::class
        ]);
    }

    /**
     * Registra os serviços utilizados por esse provedor de serviços.
     *
     * @return void
     */
    public function register(): void {}

    /**
     * Define as rotas do provedor de serviços.
     *
     * @return void
     */
    public function map(): void
    {
        Route::middleware(['web'])
			->group(base_path('routes/auth/web.php'));

        Route::middleware(['api'])
            ->group(base_path('routes/auth/api.php'));

        require base_path('routes/auth/channels.php');
        require base_path('routes/auth/console.php');
    }
}
