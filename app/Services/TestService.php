<?php

namespace App\Services;

use App\Models\Test;
use Illuminate\Database\QueryException;

    /* 
        Breve explicação dos métodos utilizados:
        implode:
            Usado para concatenar elementos de um array.
            Ele funciona informando o que será adicionado entre cada elemento e qual array será concatenado.
        empty:
            Verifica se uma variável está vazia. Uma variável é considerada vazia se não existir, se seu valor for null, uma string vazia, ou um array vazio.
            No nosso caso, estamos "negando" o método para saber se é diferente de vazio. 
        isset:
            Verifica se uma variável está definida e não é null. Retorna true se a variável estiver definida, false caso contrário.
        urldecode:
            Usado para decodificar uma URL. Ele reverte a substituição de caracteres especiais por códigos percentuais em uma URL para seu formato original.
    */
    
class TestService
{
    public function inputTestResults($data) {

        $collectionName = $data["jsonData"][0]["collectionName"];
        $data = $data["jsonData"];        

        $arrayData = [];
        foreach ($data as $data) {

            if($data["dataTest"]['testResult'] == true) {
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
            $domain = implode('.', $data["dataTest"]["url"]["host"]);
            $endpoint = implode('/', $data["dataTest"]["url"]["path"]);
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

    public function getAllTestResults () {

        $listAll = json_decode(Test::all());
        if($listAll != null) {
            $arrayData = [];
            
            foreach ($listAll as $listItens ) {

                $formatDate = \Carbon\Carbon::parse($listItens->created_at)->subHours(3)->format('d/m/y - H:i');
                $arrayData[] =  [
                    "name" => $listItens->jsonData->collectionName ?? null,
                    "id" => $listItens->id,
                    "creation_date" => $formatDate
                ];
            }
            return response($arrayData);
        } else {
            $error = [
                "error" => true,
                "description" => "Error to get all tests informations: Any test inserted",
            ];
            return response()->json($error, 500);
        }   
    }

    public function getTestById($id) {
        
        $getTest = json_decode(Test::find($id));

        if($getTest != null) {
            return $getTest;
        } else {
            $error = [
                "error" => true,
                "description" => "Error to get test informations: No test inserted with this id",
            ];
            return response()->json($error, 500);
        }
    }

}