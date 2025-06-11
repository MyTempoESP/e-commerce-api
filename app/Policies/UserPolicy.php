<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
	public function allowedAdmin(User $user): bool
	{
		return $user->administrator()->exists();
	}

	public function allowedShop(User $user): bool
	{
		return $user->shop()->exists();
	}

	public function allowedConsignee(User $user): bool
	{
		return $user->consignee()->exists();
	}
}
