<?php

namespace Tests\Company\CompanyMember\Invite;

use App\User\Models\User;
use App\Company\Models\Company;

use App\Role\Models\Role;
use App\Company\Mail\CompanyInvitation as CompanyInvitationInvite;
use App\Company\Models\CompanyInvitation as CompanyInvitationModel;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InviteNewCompanyMemberTest extends TestCase
{
	use WithFaker;
    use RefreshDatabase;
	
	/*
	|--------------------------------------------------------------------------
	| Company Member - INVITE TESTS SECTION
	|--------------------------------------------------------------------------
	*/
	/**
	 * Teste - convidar novo membro - convite enviado por usuário que é dono da empresa e com parâmetros válidos
	 * Autorizado: SIM
	 * Parâmetros válidos: SIM
	*/
    public function test_company_members_can_be_invited_to_company_by_owner_but_valid_params()
    {
        // Instancia o e-mail fake
        Mail::fake();

		// Cria um usuário
		$user = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($user->id);

		// Verifica se a coluna "current_company_id" do usuário logado = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);
		
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Cria a empresa
		$company = $this->createCompany($user->id);

		/*
		* Tenta setar a empresa criada como "current company" do usuário memberUser
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do usuário logado foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Cria um e-mail do usuário a ser convidado
		$email = $this->faker->unique()->safeEmail();

		// Pega os ids das roles e monta o array com os parâmetros do request
		$data = [
			'company_id' => $company->id,
			'email' => $email,
			'roles' => [
				(new Role())->where('name', 'company_member')->first()->id
			],
		];

        // Faz o request
		$response = $this->actingAs(Auth::user())->postJson('/companies/company-invitations/invite', $data);

		// Verifica se o código de resposta HTTP está correto (200)
		$response->assertOk();

		// Verifica se o e-mail de convite foi enviado
        Mail::assertSent(CompanyInvitationInvite::class);

		// Verifica se o convite foi armazenado na DB
        $this->assertCount(1, $company->fresh()->companyInvitations);
    }
	
	/**
	 * Teste - convidar novo membro - convite enviado por usuário que é dono da empresa, mas com parâmetros inválidos
	 * Autorizado: SIM
	 * Parâmetros válidos: NÃO
	*/
    public function test_company_members_can_be_invited_to_company_by_owner_but_invalid_params()
    {
        // Instancia o e-mail fake
        Mail::fake();

		// Cria um usuário
		$user = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($user->id);

		// Verifica se a coluna "current_company_id" do usuário logado = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);
		
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Cria a empresa
		$company = $this->createCompany($user->id);

		/*
		* Tenta setar a empresa criada como "current company" do usuário memberUser
		* -> Se o usuário for o owner, current_company_id será atualizada
		* -> Se o usuário NÃO for o owner, current_company_id continuará NULL
		*/
		Auth::user()->switchCompany($company);

		// Verifica se a coluna "current_company_id" do usuário logado foi atualizada
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => $company->id
		]);

		// Cria um e-mail do usuário a ser convidado
		$email = $this->faker->unique()->safeEmail();

        // Faz o request
		$response = $this->actingAs(Auth::user())->postJson('/companies/company-invitations/invite', []);

		// Verifica se está correta a estrutura do JSON de resposta
        $response->assertJsonStructure([
			'message',
            'errors' => []
		]);
		
		// Verifica se o código de resposta HTTP está correto (422)
		$response->assertUnprocessable();

		// Confirma que o e-mail de convite não foi enviado
        Mail::assertNotSent(CompanyInvitationInvite::class);

		// Confirma que o convite não foi armazenado na DB
        $this->assertCount(0, $company->fresh()->companyInvitations);
    }

	/**
	 * Teste - convidar novo membro - convite enviado por usuário que não é dono da empresa
	 * Autorizado: NÃO
	 * Parâmetros válidos: SIM
	*/
    public function test_company_members_can_be_invited_to_company_by_not_owner_of_company()
    {
        // Instancia o e-mail fake
        Mail::fake();

		// Cria um usuário logado
		$loggedUser = $this->createCommonUser();

		// Faz login
		Auth::loginUsingId($loggedUser->id);
		
		// Verifica se o usuário está logado
		$this->assertAuthenticated();

		// Verifica se a coluna "current_company_id" do usuário logado = null
		$this->assertDatabaseHas('users', [
			'id' => Auth::user()->id,
			'current_company_id' => null
		]);

		// Cria um usuário dono da empresa
		$companyOwnerUser = $this->createCommonUser();

		// Cria a empresa
		$company = $this->createCompany($companyOwnerUser->id);

		// Cria um e-mail do usuário a ser convidado
		$email = $this->faker->unique()->safeEmail();

		// Pega os ids das roles e monta o array com os parâmetros do request
		$data = [
			'company_id' => $company->id,
			'email' => $email,
			'roles' => [
				(new Role())->where('name', 'company_member')->first()->id
			],
		];

        // Faz o request
		$response = $this->actingAs(Auth::user())->postJson('/companies/company-invitations/invite', $data);

		// Verifica se a resposta foi do tipo "proibido" (403)
		// Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
		$response->assertForbidden();

		// Confirma que o e-mail de convite não foi enviado
        Mail::assertNotSent(CompanyInvitationInvite::class);

		// Confirma que o convite não foi armazenado na DB
        $this->assertCount(0, $company->fresh()->companyInvitations);
    }
}
