<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\gameturn;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class apiController extends Controller
{
    function apiLogin(Request $request)
    {
        $request->validate([
                'name' => 'required',
                'password' => 'required'
                ]);
        $data = $request->only('name','password');
        if(Auth::attempt($data)){
            $user = User::where('name', $request->name)->first();
            $token = $user->createToken("auth_token")->plainTextToken;
            $data = ['token' => $token]; 
            return response()->json($data, 200);
        }else{
            $data = [
                "state" => "190",
                "messege" => "wrong info",
                "data" => ""
                ];
            return response()->json($data, 200);
        }
    }

    public function api()
    {
        $gameturn = gameturn::all();
        $gameturn = $gameturn->where('id' ,1);
        foreach($gameturn as $s){$gameturnOBJ = $s;}
        if($gameturn->count() > 1)
        {
            $data = [
            "state" => "100",
            "messege" => "more than one gameturn!!",
            "data" => ""
            ];
        } elseif ($gameturn->count() < 1)
        {
            $data = [
                "state" => "150",
                "messege" => "gameturn!!",
                "data" => ""
                ];
        } else
        {
            if ($gameturnOBJ->permit == 1 || $gameturnOBJ->permit == 2)
            {
                $path = public_path("assets/uploades/gcodes/gcode.txt");
                $contnent = file_get_contents($path);
                $gameturnOBJ->godeContent = $this->parseCsvWithNewline($contnent);
                $data = [
                "state" => "200",
                "messege" => "succes",
                "data" => $gameturnOBJ
                ];
                $affectedRows = gameturn::where('id','1')
                ->update([
                    'realTimeInfo' => '1'
                ]);
            }else
            {
            $data = [
                "state" => "180",
                "messege" => "Mo permit!!",
                "data" => ""
                ];
            }
        }
        return response()->json($data, 200);
    }        
    
    function apipost(Request $request)
    {
        $stateCode = $request->code;
        if ($request->code != "") 
        {
            if ($request->code == "2") 
            {
                $affectedRows = gameturn::where('id','1')
                ->update([
                    'permit'=> '0',
                    'realTimeInfo' => '0'
                ]);
                if($affectedRows = 0) 
                {
                    return response()->json(['state' => '450' ,'message' => 'affectedRows is zero!!'], 200); 
                } else
                {
                    file_put_contents(public_path("assets/uploades/gcodes/gcode.txt"), '');
                    return response()->json(['state' => '200' ,'message' => 'state updated'], 200); 
                }
            }
        } else 
        {
            return response()->json("state Not Recived!!", 200);
        }
    }
}
