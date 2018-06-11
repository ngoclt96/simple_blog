<?php
namespace App\Http\Controllers;
use App\AppConst\Constants;
use Assets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use WindowsAzure\MediaServices\Models\Asset;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
    protected $view;
    protected $limit;
    protected $model;
    protected $controller;
    protected  $action;
    public function __construct()
    {
        if(!is_null(Route::getCurrentRoute())) {
            $route = class_basename(Route::getCurrentRoute()->getActionName());
            list($controller, $action) = explode('@', $route);
            $controller = str_replace('controller', '', strtolower($controller));
            $action = strtolower($action);
            $this->controller = $controller;
            $this->action = $action;
            $this->view = $this->getViewDir() . '.' . $controller . '.' . $action;
            $this->view =  $this->getViewDir() . '.' . $controller . '.' . $action;
            $this->limit = Constants::PAGE_RECORD;
            if(($limit = request('limit')) && intval($limit) > 0 ){
                $this->limit = $limit;
            }
        }
    }
    protected function view($data = null)
    {
        echo view($this->view)->with($data);
    }
    protected function getViewDir()
    {
        return Constants::VIEW_DIR;
    }
    public function index()
    {
        $this->view();
    }
    public function form($id = null)
    {
        return response()->view(Constants::VIEW_DIR . '.errors.404', [], '404');
    }
    public function complete()
    {
        $this->view();
    }

    public function deleteRecord($modelName)
    {
        $model = '\App\Models\\' . $modelName;
        $data  = Input::all();
        $id = $data['id'];
        if($record = $model::where('id',$id )->first()) {
            DB::transaction(function () use ($record) {
                try {
                    $ts = Carbon::now()->toDateTimeString();
                    $data = array('deleted_at' => $ts, 'deleted' => 1);
                    $record->update($data);
                } catch (\PDOException $e) {
                    throw $e;
                }
            });
        }
    }

}