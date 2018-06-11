<?php
/**
 * All constant application defined here
 */
namespace App\AppConst;
class Constants
{
    const VIEW_DIR = 'pc';
    const PAGE_RECORD = 5;
    const DATE_FORMAT = 'Y-m-d';
    const APPROVER= 1;
    const NON_APPROVER = 0;
    const APPROVER_STATUS = [
        self::APPROVER => 'approve',
        self::NON_APPROVER => 'none_approve'
    ];

}