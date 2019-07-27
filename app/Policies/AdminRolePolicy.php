<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AdminRolePolicy
{
    use HandlesAuthorization;

    public function for_manager($admin) {
        $admin_role = $admin->getRoleNames()->first();
        return (bool)(config('project.admin.roles_priorities')[$admin_role] >= config('project.admin.roles_priorities.manager'));
    }

    public function for_moderator($admin) {
        $admin_role = $admin->getRoleNames()->first();
        return (bool)(config('project.admin.roles_priorities')[$admin_role] >= config('project.admin.roles_priorities.moderator'));
    }

    public function for_administrator($admin) {
        $admin_role = $admin->getRoleNames()->first();
        return (bool)(config('project.admin.roles_priorities')[$admin_role] >= config('project.admin.roles_priorities.administrator'));
    }
}
