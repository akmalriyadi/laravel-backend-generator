<?php

namespace AkmalRiyadi\LaravelBackendGenerator\Traits;

use App\Http\Resources\Paginate;
use Illuminate\Validation\Rules\Exists;

trait ApiCollectionResource
{
    public static function apiCollection($resource)
    {
        $result = self::collection($resource['data']['data']);
        return response()->json([
            'success' => $resource['success'],
            'code' => $resource['code'],
            "data" => $result
        ], $resource['code']);
    }

    public static function paginateCollection($resource)
    {
        $result = self::collection($resource['data']['data']);
        $data = [
            'success' => $resource['success'],
            'code' => $resource['code'],
            'paginate' => $resource['data']['paginate'],

            "data" => $result
        ];
        if (isset($resource['countData'])) {
            $data['countData'] = $resource['countData'];
        }
        return response()->json($data, $resource['code']);
    }

    public static function otherCollection($resource)
    {
        $result = self::make($resource['data']['data']);
        return response()->json([
            'success' => $resource['success'],
            'code' => $resource['code'],
            'message' => $resource['message'],
            "data" => $result
        ], $resource['code']);
    }

    public static function notCollection($resource)
    {
        $result = $resource['data'];
        return response()->json([
            'success' => $resource['success'],
            'code' => $resource['code'],
            'message' => $resource['message'],
            "data" => $result
        ], $resource['code']);
    }
}
