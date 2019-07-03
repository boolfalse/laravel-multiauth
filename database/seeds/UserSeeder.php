<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class UserSeeder extends Seeder
{
    public function create_dev_users()
    {
        User::create([
            'name' => config('project.seed.dev_name'),
            'email' => config('project.seed.dev_email'),
            'password' => bcrypt(config('project.seed.dev_password')),
        ]);
    }

    public function run()
    {
        $this->create_dev_users();

        $faker = Factory::create('en_US');
        $date = Carbon::now();
        $initial_path = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . config('project.user.images_folder'));

        if(!File::exists($initial_path)) {
            File::makeDirectory($initial_path, $mode = 0777, true, true);
        }

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
        foreach (config('project.user.image_sizes') as $version => $sizes){
            if (!file_exists($uploads_path . DIRECTORY_SEPARATOR . $version)) {
                mkdir($uploads_path . DIRECTORY_SEPARATOR . $version, 0755, true);
            }
        }

        for ($i = 0; $i < config('project.seed.users_count'); $i++) {
            $this->uploadImage($uploads_path, $date, $faker);
        }
    }

    public function uploadImage($uploads_path, $date, $faker)
    {
        $faker_image_folder_path = 'storage' . DIRECTORY_SEPARATOR .
            'app' . DIRECTORY_SEPARATOR .
            'public' . DIRECTORY_SEPARATOR .
            config('project.user.images_folder') . DIRECTORY_SEPARATOR .
            $date->year . DIRECTORY_SEPARATOR .
            $date->month . DIRECTORY_SEPARATOR .
            $date->day;

        $main_image_name = $faker->image($faker_image_folder_path,
            config('project.seed.user_faker_image_width'),
            config('project.seed.user_faker_image_height'),
            null,
            false);

        $filepath = storage_path('app' . DIRECTORY_SEPARATOR .
            'public' . DIRECTORY_SEPARATOR .
            config('project.user.images_folder') . DIRECTORY_SEPARATOR .
            $date->year . DIRECTORY_SEPARATOR .
            $date->month . DIRECTORY_SEPARATOR .
            $date->day . DIRECTORY_SEPARATOR .
            $main_image_name);

        $getImageSize = getimagesize($filepath);
        $div = $getImageSize[0] / $getImageSize[1];

        foreach (config('project.user.image_sizes') as $version => $sizes)
        {
            $image_path = $uploads_path . DIRECTORY_SEPARATOR . $version . DIRECTORY_SEPARATOR . $main_image_name;

            $new_w = $div < config('project.user.image_divisions.min') ? null : $sizes['w'];
            $new_h = $div > config('project.user.image_divisions.max') ? null : $sizes['h'];

            $img = Image::make($filepath)
                ->resize($new_w, $new_h, function ($constraint) use ($div) {
                    if($div < config('project.user.image_divisions.min') || $div > config('project.user.image_divisions.max')) {
                        $constraint->aspectRatio();
                    }
                });
            $img->insert(public_path('images' . DIRECTORY_SEPARATOR . 'watermarks' . DIRECTORY_SEPARATOR . $version . '_watermark.png'), 'bottom-right', $sizes['pb'], $sizes['pr']);

            if($div < config('project.user.image_divisions.min') || $div > config('project.user.image_divisions.max')){
                $img->resizeCanvas($sizes['w'], $sizes['h'], 'center', false, '#000000');
                Image::canvas($sizes['w'], $sizes['h'])->insert($img, 'center')->save($image_path);
            }

            $img->save($image_path);
        }

        User::create([
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => bcrypt('secret'),
            'created_at' => $date,
            'updated_at' => $date,

            'image' => $main_image_name,
            'original_image_path' => $date->year . '/' . $date->month . '/' . $date->day . '/' . $main_image_name,
        ]);
    }

}
