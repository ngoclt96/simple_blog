<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\AppConst\Constants;
class BaseModel extends Model
{
    protected $searchAble = [];
    /**
     * @return array
     * @uses return searchable column and info
     */
    public function getSearchAbleField()
    {
        return $this->searchAble;
    }
    /**
     * @param $searchRemmember
     * @uses get search fields
     */
    public function getSearch($searchRemmember){
        if (session($searchRemmember) && count(session($searchRemmember)) > 1) {
            $searchFields = session($searchRemmember);
            $this->searchAble = array_filter($this->searchAble, function($key) use ($searchFields) {
                return in_array($key, $searchFields);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            $this->searchAble = array_filter($this->searchAble, function($val, $key){
                return $val['default'] == true;
            }, ARRAY_FILTER_USE_BOTH);
        }
    }
    /**
     * @param $query
     * @param $params
     * @return query
     * @uses search result by params
     */
    public function scopeSearch($query, $params)
    {
//
    }
    public function getDateFields()
    {
        return $this->dates;
    }

    public function isAdmin()
    {
        return get_class($this) === User::class;
    }


}