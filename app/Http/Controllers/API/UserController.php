<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Forum\NoticeRepository;
use App\Repositories\Frontend\Forum\AssignmentRepository;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var AssignmentRepository
     * @var NoticeRepository
     */
    protected $assignmentRepository, $noticeRepository;

    /**
     * UserController constructor.
     *
     * @param AssignmentRepository $assignmentRepository
     * @param NoticeRepository $noticeRepository
     */
    public function __construct(AssignmentRepository $assignmentRepository,
                                NoticeRepository $noticeRepository)
    {
        $this->assignmentRepository = $assignmentRepository;
        $this->noticeRepository = $noticeRepository;
    }

    /**
     * API for login.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt([
            'student_id' => $request->student_id,
            'password' => $request->password
        ])){
            $user = Auth::user();
            $token = $user->createToken('Passport API')->accessToken;
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged in as ' . $user->full_name . '.',
                'user_id'   => $user->student_id,
                'user_name' => $user->full_name,
                'token'     => $token,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized Access',
            ], 401);
        }
    }

    /**
     * API for logout.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->token()->revoke();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out as ' . $user->full_name . '.',
        ], 200);
    }

    /**
     * Fetch the current notice.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotice(Request $request)
    {
        $notice = $this->noticeRepository->APIGetNotice();
        return response()->json([
            "data" => $notice,
        ], 200);
    }

    /**
     * Fetch all ongoing assignments.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssignments(Request $request)
    {
        $assignments = $this->assignmentRepository->APIGetOngoingAssignments();
        return response()->json([
            "data" => $assignments,
        ], 200);
    }
}