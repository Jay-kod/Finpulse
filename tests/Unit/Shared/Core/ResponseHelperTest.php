<?php

namespace Tests\Unit\Shared\Core;

use Tests\TestCase;
use App\Shared\Core\Helpers\ResponseHelper;
use Illuminate\Http\JsonResponse;

class ResponseHelperTest extends TestCase
{
    public function test_success_response_structure(): void
    {
        $response = ResponseHelper::success(['id' => 1], 'Operation successful', 201);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Operation successful', $data['message']);
        $this->assertEquals(['id' => 1], $data['data']);
    }

    public function test_error_response_structure(): void
    {
        $response = ResponseHelper::error('Not Found', 404, ['field' => 'Required']);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertEquals('error', $data['status']);
        $this->assertEquals('Not Found', $data['message']);
        $this->assertEquals(['field' => 'Required'], $data['errors']);
    }
}
