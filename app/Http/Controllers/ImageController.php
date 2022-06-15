<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\ReusedModule\ImageUpload;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return Image::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $iu=new ImageUpload();
        $upload= $iu->multipleImageUpload($request->images,$request->product_id);
        if (count($upload) > 0) {
            return response()->json($upload,200);
        }else{
            return response()->json('error while uploading',401);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return $image;
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        $path= public_path().'/productimages/';
        if($image->path && file_exists($path.$image->path)){

             unlink($path.$image->path);
        }

        $image->delete();
        return response()->json('sucessfully deleted',200); 
    }
}
