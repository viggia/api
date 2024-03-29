<?php

namespace App\Company\Policies;

use App\User\Models\User;
use App\Company\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User\Models\User  $user
     * @param  \App\Company\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Company $company)
    {
        return $company->hasOwnerOrCompanyMember($user->id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User\Models\User  $user
     * @param  \App\Company\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Company $company)
    {
        return $company->hasCompanyOwner($user->id);
    }

    /**
     * Determine whether the user can switch company.
     *
     * @param  \App\User\Models\User  $user
     * @param  \App\Company\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function invite(User $user, Company $company)
    {
        return $company->hasOwnerOrCompanyMember($user->id);
    }

    /**
     * Determine whether the user can switch company.
     *
     * @param  \App\User\Models\User  $user
     * @param  \App\Company\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function switchCompany(User $user, Company $company)
    {
        return $company->hasOwnerOrCompanyMember($user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User\Models\User  $user
     * @param  \App\Company\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Company $company)
    {
        return $company->hasCompanyOwner($user->id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User\Models\User  $user
     * @param  \App\Company\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Company $company)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User\Models\User  $user
     * @param  \App\Company\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Company $company)
    {
        //
    }
}
