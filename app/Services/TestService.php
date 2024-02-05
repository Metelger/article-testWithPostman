<?php

namespace App\Services;

use App\Models\Test;
use Illuminate\Database\QueryException;

class TestService
{
    public function inputTestResults($data)
    {

        $collectionName = $data["jsonData"][0]["collectionName"];
        $data = $data["jsonData"];

        $arrayData = [];
        foreach ($data as $data) {

            if ($data["dataTest"]["testResult"] === true) {
                $testResult = [
                    "passed" => true,
                    "description" => "Passou no teste"
                ];
            } else {
                $testResult = [
                    "passed" => false,
                    "description" => "Falhou no teste"
                ];
            }

            $responseBody = $data["dataTest"]["responseBody"];
            $name = $data["dataTest"]["info"];
            $method = $data["dataTest"]["method"];
            $domain = implode(".", $data["dataTest"]["url"]["host"]);
            $endpoint = implode("/", $data["dataTest"]["url"]["path"]);
            $url = $domain . "/" . $endpoint;

            if (!empty($data["dataTest"]["url"]["query"]) && isset($data["dataTest"]["url"]["query"][0]["value"])) {
                $query = urldecode($data["dataTest"]["url"]["query"][0]["value"]);
            } else {
                $query = null;
            }
            $responseTime = $data["dataTest"]["responseTime"] . " ms";
            $sizeResponse = $data["dataTest"]["responseSize"];
            $sizeRequest = $data["dataTest"]["requestSize"];
            $totalSize = ($sizeRequest + $sizeResponse) . " B";

            $arrayData[] = [
                "nameRequest" => $name,
                "testResult" => $testResult,
                "method" => $method,
                "url" => $url,
                "query" => $query,
                "responseTime" => $responseTime,
                "requestSize" => $sizeRequest,
                "responseSize" => $sizeResponse,
                "totalSize" => $totalSize,
                "responseBody"=> $responseBody
            ];
        }

        $jsonData =[
            "jsonData" =>[
                "collectionName" => $collectionName,
                "data" => $arrayData
            ]
        ];
        try {
            $test = json_decode(Test::create($jsonData));
            return response()->json($test, 201);
        } catch (\Exception $e) {
            $error = [
                "error" => true,
                "description" => "Error to input test informations: " . $e->errorInfo[2],
            ];
            return response()->json($error, 500);
        }
    }

    public function getAllTestResults()
    {
        $listAll = json_decode(Test::all());
        if ($listAll != null) {
            $arrayData = [];

            foreach ($listAll as $listItens ) {

                $formatDate = \Carbon\Carbon::parse($listItens->created_at)->subHours(3)->format("d/m/y - H:i");
                $arrayData[] =  [
                    "name" => $listItens->jsonData->collectionName ?? null,
                    "id" => $listItens->id,
                    "creation_date" => $formatDate
                ];
            }
            return response($arrayData);
        }
        $error = [
            "error" => true,
            "description" => "Error to get all tests informations: Any test inserted",
        ];
        return response()->json($error, 500);
    }

    public function getTestById($id)
    {

        $getTest = json_decode(Test::find($id));

        if ($getTest != null) {
            return $getTest;
        }
        $error = [
            "error" => true,
            "description" => "Error to get test informations: No test inserted with this id",
        ];
        return response()->json($error, 500);
    }

}
