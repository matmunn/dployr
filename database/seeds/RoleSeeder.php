<?php

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $manageUsers = new Permission;
        $manageUsers->name = "manage-users";
        $manageUsers->display_name = "Manage Users";
        $manageUsers->description = "create, delete and manage users in the group";
        $manageUsers->save();

        $connectRepository = new Permission;
        $connectRepository->name = "connect-repository";
        $connectRepository->display_name = "Connect Repository";
        $connectRepository->description = "connect new repositories";
        $connectRepository->save();

        $disconnectRepository = new Permission;
        $disconnectRepository->name = "disconnect-repository";
        $disconnectRepository->display_name = "Disconnect Repository";
        $disconnectRepository->description = "disconnect previously connected \
            repositories";
        $disconnectRepository->save();

        $addEnvironment = new Permission;
        $addEnvironment->name = "add-environment";
        $addEnvironment->display_name = "Add Environment";
        $addEnvironment->description = "add a new environment";
        $addEnvironment->save();

        $deleteEnvironment = new Permission;
        $deleteEnvironment->name = "delete-environment";
        $deleteEnvironment->display_name = "Delete Environment";
        $deleteEnvironment->description = "delete a new environment";
        $deleteEnvironment->save();

        $deploy = new Permission;
        $deploy->name = "deploy";
        $deploy->display_name = "Deploy Environment";
        $deploy->description = "redeploy your files";
        $deploy->save();

        $billing = new Permission;
        $billing->name = "billing";
        $billing->display_name = "Billing";
        $billing->description = "update billing information and check invoices";
        $billing->save();

        $group = new Permission;
        $group->name = "manage-group";
        $group->display_name = "Manage Group";
        $group->description = "manage group settings";
        $group->save();

        $owner = new Role;
        $owner->name = "owner";
        $owner->save();
        $owner->attachPermissions([
            $manageUsers,
            $connectRepository,
            $disconnectRepository,
            $addEnvironment,
            $deleteEnvironment,
            $deploy,
            $billing,
            $group,
        ]);

        $admin = new Role;
        $admin->name = "admin";
        $admin->save();
        $admin->attachPermissions([
            $manageUsers,
            $connectRepository,
            $disconnectRepository,
            $addEnvironment,
            $deleteEnvironment,
            $deploy,
            $billing,
        ]);

        $accounts = new Role;
        $accounts->name = "accounts";
        $accounts->save();
        $accounts->attachPermission($billing);

        $manager = new Role;
        $manager->name = "manager";
        $manager->save();
        $manager->attachPermissions([
            $connectRepository,
            $disconnectRepository,
            $addEnvironment,
            $deleteEnvironment,
            $deploy,
        ]);

        $user = new Role;
        $user->name = "user";
        $user->save();
        $user->attachPermission($deploy);
    }
}
