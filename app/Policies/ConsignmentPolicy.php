<?php

namespace App\Policies;

use App\Models\Consignment;
use App\Models\Shop;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConsignmentPolicy
{

	public function addProduct(User $user, Shop $shop, Consignment $consignment): bool
	{
		if ($user->id !== $shop->user_id) {
			return false;
		}

		if ($shop->id !== $consignment->shop_id) {
			return false;
		}

		return true;
	}

	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return $user->shop()->exists() || $user->consignee()->exists();
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, Consignment $consignment): bool
	{
		return $user->shop->id === $consignment->shop_id ||
			$user->consignee->id === $consignment->consignee_id;
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
	public function update(User $user, Consignment $consignment): bool
	{
		return $user->shop->id === $consignment->shop_id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Consignment $consignment): bool
	{
		return $user->shop->id === $consignment->shop_id;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Consignment $consignment): bool
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Consignment $consignment): bool
	{
		return false;
	}
}
