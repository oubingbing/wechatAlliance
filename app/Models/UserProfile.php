<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/22 0022
 * Time: 15:37
 */

namespace App\Models;


class UserProfile extends BaseModel
{
    const TABLE_NAME = 'user_profiles';
    protected $table = self::TABLE_NAME;

    /** field id 用户Id */
    const FIELD_ID = 'id';

    /** Field user_id 用户ID */
    const FIELD_ID_USER = 'user_id';

    /** Field nickname 微信昵称 */
    const FIELD_NICKNAME = 'nickname';

    /** Field avatar 微信昵称 */
    const FIELD_AVATAR = 'avatar';

    /** Field name 用户真实姓名 */
    const FIELD_NAME = 'name';

    /** Field student_number 学号 */
    const FIELD_STUDENT_NUMBER = 'student_number';

    /** Field grade 年级 */
    const FIELD_GRADE = 'grade';

    /** Field major 年级 */
    const FIELD_MAJOR = 'major';

    /** Field college 学院 */
    const FIELD_COLLEGE = 'college';

    protected $fillable = [
        self::FIELD_ID,
        self::FIELD_ID_USER,
        self::FIELD_NICKNAME,
        self::FIELD_AVATAR,
        self::FIELD_NAME,
        self::FIELD_GRADE,
        self::FIELD_STUDENT_NUMBER,
        self::FIELD_MAJOR,
        self::FIELD_COLLEGE
    ];

}