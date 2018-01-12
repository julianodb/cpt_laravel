<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\App\Util\MercadoPublico;
use App\OcListQuery;
use DateTime;

class OcListQueryTest extends TestCase
{
    
    use RefreshDatabase;
    
    /**
     * Time spent storing large OcListQuery.
     *
     * @return void
     */
    public function testStoreOcListLargeResponse()
    {
        $sample = file_get_contents("tests/oclistquery_large.json");
        $json = json_decode($sample);

        MercadoPublico::shouldReceive('get')->andReturn($sample);

        //$time_start = microtime(true);
        $response = $this->followingRedirects()->post('/oc_list_queries',['date'=>'01-01-2017']);
        //$time_end = microtime(true);

        $response->assertSuccessful();
        $response->assertSee('01-01-2017');
        $response->assertSee(strval($json->Cantidad));
        //$this->assertLessThan(15, $time_end-$time_start);
        $this->assertEquals('01-01-2017',OcListQuery::first()->date->format('d-m-Y'));
        $this->assertEquals(13801,
            OcListQuery::withCount('orden_compras')->first()->orden_compras_count);
    }
}
