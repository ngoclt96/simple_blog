<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Controller
{
    var $curUser; // The current login user
    var $curMember; // The current login user
    var $request;
    var $data;

    public function __construct(Request $request)
    {
        header('Access-Control-Allow-Origin: *');

        $this->request = $request;
        $this->curUser = $request->user();
        $this->curMember = $request->curMember;
        Config::set('app.timezone', 'Asia/Ho_Chi_Minh');

        /*$is_login_expire = $this->check_login_expire();
        //dd($is_login_expire);
        if($is_login_expire === false) {
            header("Location: http://honyakushi.dev");
            exit();
        }*/

        $this->customDataTransform();
    }

    /**
     * @SWG\Get(
     *   path="/members/check_login_expire", tags={"member"},
     *   summary="Check member login expire",
     *   description="Check member login expire",
     *   operationId="member", produces={"application/json"},
     *   @SWG\Parameter( name="member_id", in="formData", description="id of member", required=true, type="string" ),
     *   @SWG\Parameter( name="language_id", in="formData", description="language curren of site", required=true, type="string" ),
     *   @SWG\Response( response=200, description="Success" )
     * )
     */
    public function check_login_expire(){
        $result = false ;
        if(!empty($this->curMember)) $result = true;

        //dd($result);

        //return $result;
        $this->output(['data' => $result], 200);
    }

    /**
     *  This function used just to deal with the problem in json format
     */
    private function customDataTransform()
    {
        $data = $this->request->input();
        unset($data['_method']);

        /* Transform null value */
        foreach ($data as $key => $value) {
            if ($value == '') {
                $data[$key] = NULL;
            }
        }

        $this->request->replace($data);
        $this->data = $data;
    }

    /**
     *  The global save function
     */
    public function saveRecord($modelName)
    {
        if (empty($this->data)) {
            $this->output(['message' => trans('Please enter data before send')], 400);
        }

        $this->formConfirm();
        $model = '\App\Models\\' . $modelName;

        if (isset ($this->data['id'])) {
            $model = $model::where(['id' => $this->data['id']])->first();
            DB::transaction(function () use ($model) {
                try {
                    $model->fill($this->data)->save();
                    $this->updateHasMany($model);
                } catch (\PDOException $e) {
                    throw $e;
                }
            });
            $this->output(['message' => trans($modelName . ' has been updated')], 200);
        } else {

            DB::transaction(function () use ($model) {
                try {
                    $model = $model::create($this->data);
                    $this->updateHasMany($model);
                } catch (\PDOException $e) {
                    throw $e;
                }
            });
            $this->output(['message' => trans($modelName . ' has been created')], 200);
        }
    }

    /**
     *  This function is used to auto remove/insert new the hasMany items
     */
    function updateHasMany($model)
    {
        if (isset($model->hasMany)) {
            foreach ($model->hasMany as $strModel) {
                if (method_exists($model, $strModel)) {
                    // Delete old related
                    $model->$strModel()->delete();

                    // Insert new related
                    if (isset($this->data[$strModel])) {
                        $relData = json_decode($this->data[$strModel], true);

                        $foreignKey = $model->$strModel()->getForeignKey();
                        $foreignKey = str_replace($strModel . '.', '', $foreignKey);

                        /* --- Set foreign key to the data { --- */
                        $dataWithID = [];
                        $dataWithOutID = [];
                        foreach ($relData as $item) {
                            if (!isset($item[$foreignKey])) {
                                $item[$foreignKey] = $model->id;
                            }

                            if (empty($item['id'])) {
                                array_push($dataWithOutID, $item);
                            } else {
                                array_push($dataWithID, $item);
                            }
                        }
                        /* --- Set foreign key to the data } --- */

                        $model->$strModel()->insert($dataWithID);
                        $model->$strModel()->insert($dataWithOutID);
                    }
                }
            }
        }
    }

    /**
     *  Save multiple records
     */
    public function saveMany($modelName)
    {
        if (empty($this->data)) {
            $this->output(['message' => trans('Please enter data before send')], 400);
        }

        $this->formConfirm();
        $model = '\App\Models\\' . $modelName;
        if (isset ($this->data['id'])) {
            $record = $model::where(['id' => $this->data['id']])->first();

            if ($record->fill($this->data)->save()) {
                $this->output(['message' => trans($modelName . ' has been updated')], 200);
            } else {
                $this->output(['message' => trans($modelName . ' cannot be updated')], 400);
            }
        } else {
            $array_data = $this->data;
            DB::beginTransaction();
            foreach ($array_data as $data) {
                $model::create($data);
            }
            DB::commit();
            $this->output(['message' => trans($modelName . ' has been saved')], 200);
        }
    }

    /**
     *  The global delete function
     */
    public function deleteRecord($modelName)
    {
        $model = '\App\Models\\' . $modelName;

        $this->validate($this->request, ['id' => 'required']);

        if ($record = $model::where([['id', $this->data['id']]])->first()) {
            DB::transaction(function () use ($record) {
                try {
                    $record->delete();
                } catch (\PDOException $e) {
                    throw $e;
                }
            });
            $this->output(['message' => trans($modelName . ' has been deleted')], 200);
        }
    }

    public function output($data, $statusCode)
    {
        response()->json($data, $statusCode)->send();
        die;
    }

    /**
     *  This function run after the validation and skip the save method
     */
    public function formConfirm()
    {
        if (isset($this->data['form_complete']) && $this->data['form_complete'] != 0) {
            return true;
        }

        if (isset($this->data['form_confirm']) && $this->data['form_confirm'] != 0) {
            response()->json(['confirm' => true], 200)->send();
            die;
        }
        if (isset($this->data['form_confirm']) && $this->data['form_confirm'] == 'undefined') {
            return true;
        }

        if (isset($this->data['confirm']) && $this->data['confirm'] != 0) {
            return true;
        }
        response()->json(['message' => 'Hacking detected!'], 400)->send();
        die;
    }

    public function updateFieldBackend()
    {
        $listUsers = User::pluck('id', 'id')->toArray();
        if (!in_array($this->curUser->id, $listUsers)) $this->output(['message' => trans('Please login as user system.')], 422);
        if (empty($this->data)) $this->output(['message' => trans('Form data can not be left empty.')], 422);
        $modelName = $this->data['model'];
        $model = '\App\Models\\' . $modelName;
        $valueKey = $this->data['valueKey'];
        $colChange = $this->data['column'];
        $valueColChange = $this->data['valueColumnChange'];

        if ($model::where('id', $valueKey)->update([$colChange => $valueColChange])) {
            $this->output(['message' => trans($modelName . ' has been updated ' . $colChange)], 200);
        } else {
            $this->output(['message' => trans($modelName . ' can not be updated ' . $colChange)], 422);
        }
    }


    public function delete_favorite_record($cons , $modelName)
    {
        $model = '\App\Models\\' . $modelName;

        if ($record = $model::where($cons)->first()) {
            DB::transaction(function () use ($record) {
                try {
                    $record->delete();
                } catch (\PDOException $e) {
                    throw $e;
                }
            });
            $this->output(['message' => trans($modelName . ' has been deleted')], 200);
        }
        $this->output(['message' => trans('Please refresh your browser.')], 422);
    }

    public function add_favorite_record($cons, $modelName, $data)
    {
        $model = '\App\Models\\' . $modelName;
        $record = $model::where($cons)->first();

        if (empty($record)) {
            DB::transaction(function () use ($model, $data) {
                try {
                    $model::create($data);
                } catch (\PDOException $e) {
                    throw $e;
                }
            });
            $this->output(['message' => trans($modelName . ' has been created')], 200);
        }
        $this->output(['message' => trans('Please refresh your browser.')], 422);
    }


}