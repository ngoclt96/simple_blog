<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\AppConst\Constants;
class BaseModel extends Model
{
    const VIEW_DIR = 'pc';
    const DATE_FORMAT = 'Y-m-d';

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
     * @param $query
     * @param $params
     * @return query
     * @uses search result by params
     */
    public function scopeSearch($query, $params)
    {
        $tableModel = $query->getModel()->getTable();
        foreach ($this->getSearchAbleField() as $key => $value) {
            if (isset($params[$key])) {
                switch ($value['search']['type']) {
                    case 'text' :
                        $query->where($tableModel . '.' . $key, '=', $params[$key]);
                        break;
                    case 'selectbox' :
                        $query->where($tableModel . '.' . $key, '=', $params[$key]);
                        break;
                    case 'date' :
                        $date = Carbon::createFromFormat(BaseModel::DATE_FORMAT, $params[$key])->format('Y-m-d');
                        if ($date) {
                            $query->whereDate($tableModel . '.' . $key, $date);
                        }
                        break;
                    default:
                        $query->where($tableModel . '.' . $key, 'LIKE', '%' . $params[$key] . '%');
                        break;
                }
            }

        }

        return $query;

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