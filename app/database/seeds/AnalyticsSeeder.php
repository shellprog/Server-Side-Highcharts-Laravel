<?php

class AnalyticsSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $date = \Carbon\Carbon::now();

        for($i=0;$i<100;$i++) {
            $a = new Analytics();
            $a->visits = rand(1000,5000);
            $a->save();
            //Analytics::insert(['visits'=>rand(1000,5000)]);
            sleep(1);
        }
	}

}
