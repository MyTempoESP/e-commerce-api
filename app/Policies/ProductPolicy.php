<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return $user->shop()->exists();
	}

	/**
	 * determine whether the user can view the model.
	 */
	public function view(user $user, product $product): bool
	{
		return $user->shop->id === $product->shop_id;
	}

	/**
	 * determine whether the user can create models.
	 */
	public function create(user $user): bool
	{
		return $user->shop()->exists();
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Product $product): bool
	{
		return $user->shop->id === $product->shop_id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Product $product): bool
	{
		return $user->shop->id === $product->shop_id;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Product $product): bool
	{
		return false;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Product $product): bool
	{
		return false;
	}
}
