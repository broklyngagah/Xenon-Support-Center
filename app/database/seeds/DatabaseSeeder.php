<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

        $this->command->info("Creating Super Admins");

        $group = new Groups();
        $group->name="admin";
        $group->save();

        $group = new Groups();
        $group->name="department-admin";
        $group->save();

        $group = new Groups();
        $group->name="operator";
        $group->save();

        $group = new Groups();
        $group->name="customer";
        $group->save();

        $admin = new User();
        $admin->name = "Admin";
        $admin->email = "admin@mail.com";
        $admin->password = Hash::make("admin");
        $admin->avatar = "/assets/images/default-avatar.jpg";
        $admin->show_avatar = 1;
        $admin->birthday = "01-15-1990";
        $admin->bio = "This is dummy admin bio";
        $admin->gender = "Male";
        $admin->mobile_no = "0000000000";
        $admin->country = "India";
        $admin->activated = 1;
        $admin->activated_at = \Carbon\Carbon::now();
        $admin->save();

        DB::table('users_groups')->insert(['user_id'=>1,'group_id'=>1]);
    }

}
