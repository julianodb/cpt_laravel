<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\App\Util\MercadoPublico;

class TestOcItemQuery extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Happy path for storing new OcListQuery.
     *
     * @return void
     */
    public function testStoreOcListQueryHappyPath()
    {
        $sample = file_get_contents("tests/ocitemquery.json");
        $json = json_decode($sample);

        MercadoPublico::shouldReceive('get')->andReturn($sample);
        $response = $this->followingRedirects()->post('/oc_item_queries',
        	['code'=>'CODE-HP-PATH']);
        $response->assertSuccessful();
        $response->assertSee('CODE-HP-PATH');
        $response->assertSee(strval($json->Listado[0]->Nombre));
        $response->assertSee('success'); // success BS alert with message

    }
}
