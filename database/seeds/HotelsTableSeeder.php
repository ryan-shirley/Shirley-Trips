<?php

use Illuminate\Database\Seeder;
use App\Hotel;
use App\Holiday;
use App\Image;
use App\Day;

class HotelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $image = Image::where('id', 1)->first();

        
        $hotel = new Hotel();
        $hotel->name = 'Ibis Bali Legian Street';
        $hotel->location = 'Seminyak';
        $hotel->checkIn = '2019-06-12';
        $hotel->checkOut = '2019-06-20';
        $hotel->image_id = $image->id;
        $hotel->save();

        // Add Hotel to days for checkin to checkout.
        $holiday = Holiday::where('id', '2')->first();
        $holiday_id = $holiday->id;
        $all_holiday_days = $holiday->days;

        while (strtotime($hotel->checkIn) <= strtotime($hotel->checkOut)) {

            foreach ($all_holiday_days as $d) {
                // If holiday day = a day when in the hotel update
                if($d->day == $hotel->checkIn) {
                    $day_toUpdate = Day::where('id', $d->id)->first();
                    $day_toUpdate->hotel_id = $hotel->id;
                    $day_toUpdate->save();
                }
            }

            $hotel->checkIn = date ("Y-m-d", strtotime("+1 day", strtotime($hotel->checkIn)));
        }

    }
}
