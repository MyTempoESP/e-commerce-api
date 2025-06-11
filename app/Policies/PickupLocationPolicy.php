<?php

namespace App\Policies;

use App\Models\PickupLocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PickupLocationPolicy
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
	public function view(User $user, PickupLocation $pickupLocation): bool
	{
		return false;
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function addPickupLocation(User $user): bool
	{
		return $user->shop()->exists();
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, PickupLocation $pickupLocation): bool
	{
		return $user->shop->id === $pickupLocation->shop_id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, PickupLocation $pickupLocation): bool
	{
		return $user->shop->id === $pickupLocation->shop_id;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, PickupLocation $pickupLocation): bool
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, PickupLocation $pickupLocation): bool
	{
		return false;
	}
}
