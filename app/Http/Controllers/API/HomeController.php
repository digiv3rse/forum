<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Forum\Assignment;
use App\Repositories\Frontend\Forum\NoticeRepository;
use App\Repositories\Frontend\Forum\AssignmentRepository;
use Carbon\Traits\Timestamp;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;

class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/heatmap",
     *     tags={"Utils"},
     *     summary="Get the JSON data of heatmap on index page",
     *     @OA\Parameter(
     *         name="st",
     *         description="Start time (in timestamp format).",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="ed",
     *         description="End time (in timestamp format).",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/Heatmap"),
     *         )
     *     ),
     * )
     */
    public function heatmap(Request $request)
    {
        $userID = (Input::get('userID', 0));
        $st = (Input::get('st', 0));
        $ed = (Input::get('ed', 0));
        $assignments = $this->assignmentRepository
            ->getAssignmentsByTimestamps($userID, $st, $ed);
        $jsonValueArray = array();
        foreach ($assignments as $assignment) {
            $timestamp = date_timestamp_get($assignment->due_time);
            if (isset($jsonValueArray[$timestamp])) {
                $jsonValueArray[$timestamp]++;
            } else {
                $jsonValueArray[$timestamp] = 1;
            }
        }
        return response()->json($jsonValueArray);
    }

    /**
     * @OA\Post(
     *     path="/api/app",
     *     tags={"Utils"},
     *     summary="Get the JSON data of latest mobile app info",
     *     @OA\Response(response=200, description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/AppInfo"),
     *         )
     *     ),
     * )
     */
    public function app(Request $request)
    {
        return response()->json([
            "data" => mobile_app_version(),
        ], 200);
    }


}

/**
 * @OA\Schema()
 */
class Heatmap {
    /**
     * The number of entries of a timestamp
     * @var integer
     * @OA\Property()
     */
    public $timestamp;
}

/**
 * @OA\Schema()
 */
class AppInfo {
    /**
     * App data
     * @var AppInfo_Data
     * @OA\Property()
     */
    public $data;
}

/**
 * @OA\Schema()
 */
class AppInfo_Data {
    /**
     * Version number
     * @var integer
     * @OA\Property()
     */
    public $number;
    /**
     * Version name
     * @var string
     * @OA\Property()
     */
    public $name;
    /**
     * Version info
     * @var string
     * @OA\Property()
     */
    public $info;
    /**
     * App download link
     * @var string
     * @OA\Property()
     */
    public $link;
}

/**
 * @OA\Schema()
 */
class LoginSuccess {
    /**
     * "success"
     * @var string
     * @OA\Property()
     */
    public $status;
    /**
     * API Message
     * @var string
     * @OA\Property()
     */
    public $message;
    /**
     * Student ID
     * @var integer
     * @OA\Property()
     */
    public $student_id;
    /**
     * User's Name
     * @var string
     * @OA\Property()
     */
    public $full_name;
    /**
     * API Token
     * @var string
     * @OA\Property()
     */
    public $token;
}

/**
 * @OA\Schema()
 */
class NormalMessage {
    /**
     * API Returning Status
     * @var string
     * @OA\Property()
     */
    public $status;
    /**
     * API Message
     * @var string
     * @OA\Property()
     */
    public $message;
}