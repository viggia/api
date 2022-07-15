<?php

namespace Tests\Feature\Vehicle;

use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SwitchCompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_switch_company_with_not_authenticated_user()
    {		
		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->putJson('/companies/switch', []);
		
		// Verifica se o usuário não está logado
        $this->assertGuest();
		
		// Verifica se a resposta foi do tipo "não autorizado" (401)
		$response->assertUnauthorized();
    }

    public function test_switch_company_with_authenticated_user_but_not_member_of_company()
    {
        // Cria um usuário admin
        $userAdmin = $this->createAdminUser();

        // Cria um usuário comum (não admin)
        $userCommon = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($userAdmin->id);

		// Cria a empresa
		$company = $this->createCompany($userCommon->id);

		// Faz a requisição para obter os dados do registro sem informar usuário logado
        $response = $this->putJson('/companies/switch', ['company_id' => $company->id]);

		// Verifica se o usuário não está logado
        $this->assertAuthenticated();

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();
    }

/*
    public function test_get_vehicle_localization_information_with_common_user()
    {
        // Cria um usuário comum (não admin)
        $user = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($user->id);
		
        // Cria um novo ponto de localização
		$localization = $this->createVehicleLocalization();
		
		// Faz a requisição para obter os dados do registro informando um usuário comum logado
        $response = $this->actingAs($user)->getJson('/vehicle/localizations/'.$localization->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();
		
		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
				'id',
				'license_plate',
				'localization_latitude',
				'localization_longitude',
				'localized_at'
			]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }

    public function test_get_vehicle_localization_information_with_admin_user()
    {
        // Cria um usuário admin e super_admin
        $user = $this->createAdminUser();

		// Faz login
		Auth::loginUsingId($user->id);
		
        // Cria um novo ponto de localização
		$localization = $this->createVehicleLocalization();
		
		// Faz a requisição para obter os dados do registro informando um usuário admin/super_admin logado
        $response = $this->actingAs($user)->getJson('/vehicle/localizations/'.$localization->id);
        
		// Verifica se o usuário está logado
		$this->assertAuthenticated();
		
		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'data' => [
				'id',
				'license_plate',
				'localization_latitude',
				'localization_longitude',
				'localized_at'
			]
		]);
		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();
    }
	*/
}
