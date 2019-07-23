<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    protected $seed_items = [];

    public function create_dev_users()
    {
        User::create([
            'name' => config('project.seed.dev_name'),
            'email' => config('project.seed.dev_email'),
            'status' => User::ACTIVE,
            'password' => bcrypt(config('project.seed.dev_password')),
        ]);
    }

    public function run()
    {
        $this->create_dev_users();

        // necessary definitions
        $faker = Factory::create('en_US');
        $date = Carbon::now();
        $initial_path = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . config('project.user.images_folder'));

        // creates 'storage/app/public/user-images' folder if it's not exists
        if(!File::exists($initial_path)) {
            File::makeDirectory($initial_path, $mode = 0777, true, true);
        }

        // creates something like '2019/7/3/' inside of 'user-images' (in storage)
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

        $uploads_path = public_path('uploads' . DIRECTORY_SEPARATOR . config('project.user.images_folder'));
//        if (!file_exists($uploads_path)) {
//            mkdir($uploads_path, 0755, true);
//        }

        // creates 'small', 'medium', 'large' folders inside of 'public/user-images/'
        foreach (config('project.user.image_sizes') as $version => $sizes){
            if (!file_exists($uploads_path . DIRECTORY_SEPARATOR . $version)) {
                mkdir($uploads_path . DIRECTORY_SEPARATOR . $version, 0755, true);
            }
        }

        $user_statuses = [
            User::ACTIVE,
            User::NOT_ACTIVE,
        ];

        // add candidate users to the $seed_items
        for ($i = 0; $i < config('project.seed.users_count'); $i++) {
            $status = $faker->randomElement($user_statuses);
            $this->uploadImage($uploads_path, $date, $faker, $status);
        }

        // create users via importing $seed_items to 'users' table
        DB::table('users')->insert($this->seed_items);
    }

    public function uploadImage($uploads_path, $date, $faker, $status): void
    {
        // storage folder path
        $faker_image_folder_path = 'storage' . DIRECTORY_SEPARATOR .
            'app' . DIRECTORY_SEPARATOR .
            'public' . DIRECTORY_SEPARATOR .
            config('project.user.images_folder') . DIRECTORY_SEPARATOR .
            $date->year . DIRECTORY_SEPARATOR .
            $date->month . DIRECTORY_SEPARATOR .
            $date->day;

        // download requested image to storage folder
        $main_image_name = $faker->image($faker_image_folder_path,
            config('project.seed.faker_image_width'),
            config('project.seed.faker_image_height'),
            null,
            false);

        // get generated image path (including the name) from storage
        $filepath = storage_path('app' . DIRECTORY_SEPARATOR .
            'public' . DIRECTORY_SEPARATOR .
            config('project.user.images_folder') . DIRECTORY_SEPARATOR .
            $date->year . DIRECTORY_SEPARATOR .
            $date->month . DIRECTORY_SEPARATOR .
            $date->day . DIRECTORY_SEPARATOR .
            $main_image_name);

        $getImageSize = getimagesize($filepath);
        $div = $getImageSize[0] / $getImageSize[1]; // division of image sizes

        foreach (config('project.user.image_sizes') as $version => $sizes)
        {
            $image_path = $uploads_path . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . $main_image_name;

            $new_w = $div < config('project.user.image_divisions.min') ? null : $sizes['w'];
            $new_h = $div > config('project.user.image_divisions.max') ? null : $sizes['h'];

            // outputs processed image in 'public/user-images/<VERSION>/' folder
            $img = Image::make($filepath)
                ->resize($new_w, $new_h, function ($constraint) use ($div) {
                    if($div < config('project.user.image_divisions.min') || $div > config('project.user.image_divisions.max')) {
                        $constraint->aspectRatio(); // resize image, if division of dimensions aren't between 1.2 and 2
                    }
                });

            // make watermark for current image
            $img->insert(public_path('images' . DIRECTORY_SEPARATOR . 'watermarks' . DIRECTORY_SEPARATOR . $version . '_watermark.png'),
                'bottom-right',
                $sizes['pb'],
                $sizes['pr']);

            // if division of dimensions aren't between 1.2 and 2, then add a color
            if($div < config('project.user.image_divisions.min') || $div > config('project.user.image_divisions.max')){
                $img->resizeCanvas($sizes['w'], $sizes['h'], 'center', false, '#000000');
                Image::canvas($sizes['w'], $sizes['h'])->insert($img, 'center')->save($image_path);
            }

            $img->save($image_path);
        }

        // collect all users data in one variable
        $this->seed_items[] = [
            'name' => $faker->name,
            'email' => $faker->email,
            'status' => $status,
            'password' => bcrypt('secret'),
            'created_at' => $date,
            'updated_at' => $date,

            'address' => $faker->address,
            'birth_year' => $faker->numberBetween(config('project.user.min_birth_year'), $date->year - config('project.user.teen_age')),
            'image' => $main_image_name,
            'original_image_path' => $date->year . DIRECTORY_SEPARATOR . $date->month . DIRECTORY_SEPARATOR . $date->day . DIRECTORY_SEPARATOR . $main_image_name,
        ];
    }

}
