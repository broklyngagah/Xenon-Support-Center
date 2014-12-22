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
        $admin->name = "Imran Iqbal";
        $admin->email = "shellprog@gmail.com";
        $admin->password = Hash::make("311311");
        $admin->avatar = "/assets/images/default-avatar.jpg";
        $admin->show_avatar = 1;
        $admin->birthday = "01-15-1990";
        $admin->bio = "Imran is a web developer and consultant from India. He is the founder of KodeInfo, the PHP and Laravel Community . In the meantime he follows other projects, works as a freelance backend consultant for PHP applications and studies IT Engineering . He loves to learn new things, not only about PHP or development but everything.";
        $admin->gender = "Male";
        $admin->mobile_no = "8686371915";
        $admin->country = "India";
        $admin->activated = 1;
        $admin->activated_at = \Carbon\Carbon::now();
        $admin->save();

        DB::table('users_groups')->insert(['user_id'=>1,'group_id'=>1]);
    }

}
