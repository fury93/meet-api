<?php

namespace rest\versions\v1\helper;

class ResponseHelper
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'error';

    /**
     * @param $data
     * @return array
     */
    public static function success($data)
    {
        return [
            'status' => self::STATUS_SUCCESS,
            'data' => $data
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public static function failed($data)
    {
        return [
            'status' => self::STATUS_FAILED,
            'errors' => $data
        ];
    }
}