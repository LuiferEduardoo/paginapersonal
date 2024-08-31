<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ImageService
{
    protected $url;

    public function __construct($url = null)
    {
        $this->url = $url ?: $_ENV['IMAGE_SERVICE'];
    }    
    protected function getClient($token)
    {
        $client = new Client([
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);
        return $client;
    }

    public function saveImage($file, $folder, $token)
    {
        $client = $this->getClient($token);
        $filename = $file->getClientOriginalName();

        try {
            $response = $client->post("$this->url/images/create?folder=$folder", [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($file, 'r'),
                        'filename' => $filename,
                    ]
                ]
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode === Response::HTTP_OK) {
                $responseData = json_decode($response->getBody(), true);
                $id = $responseData['database']['id'];
                return $id;
            } else {
                return response()->json(['message' => 'Failed to delete image'], $statusCode);
            }
        } catch (ClientException $e) {
            return response()->json(['message' => 'Failed to save image'], $e->getCode());
        } catch (GuzzleException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteImage($id, $token)
    {
        $client = $this->getClient($token);

        try {
            $response = $client->delete("$this->url/images", [
                'query' => [
                    'id' => $id,
                ],
            ]);
            $statusCode = $response->getStatusCode();

            if ($statusCode === Response::HTTP_OK) {
                return response()->json(['message' => 'Image deleted successfully'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Failed to delete image'], $statusCode);
            }
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            return response()->json(['message' => 'Failed to delete image'], $statusCode);
        } catch (GuzzleException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateImage($id, $file, $token)
    {
        $client = $this->getClient($token);
        $filename = $file->getClientOriginalName();

        try {
            $response = $client->post("$this->url/images/$id", [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($file, 'r'),
                        'filename' => $filename,
                    ],
                    [
                        'name' => '_method',
                        'contents' => 'PATCH',
                    ],
                ],
            ]);
            $statusCode = $response->getStatusCode();
            if ($statusCode === Response::HTTP_OK) {
                return response()->json(['message' => 'Image updated successfully'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Failed to update image'], $statusCode);
            }
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            return response()->json(['message' => 'Failed to update image'], $statusCode);
        } catch (GuzzleException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}