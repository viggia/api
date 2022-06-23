<?php

use Illuminate\Support\Facades\Route;

use App\UI\Http\Company\Controllers\AddCompanyMemberController;
use App\UI\Http\Company\Controllers\RemoveCompanyMemberController;
use App\UI\Http\Company\Controllers\UpdateCompanyInformationController;
use App\UI\Http\Company\Controllers\GetCurrentCompanyInformationController;

use App\UI\Http\Company\Controllers\GetCompanyMemberInformationController;
use App\UI\Http\Company\Controllers\GetCurrentCompanyMemberListController;




/*
|--------------------------------------------------------------------------
| API Routes - Company Domain
|--------------------------------------------------------------------------
|
| As rotas de API do domínio "Company" devem ser registradas aqui.
|
| As rotas devem seguir o seguinte padrão:
|
|		Verb		|				URI								|	Action	|		Route Name
|		GET			|	/photos/{photo}/comments					|	index	|	photos.comments.index
|		GET			|	/photos/{photo}/comments/create				|	create	|	photos.comments.create
|		POST		|	/photos/{photo}/comments					|	store	|	photos.comments.store
|		GET			|	/photos/{photo}/comments/{comment}			|	show	|	photos.comments.show
|		GET			|	/photos/{photo}/comments/{comment}/edit		|	edit	|	photos.comments.edit
|		PUT/PATCH	|	/photos/{photo}/comments/{comment}			|	update	|	photos.comments.update
|		DELETE		|	/photos/{photo}/comments/{comment}			|	destroy	|	photos.comments.destroy
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {
    
	// Company group
	Route::group(['prefix' => 'company'], function () {
		
		// Current company route group
		Route::group(['prefix' => 'current'], function () {
			
			/**
			 * Get current company information
			 *
			 * @method  GET
			 * @route   domain.example/company/current/profile
			 * @name    company.current.profile.show
			 */
			Route::get('profile', [GetCurrentCompanyInformationController::class, '__invoke'])
			->name('company.current.profile.show');

			/**
			 * Update current company information
			 *
			 * @method  PUT
			 * @route   domain.example/company/current/profile
			 * @name    company.current.profile.update
			 */
			Route::put('profile', [UpdateCompanyInformationController::class, '__invoke'])
			->name('company.current.profile.update');
			
			// Current company members route group
			Route::group(['prefix' => 'members'], function () {
				
				// Single member route group
				Route::group(['prefix' => 'member'], function () {
					
					/**
					 * Add new member to the current company
					 *
					 * @method  PUT
					 * @route   domain.example/company/current/members/member/add-member
					 * @name    company.current.members.member.add
					 */
					Route::put('add-member', [AddCompanyMemberController::class, '__invoke'])
					->name('company.current.members.member.add');
					
					/**
					 * Remove member to the current company
					 *
					 * @method  PUT
					 * @route   domain.example/company/current/members/member/remove-member
					 * @name    company.current.members.member.remove
					 */
					Route::put('remove-member', [RemoveCompanyMemberController::class, '__invoke'])
					->name('company.current.members.member.remove');
				
					/**
					 * Get company member profile information by companyMemberId
					 *
					 * @method  GET
					 * @route   domain.example/company/current/members/member/{companyMemberId}/profile
					 * @name    company.current.members.member.profile.show
					 */
					Route::get('{companyMemberId}/profile', [GetCompanyMemberInformationController::class, '__invoke'])
					->name('company.current.members.member.profile.show');

					/**
					 * Update another user profile information
					 *
					 * @method  PUT
					 * @route   domain.example/users/user/{userId}/profile
					 * @name    users.user.profile.update
					 */
					//Route::put('{userId}/profile', [UpdateUserProfileInformationController::class, '__invoke'])
					//->name('users.user.profile.update');
					
				}); // End Single member route group

				/**
				 * Get current list of company members
				 *
				 * @method  GET
				 * @route   domain.example/company/current/members
				 * @name    company.current.members.show
				 */
				Route::get('', [GetCurrentCompanyMemberListController::class, '__invoke'])
				->name('company.current.members.show');
				
			}); // End company members route group
			
		}); //End "current" company route group
		
	}); //End company route group
});
