<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ad_post;
use App\Models\Feature;
use App\Models\image;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    //
    function deleteAd($id){
        try{
            $record = ad_post::find($id);
            $title_image = $record->title_image;
            $imagesList = image::where('ad_post_id', $id)->get();
            $res = $record->delete();
            
            Storage::disk("public")->delete($title_image); //delete titile image from storage
            if(sizeof($imagesList)>1){  // check if it has detail images
                foreach ($imagesList as $value) {
                    Storage::disk("public")->delete($value->image); // delete images from storage
                }
            }

            if($res)
            return response()->json([
                'status' => true,
                'message' => 'Record Deleted Successfully!'
            ],200);
        }
        catch (Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ],200);
        }
    }
    function loadAds()
    {
        try{
            $data = ad_post::select('brand', 'name', 'variant', 'price', 'transmission', 'engine_size', 'location', 'id', 'title_image', 'created_at')
            ->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => true,
                'data' => $data
            ],200);
        }
        catch(Exception $err){
            return response()->json([
                'status' => false,
                'message' => 'Cannot Fetch Data!',
            ],200);
        }
    }
    function loadUserAds($username){
        try{
            $data = ad_post::select('brand', 'name', 'variant', 'price', 'transmission', 'engine_size', 'location', 'id', 'title_image', 'created_at')
            ->where('posted_by',$username)->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => true,
                'data' => $data
            ],200);
        }
        catch(Exception $err){
            return response()->json([
                'status' => false,
                'message' => 'Cannot Fetch Data!',
            ],200);
        }
        
    }
    function adDetail(Request $req)
    {
        try {
            $data = ad_post::find($req->id);
            $features = Feature::where('ad_post_id',$data->id)->pluck('feature')->toArray();
            $images = image::where('ad_post_id',$data->id)->pluck('image')->toArray();
            return response()->json([
                'status' => true,
                'posted_by' => $data->posted_by,
                'brand' => $data->brand,
                'name' => $data->name,
                'variant' => $data->variant,
                'created_at' => $data->created_at->diffForHumans(),
                'model' => $data->model,
                'assembly' => $data->assembly,
                'engine_size' => $data->engine_size,
                'color' => $data->color,
                'fuel_type' => $data->fuel_type,
                'transmission' => $data->transmission,
                'milage' => $data->milage,
                'location' => $data->location,
                'contact' => $data->contact,
                'registration_city' => $data->registration_city,
                'Price' => $data->Price,
                'discription' => $data->discription,
                'title_image' => $data->title_image,
                'features' => $features,
                'images' => $images
            ], 200);
        } catch (Exception $err) {
            return response()->json([
                'status' => false,
                'message' => $err->message
            ], 200);
        }
    }

    function store(Request $request)
    {
        //validating inputs

        $validate = Validator::make($request->all(), [
            'posted_by' => 'required',
            'brand' => 'required',
            'name' => 'required',
            'variant' => 'required',
            'model' => 'required|numeric|digits:4|min:1950|max:2022',
            'assembly' => 'required',
            'color' => 'required',
            'fuel' => 'required',
            'transmission' => 'required',
            'milage' => 'required|numeric|max:999999',
            'registration' => 'required',
            'contact' => 'required|numeric|digits:11',
            'location' => 'required',
            'price' => 'required|numeric',
            'title_Image' => 'required|image|max:5000',
            'discription' => 'required',
        ]);
        
        //through error if any validation fails

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validate->errors()->all()
            ], 200);
        }

        // storing title image in local storage 

        $image = $request->file('title_Image'); //get image
        $imgname = $request->posted_by . "_image_" . time() . "." . $image->getClientOriginalExtension(); //assign unique name
        $path = $image->storeAs(  // store image in store and assign path to path variable
            "images/adTitleImages",
            $imgname,
            "public"
        );
        $paths = [];

        

        

        try {
            $newAd = new ad_post;
            $newAd->posted_by = $request->posted_by;
            $newAd->brand = $request->brand;
            $newAd->name = $request->name;
            $newAd->variant = $request->variant;
            $newAd->model = $request->model;
            $newAd->assembly = $request->assembly;
            $newAd->engine_size = $request->engine;
            $newAd->color = $request->color;
            $newAd->fuel_type = $request->fuel;
            $newAd->transmission = $request->transmission;
            $newAd->milage = $request->milage;
            $newAd->registration_city = $request->registration;
            $newAd->contact = $request->contact;
            $newAd->location = $request->location;
            $newAd->Price = $request->price;
            $newAd->title_image = $path;
            $newAd->discription = $request->discription;

            $newAd->save();

            //storing other images
            
            $images = $request->file('other_images');//get images in variable
            for ($i = 0; $i < sizeof($images); $i++) { // assign unique names to all images
                $otherImageName = $request->posted_by . "_otherimage_". $newAd->id . "_". $i . "_" . time() . "." . $image->getClientOriginalExtension();
                $iname = $images[$i]->storeAs(
                    "images/detailImages",
                    $otherImageName,
                    "public"
                );
                array_push($paths,$iname);
            }


            $feature = (explode(",", $request->features));
            if (sizeof($feature) > 0) {
                for ($i = 0; $i < sizeof($feature); $i++) {
                    $newAd->feature()->create(['feature' => $feature[$i]]);
                }
            }
            if (sizeof($paths) > 0) {
                for ($i = 0; $i < sizeof($paths); $i++) {
                    $newAd->image()->create(['image' => $paths[$i]]);
                }
            }
            return response()->json([
                'status' => true,
                'message' => 'Ad Posted Successfully'
            ], 200);
        } catch (Exception $err) {
            return response()->json([
                'status' => false,
                'message' => $err->getMessage()
            ], 200);
        }
    }
}
