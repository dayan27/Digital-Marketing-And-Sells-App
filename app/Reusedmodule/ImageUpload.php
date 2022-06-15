<?php
namespace App\ReusedModule;

use App\Models\Image;
use Illuminate\Support\Str;

class ImageUpload {

    public function multipleImageUpload($files,$product_id){

        try {
            $images=[];

            foreach ($files as $file) {

               $name = Str::random(5).time().'.'.$file->extension();
               $file->move(public_path().'/productimages/', $name);
               $image=new Image();
               $image->image_path=$name;
               $image->product_id=$product_id;
               $image->save();
               $image->refresh();
               $img['id'] = $image->id;
               $img['path'] = asset('/productimages').'/'.$name;
               $images[]=$img;
        }

        return $images;
            } catch (\Throwable $th) {

                return $th;
        }
    }



}


















?>