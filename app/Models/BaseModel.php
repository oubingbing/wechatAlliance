<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/11/13
 * Time: 下午2:56
 */

namespace App\Models;


use App\Exceptions\ApiException;
use Carbon\Carbon;
use Illuminate\Contracts\Database\ModelIdentifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes;

    /** field created_at */
    const FIELD_CREATED_AT = 'created_at';

    /** field updated_at */
    const FIELD_UPDATED_AT = 'updated_at';

    /** field deleted_at */
    const FIELD_DELETED_AT = 'deleted_at';

    /**
     * 根据主键批量更新数据
     *
     * @author yezi
     *
     * @param array $multipleData
     * @return bool
     * @throws ApiException
     */
    public static function updateBatch($model,$multipleData = array())
    {
        $tableName = \DB::getTablePrefix() . app($model)->getTable();

        if(!is_array($multipleData)){
            throw new ApiException('必须是数组',500);
        }

        foreach ($multipleData as &$row){
            if(!array_key_exists('id',$row)){
                throw new ApiException('参数错误,缺少主键',500);
            }
            $row[self::FIELD_UPDATED_AT] = Carbon::now();
        }

        if ($tableName && !empty($multipleData)) {

            $updateColumn    = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0];
            unset($updateColumn[0]);
            $whereIn = "";

            $q = "UPDATE " . $tableName . " SET ";
            foreach ($updateColumn as $uColumn) {
                $q .= $uColumn . " = CASE ";

                foreach ($multipleData as $data) {
                    $q .= "WHEN " . $referenceColumn . " = " . $data[$referenceColumn] . " THEN '" . $data[$uColumn] . "' ";
                }
                $q .= "ELSE " . $uColumn . " END, ";
            }
            foreach ($multipleData as $data) {
                $whereIn .= "'" . $data[$referenceColumn] . "', ";
            }

            $q = rtrim($q, ", ") . " WHERE " . $referenceColumn . " IN (" . rtrim($whereIn, ', ') . ")";

            return \DB::update(\DB::raw($q));
        } else {
            return false;
        }

    }
}