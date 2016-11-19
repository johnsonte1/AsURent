<?php

namespace App\Policies;

use App\User;
use App\Contract;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractPolicy
{
    use HandlesAuthorization;

   /**
    * Determine if the given user can delete the given Contract.
    * 
    * @param user $user
    * @param Task $task
    * @return bool
    */
    public function destroy(User $user, Contract $contract)
    {
        return $user->id == $contract->landlord_id || $user->id == $contract->tenant_id;
    }
    /**
    * Determine if the given user can change a contract name and descriptor
    * 
    * @param user $user
    * @param Task $task
    * @return bool
    */
    public function edit(User $user, Contract $contract)
    {
        return $user->id == $contract->landlord_id;
    }
}
