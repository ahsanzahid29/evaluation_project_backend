<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    public function categoryList(Request $request){
        // get all categories
        $category = Category::all();
        //Prepare response
        $response =[
            'message'=>'Category(s) Found',
            'detail' => $category
        ];

        return response($response,200);
    }
    public function addCategory(Request $request){

        $name = $request->name;
        // check validation
        $validator=Validator::make($request->all(),[
            'name'=>'required|unique:categories',
        ],[
            'name.required'=>'Provide category name',
            'name.unique'=>'Category name already taken',
        ]);
        // response if validation fails
        if($validator->fails()){
            $error = $validator->errors()->first();
            $response = [
                'error' => $error,
            ];
            return response($response, 422);
        }
        // insert category
        $category = Category::create([
            'name' => $name
        ]);
        //response if category is added
        if($category->id) {
            $response = [
                'message' => 'Category added successfully',
                'detail' => $category,
            ];
            return response($response, 201);
        }
        // response if category does not add
        $response = [
            'message' => 'Something went wrong',
            'detail'=>null,
        ];
        return response($response, 400);

    }

    public function editCategory($id){
        $category = Category::find($id);
        if($category!=null){
            // if record is found against provided id
            $response =[
                'message'=> 'Category Found',
                'detail' => $category,
            ];
            return response($response,200);
        }
        // if no record exist agaist provided id
        $response=[
            'message'=> 'No record found',
            'detail'=> null
        ];
        return response($response,404);
    }

    public function updateCategory(Request $request){

        $name = $request->name;
        $id   = $request->id;
        // check validation
        $validator=Validator::make($request->all(),[
            'name'=>'required',
            'id'  => 'required'
        ],[
            'name.required'=>'Provide category name',
            'id.required'=>'Provide category id',
        ]);
        // response if validation fails
        if($validator->fails()){
            $error = $validator->errors()->first();
            $response = [
                'error' => $error,
            ];
            return response($response, 422);
        }
        // update the record
        $newdata = [
            'name'      => $request->name,
        ];
        $result = Category::where('id', $id)->update($newdata);

        if($result==1){
            // response when record is updated
            $response=[
                'message'=> 'Category updated',
            ];

            return response($response,200);
        }
        else{
            // when id is not found
            $response=[
                'message'=> 'Something went wrong',
            ];

            return response($response,404);

        }
    }

    public function deleteCategory($id){

        $cars = Car::where('category_id',$id)->first();
        // check if category id exist in cars table
        if(!empty($cars)){
            $response = [
                'message'=> 'This action cannot be performed',
            ];
            return response($response,409);
        }
        $result = Category::where('id', $id)->delete();

        // if category is successfully deleted
        if($result==1){
            $response = [
                'message' => 'Category deleted'
            ];
            return response($response, 200);
        }

        // if provided id does not exist or record already deleted
        $response = [
            'message' => 'Something went wrong'
        ];
        return response($response, 404);






    }
}
