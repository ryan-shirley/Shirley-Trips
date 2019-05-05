<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Holiday;
use App\Image;
use App\User;
use App\Day;

class HolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('id', '1')->first();
        $image = Image::where('id', 1)->first();

        $holiday = new Holiday();
        $holiday->title = 'Thailand';
        $holiday->subTitle = 'Bangkok';
        $holiday->image_id = $image->id;
        $holiday->beginDate = '2019-06-11';
        $holiday->endDate = '2019-07-03';
        $holiday->save();
        $holiday->users()->attach($user, ['editPermission' => true, 'owner' => true]);

        while (strtotime($holiday->beginDate) <= strtotime($holiday->endDate)) {
            $day = new Day();
            $day->day = $holiday->beginDate;
            $day->holiday_id = $holiday->id;
            $day->save();

            $holiday->beginDate = date ("Y-m-d", strtotime("+1 day", strtotime($holiday->beginDate)));
        }

        $holiday2 = new Holiday();
        $holiday2->title = 'Indonesia';
        $holiday2->subTitle = 'Bali';
        $holiday2->image_id = $image->id;
        $holiday2->beginDate = '2019-06-11';
        $holiday2->endDate = '2019-07-03';
        $holiday2->save();
        $holiday2->users()->attach($user, ['editPermission' => true, 'owner' => true]);

        while (strtotime($holiday2->beginDate) <= strtotime($holiday2->endDate)) {
            $day = new Day();
            $day->day = $holiday2->beginDate;
            $day->holiday_id = $holiday2->id;
            $day->save();

            $holiday2->beginDate = date ("Y-m-d", strtotime("+1 day", strtotime($holiday2->beginDate)));
        }
    }
}
