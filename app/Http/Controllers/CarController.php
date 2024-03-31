<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function carList(Request $request){

        // get all cars
        $cars =  Car::select('cars.name as carname','cars.color as carcolor','cars.model as carmodel',
            'categories.name as categoryname','cars.id as carid')
            ->join('categories', 'cars.category_id', '=', 'categories.id')
            ->get();

        //Prepare response
        $response =[
            'message'=>'Car(s) Found',
            'detail' => $cars
        ];

        return response($response,200);
    }

    public function addCar(Request $request){

        $name = $request->name;
        $color = $request->color;
        $model = $request->model;
        $categoryId = $request->category_id;
        // check validation
        $validator=Validator::make($request->all(),[
            'name'        =>'required|unique:cars',
            'color'       => 'required',
            'model'       =>'required',
            'category_id' =>'not_in:0'
        ],[
            'name.required'       =>'Provide car name',
            'name.unique'         => 'Car name already provided',
            'color.required'      =>'Provide car color',
            'model.required'      =>'Provide car model',
            'category_id.not_in'  =>'Select Category',
        ]);
        // response if validation fails
        if($validator->fails()){
            $error = $validator->errors()->first();
            $response = [
                'error' => $error,
            ];
            return response($response, 422);
        }
        // insert car
        $car = Car::create([
            'name'       =>  $name,
            'color'      =>  $color,
            'model'      =>  $model,
            'category_id'=>  $categoryId
        ]);
        //response if car is added
        if($car->id) {
            $response = [
                'message' => 'car added successfully',
                'detail' => $car,
            ];
            return response($response, 201);
        }
        // response if car does not add
        $response = [
            'message' => 'Something went wrong',
            'detail'=>null,
        ];
        return response($response, 400);
    }

    public function editCar($id){

        $car =  Car::find($id);
        if($car!=null){
            // if record is found against provided id
            $response =[
                'message'=> 'Car Found',
                'detail' => $car,
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
    public function updateCar(Request $request){

        $name = $request->name;
        $color = $request->color;
        $model = $request->model;
        $categoryId = $request->category_id;
        $id         = $request->id;
        // check validation
        $validator=Validator::make($request->all(),[
            'name'        =>'required',
            'color'       => 'required',
            'model'       =>'required',
            'category_id' =>'not_in:0',
            'id'          =>'not_in:0',
        ],[
            'name.required'       =>'Provide car name',
            'color.required'      =>'Provide car color',
            'model.required'      =>'Provide car model',
            'category_id.not_in'  =>'Select Category',
            'id.not_in'           =>'Provide id',
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
            'name'       =>  $name,
            'color'      =>  $color,
            'model'      =>  $model,
            'category_id'=>  $categoryId
        ];
        $result = Car::where('id', $id)->update($newdata);

        if($result==1){
            // response when record is updated
            $response=[
                'message'=> 'Car updated',
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

    public function deleteCar($id){

        $cars = Car::where('id',$id)->first();

        // check if car id exist
        if(!empty($cars)){
            $result = Car::where('id', $id)->delete();
            if($result==1){
                $response = [
                    'message' => 'Car deleted'
                ];
                return response($response, 200);
            }
        }

        // if provided id does not exist or record already deleted
        $response = [
            'message' => 'Something went wrong'
        ];
        return response($response, 404);
    }

}
