<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\App\Util\MercadoPublico;

class TestOcListQuery extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Happy path for storing new OcListQuery.
     *
     * @return void
     */
    public function testStoreOcListQueryHappyPath()
    {
        $sample = file_get_contents("tests/oclistquery.json");
        $json = json_decode($sample);

        MercadoPublico::shouldReceive('get')->andReturn($sample);
        $response = $this->followingRedirects()->post('/oc_list_queries',['date'=>'01/01/2017']);
        $response->assertSuccessful();
        $response->assertSee('01/01/2017');
        $response->assertSee(strval($json->Cantidad));

    }
}
