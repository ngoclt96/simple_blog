<?php

namespace App\Http\Controllers;

use App\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class BaseController
{
    const DELETED = 1;
    const NOT_DELETE = 0;

    protected $view;
    protected $model;
    protected $controller;

    public function __construct()
    {
        if (!is_null(Route::current())) {
            $route = class_basename(Route::current()->getActionName());
            list($controller, $action) = explode('@', $route);
            $controller = str_replace('controller', '', strtolower($controller));
            $this->view = $this->getViewDir() . '.' . $controller . '.' . $action;

        }
    }

    public function view($data = null)
    {
        echo view($this->view)->with($data);
    }

    public function getViewDir()
    {
        return BaseModel::VIEW_DIR;
    }

    public function index()
    {
        $this->view();
    }

    public function form()
    {
        return response()->view(BaseModel::VIEW_DIR . '.errors.404', [], '404');
    }

    public function complete()
    {
        $this->view();
    }

    public function deleteRecord($modelName, $id)
    {
        if (empty($modelName) || empty($id)) {
            return array();
        }
        $model = '\App\Models\\' . $modelName;
        if ($record = $model::where('id', $id)->first()) {
            DB::transaction(function () use ($record) {
                try {
                    $ts = Carbon::now()->toDateTimeString();
                    $data = array('deleted_at' => $ts, 'deleted' => self::DELETED);
                    $record->update($data);
                } catch (\PDOException $e) {
                    throw $e;
                }
            });
        }
    }

}