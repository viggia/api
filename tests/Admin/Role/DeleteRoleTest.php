<?php

namespace Tests\Admin\Role;

use App\Role\Models\Role;

use Tests\TestCase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteRoleTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;

	/*
	* Teste - deletar role sem estar logado
	* Usuário logado: NÃO
	* Usuário autorizado: PREJUDICADO
	* Parâmetros corretos: PREJUDICADO
	*/
    public function test_delete_role_with_not_authenticated_user()
    {
        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
        
		// Faz a requisição para deletar o registro
		$response = $this->deleteJson('/admin/roles/'.$role->id);
		
		// Verifica se o usuário não está logado
        $this->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

	/*
	* Teste - deletar role com usuário logado, autorizado e parâmetros válidos
	* Usuário logado: SIM
	* Usuário autorizado: SIM
	* Parâmetros corretos: SIM
	*/
    public function test_delete_role_with_authorized_user()
    {
        // Cria um usuário admin
        $user = $this->createAdminUser();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
        
		// Faz login
		Auth::loginUsingId($user->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Faz a requisição para deletar o registro
		$response = $this->actingAs($user)->deleteJson('/admin/roles/'.$role->id);

		// Verifica se a requisição foi um sucesso com retorno "NoContent"
		$response->assertNoContent();
    }

	/*
	* Teste - deletar role com usuário logado, mas não autorizado
	* Usuário logado: SIM
	* Usuário autorizado: NÃO
	* Parâmetros corretos: SIM
	*/
    public function test_delete_role_with_unauthorized_user()
    {
        // Cria um usuário comum
        $commonUser = $this->createCommonUser();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
        
		// Faz login
		Auth::loginUsingId($commonUser->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Faz a requisição para deletar o registro
		$response = $this->actingAs($commonUser)->deleteJson('/admin/roles/'.$role->id);

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

	/*
	* Teste - deletar role com usuário logado e autorizado, mas com parâmetros inválidos
	* Usuário logado: SIM
	* Usuário autorizado: SIM
	* Parâmetros corretos: NÃO
	*/
    public function test_delete_role_with_authorized_user_but_invalid_params()
    {
        // Cria um usuário admin
        $user = $this->createAdminUser();

        // Cria uma role
		$role = (new Role())->create([
            'name' => $this->faker->slug,
            'description' => $this->faker->text(50),
		]);
        
		// Faz login
		Auth::loginUsingId($user->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Faz a requisição para deletar o registro especificando uma url inválida (param XXXXX)
		$response = $this->actingAs($user)->deleteJson('/admin/roles/XXXXX');

		// Verifica se a role ainda existe
		$this->assertDatabaseHas('roles', ['id' => $role->id]);

		// Verifica se o código de resposta HTTP está correto (404)
		$response->assertNotFound();
    }
}
