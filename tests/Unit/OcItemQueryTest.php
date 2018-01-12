<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\App\Util\MercadoPublico;
use App\OcType;

class TestOcItemQuery extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Happy path for storing new OcListQuery.
     *
     * @return void
     */
    public function testStoreOcItemQueryHappyPath()
    {
        $sample = file_get_contents("tests/ocitemquery.json");
        $json = json_decode($sample);

        MercadoPublico::shouldReceive('get')->andReturn($sample);
        $response = $this->followingRedirects()->post('/oc_item_queries',
        	['code'=>'CODE-HP-PATH']);
        $response->assertSuccessful();
        $response->assertSee('CODE-HP-PATH');
        $response->assertSee(strval($json->Listado[0]->Nombre));
        $response->assertSee(OcType::all()->where('code',$json->Listado[0]->Tipo)
        	->first()->name);
        $response->assertSee('success'); // success BS alert with message

    }

    /**
     * Dates with different formats should work.
     *
     * @return void
     */
    public function testDifferentDateFormat()
    {
        $sample = file_get_contents("tests/ocitemquery.json");
        $json = json_decode($sample);
        $json->Listado[0]->Fechas->FechaCreacion = '2017-12-01T15:50:00';
        $sample = json_encode($json);

        MercadoPublico::shouldReceive('get')->andReturn($sample);
        $response = $this->followingRedirects()->post('/oc_item_queries',
        	['code'=>'CODE-HP-PATH']);
        $response->assertSuccessful();
        $response->assertSee('CODE-HP-PATH');
        $response->assertSee(strval($json->Listado[0]->Nombre));
        $response->assertSee(OcType::all()->where('code',$json->Listado[0]->Tipo)
        	->first()->name);
        $response->assertSee('success'); // success BS alert with message

    }

    /**
     * Should accept json with financiamiento=NULL.
     *
     * @return void
     */
    public function testMissingFinancing()
    {
        $sample = file_get_contents("tests/ocitemquery.json");
        $json = json_decode($sample);
        $json->Listado[0]->Financiamiento = NULL;
        $sample = json_encode($json);

        MercadoPublico::shouldReceive('get')->andReturn($sample);
        $response = $this->followingRedirects()->post('/oc_item_queries',
        	['code'=>'CODE-HP-PATH']);
        $response->assertSuccessful();
        $response->assertSee('CODE-HP-PATH');
        $response->assertSee(strval($json->Listado[0]->Nombre));
        $response->assertSee(OcType::all()->where('code',$json->Listado[0]->Tipo)
        	->first()->name);
        $response->assertSee('success'); // success BS alert with message

    }
}
