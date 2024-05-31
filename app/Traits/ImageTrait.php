<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Hashids\Hashids;

trait ImageTrait
{
    public function storeImage(Model $model, $image)
    {
        if ($model->image_path !== null && $model->image_path !== '' && \File::exists($model->image_path))
            unlink(public_path() . $model->image_path);

        $image = \Image::make($image);
        $ext = (new \Symfony\Component\Mime\MimeTypes)->getExtensions($image->mime());

        $hashids = new Hashids('secretkey', 4);
        $strRandom = Str::random(4) . $hashids->encode($model->uuid);

        $path = storage_path() . '/app/public/files/images/'.$model->uuid.'/';
        \File::isDirectory($path) or \File::makeDirectory($path, 0777, true, true);
        $filePath = $path . 'logo-'.$strRandom.'.'.$ext[0];
        $image->save($filePath);

        $model->image_path = '/storage/files/images/'.$model->uuid.'/logo-'.$strRandom.'.'.$ext[0];
        $model->save();
    }
}
