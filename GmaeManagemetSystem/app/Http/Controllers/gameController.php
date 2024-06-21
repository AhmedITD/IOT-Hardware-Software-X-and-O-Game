<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\gameturn;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
class gameController extends Controller
{
    function index()
    {
        return view("game/index");
    }

    function playPost(Request $request)
    {
        switch($request->permit)
        {
            case "0":
                $affectedRows = gameturn::where('id','1')
                ->update([
                    'permit'=> '0',
                ]);
                if($affectedRows = 0) 
                {
                    return response()->json(['state' => '450' ,'message' => 'affectedRows is zero!!'], 200); 
                } else
                {
                    file_put_contents(public_path("assets/uploades/gcodes/gcode.txt"), '');
                    return response()->json(['state' => '200' ,'message' => 'reseted succfully!!'], 200); 
                }
                break;
            case "1":
                $square = $request->square;
                $turn = $request->currentTurn;
                $width = 19.5/2;
                $hieght = 17.6/2;
                $Xthcnes = 0.4875;
                $Ythcnes = 0.44;
                    switch ($square)
                    {
                        case "0":
                            $Xcenter =$width + $Xthcnes;
                            $Ycenter =$hieght + $Ythcnes;
                            break;     
                        case "1":
                            $Xcenter =($width + $Xthcnes) * 3;
                            $Ycenter =$hieght + $Ythcnes;
                            break;  
                        case "2":
                            $Xcenter =($width + $Xthcnes) * 5;
                            $Ycenter =$hieght + $Ythcnes;
                            break;   
                        case "3":
                            $Xcenter =$width + $Xthcnes;
                            $Ycenter =($hieght + $Ythcnes) * 3;
                            break;     
                        case "4":
                            $Xcenter =($width + $Xthcnes) * 3;
                            $Ycenter =($hieght + $Ythcnes) * 3;
                            break;  
                        case "5":
                            $Xcenter =($width + $Xthcnes) * 5;
                            $Ycenter =($hieght + $Ythcnes) * 3;
                            break;  
                        case "6":
                            $Xcenter =$width + $Xthcnes;
                            $Ycenter =($hieght + $Ythcnes) * 5;
                            break;     
                        case "7":
                            $Xcenter =($width + $Xthcnes) * 3;
                            $Ycenter =($hieght + $Ythcnes) * 5;
                            break;  
                        case "8":
                            $Xcenter =($width + $Xthcnes) * 5;
                            $Ycenter =($hieght + $Ythcnes) * 5;
                            break;
                        default : return response()->json(['state' => '300' ,'message' => 'undefine square!!'], 200);
                            break;  
                    }
//we gonna make the x values in - to reflect the direction of the axis manually         
                    switch ($turn) 
                    {
                        case 'x':
$Gcode =
'G21
G90
M03 S80
G0 X'. -$Xcenter - ($width/2) .' Y'. $Ycenter - ($hieght/2) .'F4000
M05 S10
G1 X'. -$Xcenter + ($width/2) .' Y'. $Ycenter + ($hieght/2) .'F1000
M03 S80
G0 X'. -$Xcenter - ($width/2) .' Y'. $Ycenter + ($hieght/2) .'F4000
M05 S10
G1 X'. -$Xcenter + ($width/2) .' Y'. $Ycenter - ($hieght/2) .'F1000
M03 S80
G1 X0 Y0 F4000';
                        break;
                        case 'o':
$Gcode =
'G21
G90
M03 S80
G0 X'. -$Xcenter .' Y'. $Ycenter + ($hieght/2) . 'F4000
M05 S10
G3 X'. -$Xcenter .' Y'. $Ycenter + ($hieght/2) .' I0 J'. -$hieght/2 . 'F4000
M03 S80
G1 X0 Y0 F4000';                            
                            break;
                        default: return response()->json(['state' => '350' ,'message' => 'undefine turn!!'], 200);
                            break;
                    } 
file_put_contents(public_path("assets/uploades/gcodes/gcode.txt"), $Gcode);
                $affectedRows = gameturn::where('id','1')
                ->update([
                    'permit'=> '1',
                    'realTimeInfo' => '1'
                ]);
                if($affectedRows = 0) 
                {
                    return response()->json(['state' => '450' ,'message' => 'affectedRows is zero!!'], 200); 
                }
                return response()->json(['state' => '200' ,'message' => 'updated succesfully!!'], 200);
                break;
            case "2":
                $board = $request->boardArray;
                $width = 19.5/2;
                $hieght = 17.6/2;
                $Xthcnes = 0.4875;
                $Ythcnes = 0.44;
                if($board[0] == $board[1] && $board[1] == $board[2])
                {
                    $Xstart =$Xthcnes;
                    $Ystart =$hieght + $Ythcnes;
                    $Xend = ($width + $Xthcnes) * 6;
                    $Yend = $Ystart;
                }
                elseif($board[3] == $board[4] && $board[4] == $board[5])
                {
                    $Xstart =$Xthcnes;
                    $Ystart =($hieght + $Ythcnes) * 3;
                    $Xend = ($width + $Xthcnes) * 6;
                    $Yend = $Ystart;
                }
                elseif($board[6] == $board[7] && $board[7] == $board[8])
                {
                    $Xstart = $Xthcnes;
                    $Ystart = ($hieght + $Ythcnes) * 5;
                    $Xend = ($width + $Xthcnes)* 6;
                    $Yend = $Ystart;
                }
                elseif($board[0] == $board[3] && $board[3] == $board[6])
                {
                    $Xstart = $width + $Xthcnes;
                    $Ystart = $Ythcnes;
                    $Xend = $Xstart;
                    $Yend = ($hieght + $Ythcnes) * 6;
                }
                elseif($board[1] == $board[4] && $board[4] == $board[7])
                {
                    $Xstart = ($width + $Xthcnes) * 3 ;
                    $Ystart = $Ythcnes;
                    $Xend = $Xstart;
                    $Yend = ($hieght + $Ythcnes) * 6;
                }elseif($board[2] == $board[5] && $board[5] == $board[8])
                {
                    $Xstart = ($width + $Xthcnes) * 5;
                    $Ystart = $Ythcnes;
                    $Xend = $Xstart;
                    $Yend = ($hieght + $Ythcnes) * 6;
                }
                elseif($board[2] == $board[4] && $board[4] == $board[6])
                {
                    $Xstart = ($width + $Xthcnes) * 6;
                    $Ystart = $Ythcnes;
                    $Xend = $Xthcnes;
                    $Yend = ($hieght + $Ythcnes) * 6;
                }
                elseif($board[0] == $board[4] && $board[4] == $board[8])
                {
                    $Xstart = $Xthcnes;
                    $Ystart = $Ythcnes;
                    $Xend = ($width + $Xthcnes) * 6 ;
                    $Yend = ($hieght + $Ythcnes) * 6;
                } else
                {
                    return response()->json(['state' => '500' ,'message' => 'undefine win state!!'], 200);
                }
$Gcode =
'G21
G90
F1000
M03 S80
G0 X'. -$Xstart .' Y'. $Ystart . 'F4000
M05 S10
G1 X'. -$Xend .' Y'. $Yend . 'F4000
M03 S80
G1 X0 Y0 F4000';                            
            file_put_contents(public_path("assets/uploades/gcodes/gcode.txt"), $Gcode);
            $affectedRows = gameturn::where('id','1')
            ->update([
                'permit'=> '1',
                'realTimeInfo' => '1'
            ]);
            if($affectedRows = 0) 
            {
                return response()->json(['state' => '450' ,'message' => 'affectedRows is zero!!'], 200); 
            }
            return response()->json(['state' => '200' ,'message' => 'win state updated!!'], 200);
                break;     
            case "3":
                // handeling the draw state :)
                $affectedRows = gameturn::where('id','1')
                ->update([
                    'permit'=> '0',
                ]);
                if($affectedRows = 0) 
                {
                    return response()->json(['state' => '450' ,'message' => 'affectedRows is zero!!'], 200); 
                } else
                {
                    file_put_contents(public_path("assets/uploades/gcodes/gcode.txt"), '');
                    return response()->json(['state' => '200' ,'message' => 'draw!!'], 200); 
                }
                
                break;
            case"4":
                $realTimeInfo = gameturn::where('id','1')->first()->realTimeInfo;
                if ($realTimeInfo != 0) 
                {
                    return response()->json(['state' => '600' ,'message' => 'busy'], 200); 
                } else 
                {
                    return response()->json(['state' => '200' ,'message' => 'Not busy'], 200); 
                }
                break;  
                case"5":
                    $realTimeInfo = gameturn::where('id','1')->first()->realTimeInfo;
                    if ($realTimeInfo != 0) 
                    {
                        return response()->json(['state' => '600' ,'message' => 'busy'], 200); 
                    }
                    $width = 19.5/2;
                    $hieght = 17.6/2;
                    $Xthcnes = 0.4875;
                    $Ythcnes = 0.44;
                    $startPointes = [
                        "X" => [($width+$Xthcnes)*2,
                                ($width+$Xthcnes)*4,
                                ($width+$Xthcnes)*6,
                                $Xthcnes
                                ],
                        "Y" => [$Ythcnes,
                                $Ythcnes,
                                ($hieght+$Ythcnes)*2,
                                ($hieght+$Ythcnes)*4
                                  ]
                    ];
                    $endPointes = [
                        "X" => [$startPointes["X"][0],
                                $startPointes["X"][1],
                                $Xthcnes,
                                ($width+$Xthcnes)*6
                                ],
                        "Y" => [($hieght+$Ythcnes)*6,
                                ($hieght+$Ythcnes)*6,
                                $startPointes["Y"][2],
                                $startPointes["Y"][3]
                                ]
                    ];
$Gcode =
'G21
G90
F1000
M03 S80
G0 X'. -$startPointes["X"][0] .' Y'. $startPointes["Y"][0] . 'F4000
M05 S10
G1 X'. -$endPointes["X"][0] .' Y'. $endPointes["Y"][0] . 'F4000
M03 S80
G0 X'. -$startPointes["X"][1] .' Y'. $startPointes["Y"][1] . 'F4000
M05 S10
G1 X'. -$endPointes["X"][1] .' Y'. $endPointes["Y"][1] . 'F4000
M03 S80
G0 X'. -$startPointes["X"][2] .' Y'. $startPointes["Y"][2] . 'F4000
M05 S10
G1 X'. -$endPointes["X"][2] .' Y'. $endPointes["Y"][2] . 'F4000
M03 S80
G0 X'. -$startPointes["X"][3] .' Y'. $startPointes["Y"][3] . 'F4000
M05 S10
G1 X'. -$endPointes["X"][3] .' Y'. $endPointes["Y"][3] . 'F4000
M03 S80
G1 X0 Y0 F4000';         
                    file_put_contents(public_path("assets/uploades/gcodes/gcode.txt"), $Gcode);
                    $affectedRows = gameturn::where('id','1')
                    ->update([
                        'permit'=> '1',
                        'realTimeInfo' => '1'
                    ]);
                    if($affectedRows = 0) 
                    {
                        return response()->json(['state' => '450' ,'message' => 'affectedRows is zero!!'], 200); 
                    }
                    return response()->json(['state' => '200' ,'message' => 'win state updated!!'], 200);
                    break;
            default: return response()->json(['state' => '400' ,'message' => 'undefine permit!!'], 200); 
                break;
        }
        return response()->json(['state' => '200','messeg' => 'uploaded succesfully'], 200);
    }
}