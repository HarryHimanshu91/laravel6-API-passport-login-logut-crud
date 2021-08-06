<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Image;
use Validator;

class ImageController extends Controller
{


    public function listImages()
    {
        try {
            $data = Image::all();
            if ($data) {
                return response()->json(['status' => 'true','statusCode' =>200, 'message' => 'All Data List', 'data' => $data]);
            } else {
                return response()->json(['status' => 'false', 'statusCode' =>404, 'message' => 'No Data Found', 'data' => []]);
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function singleImage($id)
    {
        try {
            $data = Image::where('id', $id)->first();
            if ($data) {
                return response()->json(['status' => 'true', 'statusCode' =>200, 'message' => 'Data fetch successfully', 'data' => $data]);
            } else {
                return response()->json(['status' => 'false', 'statusCode' =>404, 'message' => 'No Data Found', 'data' => []]);
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    public function storeImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|min:5',
                'file' => 'required|image',
                'description' => 'required|min:10',    
            ]);
    
            if ($validator->fails()) {
               return response()->json([
                'validate_err' => $validator->messages(),
                'status' => 'false',
                'statusCode' =>422
    
            ]);
            } else {
                $data = new Image();
                $data->title = $request->title;
                $data->description = $request->description;
                if ($request->file && $request->file->isValid()) {
    
                    $destination_path = 'public/images/gallery';
                    $image = $request->file('file');
                    $image_name = time().'.'.$image->extension();
                    $request->file('file')->storeAs($destination_path, $image_name);
    
                    // $file_name = time().'.'.$request->file_path->extension();
                    // $request->file_path->move(public_path('products'),$file_name);
                    // $path = "public/products/$file_name";
                    $data->file = $image_name;
                }
                $data->save();
                return response()->json(['status' => 'true','statusCode' =>200, 'message' => 'Data saved successfully', 'data' => $data]);
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    public function updateImage($id, Request $request)
    {
        try{

          //  return $request->input();

          $validator = Validator::make($request->all(), [
            'title' => 'required|min:5',
            'description' => 'required|min:10',
          

        ]);

        if ($validator->fails()) {
           // $error = $validator->errors()->all()[0];  // show particular field error message
           // return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);

           return response()->json([
            'validate_err' => $validator->messages(),
            'status' => 'false',
            'statusCode' =>422

        ]);

        } else {

                $data = Image::find($id);
                $data->title = $request->title;
                $data->description = $request->description;
                if ($request->file && $request->file->isValid()) {
    
                    $destination_path = 'public/images/gallery';
                    $image = $request->file('file');
                    $image_name = time().'.'.$image->extension();
                    $request->file('file')->storeAs($destination_path, $image_name);
    
                    // $file_name = time().'.'.$request->file_path->extension();
                    // $request->file_path->move(public_path('products'),$file_name);
                    // $path = "public/products/$file_name";
                    $data->file = $image_name;
                }
                $data->save();
                return response()->json(['status' => 'true','statusCode' =>200, 'message' => 'Product updated successfully', 'data' => $data],200);
        }

        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    public function deleteImage($id)
    {
        $data = Image::find($id);

        if($data) {
            $data->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Image data Deleted successfully'
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => "No Image ID found",
            ]);
        }
       
    }

}
