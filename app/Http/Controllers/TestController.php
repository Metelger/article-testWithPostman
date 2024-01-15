<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TestService;

class TestController extends Controller
{
    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function inputTestResults(Request $request)
    {
        $request = $request->json()->all();
        return $this->testService->inputTestResults($request);
    }

    public function getAllTestResults() {
        return $this->testService->getAllTestResults();
    }

    public function getTestById($id) {
        return $this->testService->getTestById($id);
    }

}
