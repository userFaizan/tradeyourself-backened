<?php

namespace App\Http\Controllers;

use App\Models\Days;
use App\Models\Goal;
use App\Models\GoalImages;
use App\Models\Habit;
use App\Models\HabitCategory;
use App\Models\Calculations;
use App\Models\User;
use App\Models\Priority;
use App\Models\Todo;
use App\Models\HabitLog;
use App\Models\TodoLog;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiController extends Controller
{

    public function store_Registration(Request $request)

    {
        $rules = array("email" => "required");
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return   $validator->errors();
        } else {
            // $data = new User;
            // $data->email = $request->Input(['email']);
            // $data->save();
            $data = User::updateOrCreate(
                ['email' =>  request('email')],
                // ['name' => request('name')]
            );
            if ($data) {
                return response()->json([
                    'user'=>$data,
                    'message' => 'User Logged In Successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'User did not Logged In Successfully',
                ]);
            }
        }
    }

    //goal api

    public function store(Request $request)

    {
        $rules = array(
            "name" => "required",
            "goal_image_id" => "required",
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return   $validator->errors();
        } else {
            $user = User::where('email', $request->email)->first();
            $request['user_id'] = $user->id;
            $data = Goal::create($request->except('email'));
            if ($data) {
                return response()->json([
                    
                    'message' => 'Goal added Successfully',
                ]);
            } else {
                return response()->json([
                    'message' => 'Goal did not added',
                ]);
            }
        }
    }

    public function show(Request $request)
    {
   
        $user = User::where('email', $request->email)->first();
        // $goal =[];
        $data =  Goal::with('goal_images')->withCount('todoUnCompleted', 'habitUnCompleted','habitPending','todoPending','habitCompletedhigh','habitCompletedlow','habitCompletedmedium','badhabitCompletedhigh','badhabitCompletedmedium','badhabitCompletedlow','TodoCompletedhigh','TodoCompletedmedium','TodoCompletedlow')->where('user_id', $user->id)->orderBy('id', 'DESC')->get();
    //   dd($data);
        $goal = [];
            $good_habithigh_count=3*3*(1.0+0.00)+0.05;
            $good_habitmedium_count=3*2*(1.0+0.00)+0.05;
            $good_habitlow_count=3*1*(1.0+0.00)+0.05;
            $good_habits_completed_sum=0;
            $bad_habithigh_count=2*3*(1.0+0.00)+0.05;
            $bad_habitmedium_count=2*2*(1.0+0.00)+0.05;
            $bad_habitlow_count=2*1*(1.0+0.00)+0.05;
            $deduction = 6.6;
            $not_done_habit=0;
            $open = 0;
            $TodoHigh= 1.5;
            $TodoMiddle= 1;
            $TodoLow= 0.5;
            $i = 0;
            $TotalPendingsum = 0;
        foreach ($data as $b) {
            $high_todo =  $TodoHigh * $b->todo_completedhigh_count;
            $middle_todo = $TodoMiddle * $b->todo_completedmedium_count;
            $low_todo= $TodoLow * $b->todo_completedlow_count;
            $total_todo =   $high_todo + $middle_todo + $low_todo;
            $good_habit=$good_habithigh_count *   $b->habit_completedhigh_count;
            $good_habit2=$good_habitmedium_count *   $b->habit_completedmedium_count;
            $good_habit3= $good_habitlow_count *   $b->habit_completedlow_count;
            $good_habit4= $good_habit + $good_habit2 + $good_habit3;
            $bad_habit= $bad_habithigh_count *   $b->badhabit_completedhigh_count;
            $bad_habit2=$bad_habitmedium_count *   $b->badhabit_completedmedium_count;
            $bad_habit3= $bad_habitlow_count *   $b->badhabit_completedlow_count;
            $bad_habit4= $bad_habit + $bad_habit2 + $bad_habit3;
            $not_done_habit =  $deduction * $b->habit_un_completed_count;
            $low =  $deduction * $b->habit_un_completed_count;
            $high = $good_habit4 + $bad_habit4 + $total_todo;
            $close = $good_habit4 + $bad_habit4 + $total_todo - $not_done_habit;
             $goal[$i]['id'] = $b->id;
             $goal[$i]['user_id'] = $b->user_id;
             $goal[$i]['name'] = $b->name;
             $goal[$i]['description'] = $b->description;
             $goal[$i]['goal_image_id'] = $b->goal_image_id;
             $goal[$i]['goal_images'] = $b->goal_images;

            // $data[$i]['name'] = $b->name;
            // $goal[$i]['uncompleted_total_sum'] = $b->habit_un_completed_count + $b->todo_un_completed_count;
            $goal[$i]['pending_total_sum'] = $b->habit_pending_count + $b->todo_pending_count;
            $data[$i]['todo_un_completed_count'] = $b->todo_un_completed_count;
            $TotalPendingsum +=  $b->habit_pending_count + $b->todo_pending_count;
               $goal[$i]['date'] = Carbon::now()->isoFormat('YYYY-MM-DD') . " 12:00:00";
               $goal[$i]['high'] = $high;
               $goal[$i]['low'] = $low;
               $goal[$i]['open'] =   $open ;
               $goal[$i]['close'] = $close;
               $goal[$i]['Totalpoints'] = $good_habit4 + $bad_habit4 + $total_todo  - $not_done_habit ;
               
            $i++;
        }
        $arr = [];
        $i = 0 ;
        foreach($goal as $cal)
        {
            $today = date('Y-m-d');
            $check= Calculations::where(['goal_id'=> $cal['id']])->where('created_at', 'LIKE', '%'.$today.'%')->first();  
            $previous = Carbon::yesterday()->isoFormat('YYYY-MM-DD');
            $closesetting = Calculations::where(['goal_id'=> $cal['id']])->where('created_at', 'LIKE', '%'.$previous.'%')->first();  
             if($check == Null)
                   {

                    if($closesetting != Null)
                    {
                      $open = $closesetting['Close'];
                    }
            $container = Calculations::create([
                            'user_id' =>$cal['user_id'],
                            'goal_id' =>$cal['id'],
                            'High' =>$cal['high'],
                            'Low' => $cal['low'],
                            'Open' => $open ,
                            'Close' => $cal['close'],
                            'Totalpoints' => $cal['Totalpoints'],
                            'Date' => $cal['date']
                          ]);
                        }else{
                            if($closesetting != Null)
                            {
                              
                              $open = $closesetting['Close'];
                            }
                            $container = Calculations::where(['goal_id'=> $cal['id']])->where('created_at', 'LIKE', '%'.$today.'%')->update([
                                'user_id' =>$cal['user_id'],
                                'goal_id' =>$cal['id'],
                                'High' =>$cal['high'],
                                'Low' => $cal['low'],
                                'Open' => $open ,
                              'Close' => $cal['close'],
                                'Totalpoints' => $cal['Totalpoints'],
                                'Date' => $cal['date']
                              ]);
                        }
        $i++;
        }
     
        if ($goal) {
            return $response = compact('goal','TotalPendingsum');
            // return $response = $goal;
            return response($response, 200)->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'message' => 'No Goal found',
            ]);
        }
    }
    public function update(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $request['user_id'] = $user->id;
        $data = Goal::where('id', $request->id)->first();
        if ($data != "" || $data != NULL) {
            if ($request->name != "" || $request->name != NULL) {
                $data->update(['name' => $request->name]);
            }
            if ($request->description != "" || $request->description != NULL) {
                $data->update(['description' => $request->description]);
            }
            if ($request->goal_image_id != "" || $request->goal_image_id != NULL) {
                $data->update(['goal_image_id' => $request->goal_image_id]);
            }
            return response()->json([
                'message' => 'Goal update Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No record found to update against given id ',
            ]);
        }
    }


    public function destroy($id)
    {
        $data = Goal::find($id);
        $data->habit()->delete();
        $data->todo()->delete();
        $data->delete();

        if ($data) {
            return response()->json([
                'message' => 'Goal deleted successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No record found to update against given id ',
            ]);
        }
    }

    //goal_image apis
    public function store_image(Request $request)

    {
        $this->validate($request, [
            'image'        =>  'required|image|mimes:jpeg,png,jpg,gif'
        ]);
        $data = new GoalImages();
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/goals/', $filename);
            $data->image = $filename;
        }
        $data->save();

        if ($data) {
            return response()->json([
                'message' => 'Image added Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Image did not added',
            ]);
        }
    }


    //goal_images api
    public function show_image(Request $request)
    {

        $data = GoalImages::all();
        if ($data) {
            return $response['data'] = $data;
            return response($response, 200)->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'message' => 'No Goal_image found',
                'code' => 201
            ]);
        }
    }
    //habit api

    public function store_habit(Request $request)
    {
        $rules = array(
            "name" => "required",
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return   $validator->errors();
        } else {
        $user = User::where('email', $request->email)->first();
        $request['user_id'] = $user->id;
        $data = Habit::create($request->except(['day_ids','email']));
        $data->days()->sync($request->day_ids);
        if ($data) {
            return response()->json([
                'message' => 'Habit added Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Habit did not added',
            ]);
        }
    }
    }

    public function show_habit(Request $request)
    {
        if ($request->goal_id != '') {
        
            $value = Carbon::today()->format('l');
            // $data = Habit::with(['days' => function ($query) {
            //     $query->get(['days']);
            // }])->get();

            // dd($data);
            $habit = Habit::with('goal', 'priority', 'habit_category', 'days')->where('goal_id', $request->goal_id)->get();
        } else {
            $user = User::where('email', $request->email)->first();
            $habit = Habit::with('goal', 'priority', 'habit_category', 'days')->where('user_id', $user->id)->get();
        }
        if ($habit) {
            return $response = $habit;
            return response($response, 200)->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'message' => 'No Habit found',
            ]);
        }
    }

    public function update_habits(Request $request)
    {
        $update_habit = Habit::where('id', $request->id)->first();
        $update_habit->days()->sync($request->day_ids);
        if ($update_habit != "" || $update_habit != NULL) {
            if ($request->name != "" || $request->name != NULL) {
                $update_habit->update(['name' => $request->name]);
            }
            if ($request->reminder_time != "" || $request->reminder_time != NULL) {
                $update_habit->update(['reminder_time' => $request->reminder_time]);
            }
            if ($request->category_id != "" || $request->category_id != NULL) {
                $update_habit->update(['category_id' => $request->category_id]);
            }
            if ($request->goal_id != "" || $request->goal_id != NULL) {
                $update_habit->update(['goal_id' => $request->goal_id]);
            }
            if ($request->priority_id != "" || $request->priority_id != NULL) {
                $update_habit->update(['priority_id' => $request->priority_id]);
            }
            return response()->json([
                'message' => 'Habit update Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No record found to update against given id ',
            ]);
        }
    }

    public function destroy_habit($id)
    {
        $data = Habit::find($id)->delete();
        if ($data) {
            return response()->json([
                'message' => 'Habit deleted successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No Habit found against this id',
            ]);
        }
    }
    //show all days
    public function show_days()
    {
        $days = Days::all();
        if ($days) {
            return $response['data'] = $days;
            return response($response, 201)->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'message' => 'No Days found',
            ]);
        }
    }

    //show all_priority
    public function all_priority()
    {
        $priority = Priority::all();
        if ($priority) {
            return $response['data'] = $priority;
            return response($response, 201)->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'message' => 'No Priority found',
            ]);
        }
    }
    //all_habit_category
    public function all_habit_category()
    {
        $habitcategory = HabitCategory::all();
        if ($habitcategory) {
            return $response['data'] = $habitcategory;
            return response($response, 201)->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'message' => 'No HabitCategory found',
            ]);
        }
    }
    public function store_to_do(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $request['user_id'] = $user->id;
        $todo  = Todo::create($request->except('email'));
        if ($todo) {
            return response()->json([
                'message' => 'Todo added Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Todo did not added',
            ]);
        }
    }

    public function show_todo(Request $request)
    {
        if ($request->goal_id != '') {
            $todo = Todo::with('goal', 'priority')->where('goal_id', $request->goal_id)->get();
        } else {
            $user = User::where('email', $request->email)->first();
            $todo = Todo::with('goal', 'priority')->where('user_id', $user->id)->get();
        }
        if ($todo) {
            return $response = $todo;
            return response($response, 200)->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'message' => 'No Todo found',
            ]);
        }
    }
    public function update_todo(Request $request)
    {

        $todo = Todo::where('id', $request->id)->first();
        if ($todo != "" || $todo != NULL) {
            if ($request->goal_id != "" || $request->goal_id != NULL) {
                $todo->update(['goal_id' => $request->goal_id]);
            }
            if ($request->priority_id != "" || $request->priority_id != NULL) {
                $todo->update(['priority_id' => $request->priority_id]);
            }
            if ($request->name != "" || $request->name != NULL) {
                $todo->update(['name' => $request->name]);
            }
            if ($request->date != "" || $request->date != NULL) {
                $todo->update(['date' => $request->date]);
            }
            if ($request->time != "" || $request->time != NULL) {
                $todo->update(['time' => $request->time]);
            }
            if ($request->reminder_time != "" || $request->reminder_time != NULL) {
                $todo->update(['reminder_time' => $request->reminder_time]);
            }
            return response()->json([
                'message' => 'Todo update Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No record found to update against given id ',
            ]);
        }
    }

    public function destroy_todo($id)
    {
        $todo = Todo::where('id', $id)->delete();
        if ($todo) {
            return response()->json([
                'message' => 'Todo deleted successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No Todo found against this id',
            ]);
        }
    }
    public function update_habits_status(Request $request)
    {
        $habit = Habit::where('id', $request->id)->first();
   
        if ($habit != "" || $habit != NULL) {
            if ($request->status != "" || $request->status != NULL) {
                $habit->update(['status' => $request->status]);
            }
            if ($request->goal_id != "" || $request->goal_id != NULL) {
                $habit->update(['goal_id' => $request->goal_id]);
            }
            if ($request->user_id != "" || $request->user_id != NULL) {
                $habit->update(['user_id' => $request->user_id]);
            }
            $today = date('Y-m-d');
            $check= HabitLog::where('created_at', 'LIKE', '%'.$today.'%')->where('habit_id',$request->id )->get(); 
         
             if(count($check)==0)
                   {
                 
                        $habitLog= HabitLog::create(
                            [
                            'user_id'  => $habit->user_id,
                            'goal_id'  =>  $habit->goal_id,
                            'habit_id' =>  $habit->id,
                            'status'   =>  $habit->status,
                        ]);
                   } else{
                    $habitLog= HabitLog::query()
                    ->update(
                        [
                            'goal_id' =>  $habit->goal_id,
                            'habit_id' =>  $habit->id,
                            'status' =>  $habit->status
                    ],
                   
                    );
                   }          
            return response()->json([
                'message' => 'Habit Status update Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No record found to update against given id ',
            ]);
        }
       
    }
    public function update_todo_status(Request $request)
    { 
         $todo = Todo::where('id', $request->id)->first();
        if ($todo != "" || $todo != NULL) {
            if ($request->status != "" || $request->status != NULL) {
                $todo->update(['status' => $request->status]);
            }
            if ($request->goal_id != "" || $request->goal_id != NULL) {
                $todo->update(['goal_id' => $request->goal_id]);
            }
            if ($request->user_id != "" || $request->user_id != NULL) {
                $todo->update(['user_id' => $request->user_id]);
            }
          
            $today = date('Y-m-d');
            $check= TodoLog::where('created_at', 'LIKE', '%'.$today.'%')->where('todo_id',$request->id )->get(); 
         
             if(count($check)==0)
                   {
                 
                        $TodoLog= TodoLog::create(
                            [
                            'user_id'  => $todo->user_id,
                            'goal_id'  =>  $todo->goal_id,
                            'todo_id' =>  $todo->id,
                            'status'   =>  $todo->status,
                        ]);
                   } else{
                    $TodoLog= TodoLog::query()
                    ->update(
                        [
                            'goal_id' =>  $todo->goal_id,
                            'todo_id' =>  $todo->id,
                            'status' =>  $todo->status
                    ],
                   
                    );
                   }          
            return response()->json([
                'message' => 'Todo Status update Successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No record found to update against given id ',
            ]);
        }
    }

    
    public function get_calculations(Request $request)
    {
        if ($request->goal_id != '') {
            $result = Calculations::where('goal_id', $request->goal_id)->get();
            $total = Calculations::where('goal_id', $request->goal_id)->sum('Totalpoints');
            $totalsum = round($total);
        } else {
            $user = User::where('email', $request->email)->first();
            $total = Calculations::where('user_id', $user->id)->sum('Totalpoints');
            $totalsum = round($total);
            $calculations = Calculations::where('user_id', $user->id)->select('Date')->distinct()->get();
            $result = [];
            $i=0;
            foreach($calculations as $data)
            {
                $result1 =  DB::table("calculations")
                ->select(DB::raw("SUM(High) as High ,SUM(Low) as Low ,SUM(Open) as Open ,SUM(Close) as Close ,SUM(Totalpoints) as Totalpoints"))
                ->where("Date",'=', $data->Date)
                ->first();
                $result[$i]['Date'] =$data->Date;
                $result[$i]['High'] =$result1->High * 100/ 100;
                $result[$i]['Low'] = $result1->Low * 100/ 100;
                $result[$i]['Open'] = $result1->Open * 100/ 100;
                $result[$i]['Close'] = $result1->Close * 100/ 100;
                // $result[$i]['Totalpoints'] = round($total);
                $i++;
            }
          
        }
        if ($result) {
 

            return $response =compact(['result','totalsum']);
            // return $response = $total;

            return response($response, 200)->header('Content-Type', 'application/json');
        } else {
            return response()->json([
                'message' => 'No Calculations found',
            ]);
        }
    }

    public function get_count(Request $request)
    {
        
if($request->count == 'Today'){
    $user = User::where('email', $request->email)->first();
    $goal = Goal::where('user_id', $user->id)->whereDay('created_at', '=', date('d'))->count();
    $habit = Habit::where('user_id', $user->id)->whereDay('created_at', '=', date('d'))->count();
    $habitlog = HabitLog::where('user_id', $user->id)->where('status', 1)->whereDay('created_at', '=', date('d'))->count();
    $todo = Todo::where('user_id', $user->id)->whereDay('created_at', '=', date('d'))->count();
    $todolog = TodoLog::where('user_id', $user->id)->where('status', 1)->whereDay('created_at', '=', date('d'))->count();
}elseif($request->count == 'Week')
{
    $user = User::where('email', $request->email)->first();
    $goal=Goal::where('user_id', $user->id)
    ->where('created_at', '>', Carbon::now()->startOfWeek())
    ->where('created_at', '<', Carbon::now()->endOfWeek())->count();
    $habit=Habit::where('user_id', $user->id)
    ->where('created_at', '>', Carbon::now()->startOfWeek())
    ->where('created_at', '<', Carbon::now()->endOfWeek())->count();
    $habitlog=HabitLog::where('user_id', $user->id)
    ->where('status', 1)
    ->where('created_at', '>', Carbon::now()->startOfWeek())
    ->where('created_at', '<', Carbon::now()->endOfWeek())->count();
    $todo=Todo::where('user_id', $user->id)
    ->where('created_at', '>', Carbon::now()->startOfWeek())
    ->where('created_at', '<', Carbon::now()->endOfWeek())->count();
    $todolog=TodoLog::where('user_id', $user->id)
    ->where('status', 1)
    ->where('created_at', '>', Carbon::now()->startOfWeek())
    ->where('created_at', '<', Carbon::now()->endOfWeek())->count();
}elseif($request->count == 'Month')
{
    $user = User::where('email', $request->email)->first();
    $goal = Goal::where('user_id', $user->id)->whereMonth('created_at', '=', date('m'))->count();
    $habit = Habit::where('user_id', $user->id)->whereMonth('created_at', '=', date('m'))->count();
    $habitlog = HabitLog::where('user_id', $user->id)->where('status', 1)->whereMonth('created_at', '=', date('m'))->count();
    $todo = Todo::where('user_id', $user->id)->whereMonth('created_at', '=', date('m'))->count();
    $todolog = TodoLog::where('user_id', $user->id)->where('status', 1)->whereMonth('created_at', '=', date('m'))->count();

}elseif($request->count == 'Year')
{
    $user = User::where('email', $request->email)->first();
    $goal = Goal::where('user_id', $user->id)->whereYear('created_at', '=', date('Y'))->count();
    $habit = Habit::where('user_id', $user->id)->whereYear('created_at', '=', date('Y'))->count();
    $habitlog = HabitLog::where('user_id', $user->id)->where('status', 1)->whereYear('created_at', '=', date('Y'))->count();
    $todo = Todo::where('user_id', $user->id)->whereYear('created_at', '=', date('Y'))->count();
    $todolog = TodoLog::where('user_id', $user->id)->where('status', 1)->whereYear('created_at', '=', date('Y'))->count();
}
if ($goal && $habit &&  $habitlog && $todo  &&  $todolog ) {
    return response()->json([
        'TotalGoalCount' => $goal,
        'TotalHabitCount' => $habit,
        'CompleteHabitCount' => $habitlog,
        'TotalTodoCount' => $todo,
        'CompleteTodoCount' => $todolog,
]);
    return response($response, 200)->header('Content-Type', 'application/json');
} else {
    return response()->json([
        'TotalGoalCount' => $goal,
        'TotalHabitCount' => $habit,
        'CompleteHabitCount' => $habitlog,
        'TotalTodoCount' => $todo,
        'CompleteTodoCount' => $todolog,
       
    ]);
}
    }


}
