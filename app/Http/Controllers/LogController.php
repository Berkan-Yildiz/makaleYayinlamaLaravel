<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $searchText = $request->search_text;
        $userSearchText = $request->user_search_text;
        $model = $request->model;
        $action = $request->action;

        $logs = Log::with([
            'loggable',
            'user'
        ])
            ->orWhere(function ($query) use ($searchText, $model, $action) {
                //model ve action viewdan direk databasedeki halde geldiği için böyle aldık.
                if (!is_null($model))
                {
                    $query->where('loggable_type', $model );
                }
                if (!is_null($action))
                {
                    $query->where('action', $action );
                }
                if (!is_null($searchText))
                {
                    $query->where(function ($q) use ($searchText) {
                        $q->orWhere('data', 'like', '%'.$searchText.'%')
                          ->orWhere('created_at', 'like', '%'.$searchText.'%')
                          ->orWhere('updated_at', 'like', '%'.$searchText.'%');
                    });
                }
            })
            ->whereHas('loggable')
            ->whereHas('user', function ($query) use ($userSearchText) {
                $query->where('name', 'like', '%'.$userSearchText.'%')
                    ->orWhere('email', 'like', '%'.$userSearchText.'%')
                    ->orWhere('username', 'like', '%'.$userSearchText.'%');
            })
            ->orderBy("id", "DESC")
            ->paginate(20);

        $actions = Log::ACTIONS;
        $models = Log::MODELS;

        return view('admin.logs.list', [
            'list' => $logs ,
            'actions' => $actions,
            'models' => $models
        ]);
    }

    public function getLog(Request $request)
    {
        $id = $request->id;
        $dataType = $request->data_type;

        $log = Log::query()->with("loggable")->findOrFail($id);

        $logType = $log->loggable_type;

        $data = json_decode($log->data);
        if ($dataType == "data")
        {
            return response()->json()->setData($data)->setStatusCode(200);
        }
        $data = $log->loggable;
        return view('admin.logs.model-log-view', compact("data", 'logType'));
    }

}
