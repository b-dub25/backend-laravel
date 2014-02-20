<?php

class StaffTypeController extends BaseController {

	public function create(){
		$input = Input::all();
		
		$validatedInput = StaffType::validate(Input::all());

		$messages = $validatedInput->messages();

		// if any error messages, don't create and return errors.
		if(!$messages->all()){
			try{

				$course = StaffType::create($input);

				return Response::json(array('status' => 201, 'message' => 'StaffType created'), 201);

			}catch(Exception $e){
				return Response::json(array('status' => 400, 
					'message' => 'Failed to create StaffType', 'error' => $e), 400);
			}
		}
		return Response::json(array('status' => 400,
		 'message' => 'Failed to create StaffType', 'error' => $messages->all() ), 400);
	}

	public function delete(){
		
		try{
			$id = Input::get('id');
			StaffType::findOrFail($id)->forceDelete();
			
		}catch(exception $e){
			return Response::json(array('status' => 400, 	
			'message' => 'Failed to delete staffType.', 'error' => $e->getMessage()), 400);
		}
		return Response::json(array('status' => 200, 'message' => 'staffType Deleted'), 200);
	}

	
public function get(){
		
		try{	

			if(!Input::has('id'))
				return Response::json(StaffType::all());

			$id = Input::get('id');

			$staffType = StaffType::findOrFail($id)->toArray();
			return Response::json($staffType);

		}catch(exception $e){
			return Response::json(array('status' => 400, 	
			'message' => 'Failed to get staffType.', 'error' => $e->getMessage()), 400);
		}		
	}

	public function getUserStaffType(){


		try{	
			$id = Input::get('id');
			$user = User::findOrFail($id);

			$users = array();
			array_push($users, array('user' => $user->toarray(),
			'StaffType' => $user->staffTypeArr()));

			return Response::json($users);

		}catch(exception $e){
			return Response::json(array('status' => 400, 	
			'message' => 'Failed to get staffType.', 'error' => $e->getMessage()), 400);
		}
	}

	public function set(){

		if(Input::has('type')){
			$input = Input::all();

			//update or create
			//************************ update currently wipes old data 

			$StaffType = (Input::has('id')) ? StaffType::find($input['id'])->update($input) : StaffType::create($input);
			
			return Response::json(array("response" => "created"));
		}
		app::abort(400);
	}


	public function setUserStaffType(){
			
		$userId = Input::get('user');
		$staffType = Input::get('staffType');
		try{

			User::findOrFail($userId)->staffType()->attach($staffType);
		}catch(exception $e){
			return Response::json(array('status' => 400, 
				'message' => 'Failed to assign staffType', 'error' => $e->getMessage()), 400);
		}
		return Response::json(array('status' => 201, 'message' => 'staffType assigned'), 201);
	}

}