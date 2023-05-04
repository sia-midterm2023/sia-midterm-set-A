<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request; 
use App\Traits\ApiResponser;
use App\Models\User; 

class StudentController extends Controller
{
    use ApiResponser;

    private $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }
// browse (get)
    public function browse(){
        $users = User::all();
    // return response()->json($users, 200); 
        return $this->successResponse($users);
    }

// search (get, showID)
    public function search($id){ 
    // return User::where('studid', '=', $id)->get();
        $user = User::where('studid', $id)->first();
        if($user){
            return $this->successResponse($user);
        }
        {
            return $this->errorResponse('User ID Does Not Exists', Response::HTTP_NOT_FOUND);
        }
        }

// insert (post)
    public function insert(Request $request){
        $rules = [
            $this->validate($request, [
                'lastname' => 'required|alpha:max:50',
                'firstname' => 'required|alpha:max:50',
                'middlename' => 'required|alpha:max:50',
                'bday' => 'date',
                'age' => 'required|integer|max:49'
            ])  
        ];
        $this->validate($request, $rules);
        $user = User::create($request->all());
        
        return $this->successResponse($user, Response::HTTP_CREATED);
    }

// update (put)
    public function update(Request $request, $id){
        $rules = [
            $this->validate($request, [
                'lastname' => 'required|alpha:max:50',
                'firstname' => 'required|alpha:max:50',
                'middlename' => 'required|alpha:max:50',
                'bday' => 'date',
                'age' => 'required|integer|max:49'
            ])  
        ];
        $this->validate($request, $rules);
    
        $user = User::findOrFail($id);
        $user->fill($request->all());
    
        if ($user->isClean()) {
            return Response()->json("At least one value must change", 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $user->save();
            return $this->successResponse($user);
        }
    }

// delete (delete)
    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();
    
        return $this->successResponse($user);
    }
}
