<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Api\Responses;

use Illuminate\Http\JsonResponse;

interface ApiResponseInterface
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond(): JsonResponse;
}
