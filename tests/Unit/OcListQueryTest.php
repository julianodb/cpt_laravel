<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\App\Util\MercadoPublico;
use Datetime;

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
        $response = $this->followingRedirects()->post('/oc_list_queries',['date'=>'01-01-2017']);
        $response->assertSuccessful();
        $response->assertSee('01-01-2017');
        $response->assertSee(strval($json->Cantidad));
        $response->assertSee('success'); // success BS alert with message

    }

    /**
     * OcListQuery from future date should fail.
     *
     * @return void
     */
    public function testStoreOcListQueryFutureDateFails()
    {
        $test_date = new DateTime('tomorrow');
        $test_date = $test_date->format('d-m-Y');

        $sample = file_get_contents("tests/oclistquery.json");
        $json = json_decode($sample);

        MercadoPublico::shouldReceive('get')->andReturn($sample);
        $response = $this->followingRedirects()->post('/oc_list_queries',['date'=> $test_date]);
        $response->assertSuccessful();
        //$response->assertDontSee($test_date);
        $response->assertDontSee(strval($json->Cantidad));
        $response->assertSee('danger'); // danger BS alert with error

    }
}
