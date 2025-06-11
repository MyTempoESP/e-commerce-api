<?php

namespace App\Policies;

use App\Models\Consignee;
use App\Models\User;

class ConsigneePolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return $user->shop()->exists();
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, Consignee $consignee): bool
	{
		return $user->id === $consignee->user_id;
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		return $user->shop()->exists();
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Consignee $consignee): bool
	{
		return $user->id === $consignee->user_id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Consignee $consignee): bool
	{
		return $user->shop->id === $consignee->shop_id;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Consignee $consignee): bool
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Consignee $consignee): bool
	{
		return false;
	}
}
