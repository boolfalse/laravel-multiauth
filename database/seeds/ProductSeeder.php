<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use Illuminate\Support\Facades\File;
use App\Models\Product;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Admin;

class ProductSeeder extends Seeder
{
    public function generateOrderedCarbonDates($faker, $count, $interval_length): array
    {
        $tmp = [];
        for ($k = 0; $k < $count; $k++){
            $tmp[] = $faker->dateTimeInInterval('-' . $interval_length . ' days', '+ ' . $interval_length . ' days', null); // start date, interval, timezone
        }

        $datetimes_array = [];
        for ($k = 0; $k < $count; $k++){
            $datetimes_array[] = $tmp[$k]->format('Y-m-d');
        }

        function date_sort_2($a, $b) {
            return strtotime($a) - strtotime($b);
        }
        usort($datetimes_array, "date_sort_2");

        $response = [];
        for ($k = 0; $k < $count; $k++){
            $response[] = new Carbon($datetimes_array[$k]);
        }

        return $response;
    }

    public function run()
    {
        $faker = Factory::create('en_US');
        $count = config('project.seed.products_count');
        $interval_length = config('project.seed.products_dates_interval_length_days');
        $datetimes_array = $this->generateOrderedCarbonDates($faker, $count, $interval_length);

        $initial_path = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . config('project.product.images_folder'));
        if(!File::exists($initial_path)) {
            File::makeDirectory($initial_path, $mode = 0777, true, true);
        }
        foreach ($datetimes_array as $date)
        {
            $original_image_path = $initial_path . DIRECTORY_SEPARATOR . $date->year;
            if(!File::exists($original_image_path)) {
                File::makeDirectory($original_image_path, $mode = 0777, true, true);
            }
            $original_image_path .= DIRECTORY_SEPARATOR . $date->month;
            if(!File::exists($original_image_path)) {
                File::makeDirectory($original_image_path, $mode = 0777, true, true);
            }
            $original_image_path .= DIRECTORY_SEPARATOR . $date->day;
            if(!File::exists($original_image_path)) {
                File::makeDirectory($original_image_path, $mode = 0777, true, true);
            }
        }
        $uploads_path = public_path('uploads' . DIRECTORY_SEPARATOR . config('project.product.images_folder'));
        if (!file_exists($uploads_path)) {
            mkdir($uploads_path, 0755, true);
        }
        foreach (config('project.product.image_sizes') as $version => $sizes){
            if (!file_exists($uploads_path . DIRECTORY_SEPARATOR . $version)) {
                mkdir($uploads_path . DIRECTORY_SEPARATOR . $version, 0755, true);
            }
        }

        $admin_ids_array = Admin::all()->pluck('id')->toArray();

        $i = 0;
        while ($i < $count){
            $this->uploadImage($uploads_path, $datetimes_array[$i], $admin_ids_array, $faker);
            $i++;
        }
    }

    public function uploadImage($uploads_path, $date, $admin_ids_array, $faker): void
    {
        $main_image_name = $faker->image('storage/app/public/' . config('project.product.images_folder') . '/' . $date->year . '/' . $date->month . '/' . $date->day, config('project.seed.faker_image_width'), config('project.seed.faker_image_height'), null, false);
        // $main_image_name = $faker->image($original_image_path, null, false);
        // this worked on Windows 7 x64 too, but not for Ubuntu 18.04
        // that throw an error like this
        // InvalidArgumentException  : Cannot write to directory "/home/.../test/web/backend/storage/app/public/products/2019/1/10"

        $filepath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . config('project.product.images_folder') . DIRECTORY_SEPARATOR . $date->year . DIRECTORY_SEPARATOR . $date->month . DIRECTORY_SEPARATOR . $date->day . DIRECTORY_SEPARATOR . $main_image_name);
        $getimagesize = getimagesize($filepath);
        $div = $getimagesize[0] / $getimagesize[1];

        foreach (config('project.product.image_sizes') as $version => $sizes)
        {
            $image_path = $uploads_path . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . $main_image_name;

            $new_w = $div < config('project.product.image_divisions.min') ? null : $sizes['w'];
            $new_h = $div > config('project.product.image_divisions.max') ? null : $sizes['h'];

            $img = Image::make($filepath)
                ->resize($new_w, $new_h, function ($constraint) use ($div) {
                    if($div < config('project.product.image_divisions.min') || $div > config('project.product.image_divisions.max')) {
                        $constraint->aspectRatio();
                    }
                });
            $img->insert(public_path('images/watermarks/' . $version . '_watermark.png'), 'bottom-right', $sizes['pb'], $sizes['pr']);

            if($div < config('project.product.image_divisions.min') || $div > config('project.product.image_divisions.max')){
                $img->resizeCanvas($sizes['w'], $sizes['h'], 'center', false, '#000000');
                Image::canvas($sizes['w'], $sizes['h'])->insert($img, 'center')->save($image_path);
            }

            $img->save($image_path);
        }

        $statuses = [
            Product::BLOCKED,
            Product::PENDING,
            Product::APPROVED,
        ];
        $status = $faker->randomElement($statuses);
        $random_title = $faker->sentence(mt_rand(1, 4), true);
        $random_description = $faker->realText(200, 1);
        $admin_id = $faker->randomElement($admin_ids_array);

        Product::create([
            'title' => $random_title,
            'description' => $random_description,
            'main_image' => $main_image_name,
            'admin_id' => $admin_id,
            'status' => $status,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
