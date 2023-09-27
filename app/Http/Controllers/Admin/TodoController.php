<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/19/19
 * Time: 3:13 AM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerCollection;
use PDO;
use DB;


class TodoController extends Controller
{

    //Create Customer
    public function createTask(Request $request)
    {
        $todo = new Todo();
        $todo->name = $request->input('name');
        $todo->task_type_id = $request->input('task_type_id');
        $todo->description = $request->input('description');
        $todo->user_id = $request->input('user_id');
        $todo->save();
        return response()->json(['success' => 'Task created successfully', 'id' => $todo->id], 201);
    }

    public function getTasks($user_id,$company_page,$personal_page)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_tasks(?,?,?)');
        $stmt->execute(array($user_id,$company_page,$personal_page));

        $task_types = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $todo_tasks = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $company_count = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();
        $company_count=$company_count[0]->company_count;

        $personal_count = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();
        $personal_count=$personal_count[0]->personal_todo_count;


        return response()->json(['task_types' => $task_types, 'todo_tasks' => $todo_tasks,'company_todo_count'=>$company_count,'personal_todo_count'=>$personal_count ], 200);
    }


    //Delete Project
    public function deleteTodoTask($id)
    {
        $todo_task = Todo::find($id);
        $todo_task->delete();
        if ($todo_task->task_type_id == 86) {
            return response()->json(['success' => 'Personal To Do deleted successfully'], 201);
        } else {
            return response()->json(['success' => 'Company To Do deleted successfully'], 201);
        }

    }

    public function completeTask($id, Request $request)
    {
        $todo_task = Todo::find($request->input('id'));
        $todo_task->is_completed = $request->input('is_completed');
        $todo_task->save();
        return response()->json(['success' => 'Task completed successfully'], 201);
    }

}
