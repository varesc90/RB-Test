<?php

namespace Tests\Feature;

use App\Service\Util;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UtilTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSuccessResponseBuilder()
    {
        $response = Util::buildResponse("SUCCESS","",[], 200);
        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals($response->getContent(), "{\"code\":\"SUCCESS\",\"message\":\"\",\"data\":[]}");
    }

    public function testFailResponseBuilder()
    {
        $response = Util::buildResponse("FAIL","Invalid",[], 410);
        $this->assertEquals($response->getStatusCode(), 410);
        $this->assertEquals($response->getContent(), "{\"code\":\"FAIL\",\"message\":\"Invalid\",\"data\":[]}");
    }
}
