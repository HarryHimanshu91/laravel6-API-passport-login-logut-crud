<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Validator;

class ProductController extends Controller
{


    /*
     * @CreatedOn : 23-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : List All Products Api
     */


    public function listProduct()
    {
        try {
            $product = Product::all();
            if ($product) {
                return response()->json(['status' => 'true', 'message' => 'All Products List', 'data' => $product]);
            } else {
                return response()->json(['status' => 'false', 'message' => 'No Products Found', 'data' => []]);
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    /*
     * @CreatedOn : 23-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : Get Single Product Api
     */


    public function singleProduct($id)
    {
        try {
            $product = Product::where('id', $id)->first();
            if ($product) {
                return response()->json(['status' => 'true', 'message' => 'Product fetch successfully', 'data' => $product]);
            } else {
                return response()->json(['status' => 'false', 'message' => 'No Products Found', 'data' => []]);
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }



    /*
     * @CreatedOn : 23-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : Create Product Api
     */

    public function storeProduct(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:5',
                'file_path' => 'required|image',
                'description' => 'required|min:10',
                'price' => 'required',

            ]);

            if ($validator->fails()) {
            //     $error = $validator->errors()->all();  // show particular field error message
            //    return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
               return response()->json([
                'validate_err' => $validator->messages(),
                'status' => 'false',
                'statusCode' =>422

            ]);
            } else {
                $product = new Product();
                $product->name = $request->name;
                $product->description = $request->description;
                $product->price = $request->price;

                if ($request->file_path && $request->file_path->isValid()) {

                    $destination_path = 'public/images/products';
                    $image = $request->file('file_path');
                    $image_name = $image->getClientOriginalName();
                    $request->file('file_path')->storeAs($destination_path, $image_name);

                    // $file_name = time().'.'.$request->file_path->extension();
                    // $request->file_path->move(public_path('products'),$file_name);
                    // $path = "public/products/$file_name";
                    $product->file_path = $image_name;
                }
                $product->save();
                return response()->json(['status' => 'true','statusCode' =>200, 'message' => 'Product saved successfully', 'data' => $product]);
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }


    /*
     * @CreatedOn : 25-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : Delete Product Api
     */


    public function deleteProduct($id)
    {
        try {
            if (Product::where('id', $id)->exists()) {
                $product = Product::find($id);
                $product->delete();

                return response()->json(['status' => 200, 'message' => 'Product has been deleted successfully']);
            } else {
                return response()->json(['status' => 404, 'message' => 'No Product ID found']);
            }
        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

     /*
     * @CreatedOn : 25-7-2021
     * @CreatedBy : Himanshu Chand
     * @Description : UPdate Product Api
     */

    public function updateProduct($id, Request $request)
    {
        try{

          //  return $request->input();

          $validator = Validator::make($request->all(), [
            'name' => 'required|min:5',
            'description' => 'required|min:10',
            'price' => 'required',

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

                $product = Product::find($id);
                $product->name = $request->name;
                $product->description = $request->description;
                $product->price = $request->price;

                if ($request->file_path && $request->file_path->isValid()) {

                    $destination_path = 'public/images/products';
                    $image = $request->file('file_path');
                    $image_name = $image->getClientOriginalName();
                    $request->file('file_path')->storeAs($destination_path, $image_name);
                    $product->file_path = $image_name;
                }
                $product->save();
                return response()->json(['status' => 'true','statusCode' =>200, 'message' => 'Product updated successfully', 'data' => $product],200);
        }

        } catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }

    public function searchProduct($key)
    {
        try{
             $product = Product::where('name','like',"%$key%")->get();
             if(count($product)>0){
                return response()->json(['status' => 'true', 'message' => 'Product found successfully', 'data' => $product]);
             }else {
                return response()->json(['status' => 'false', 'message' => 'No Products Found', 'data' => []]);
            }
        }catch (\Exception $e) {
            return  response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
}
