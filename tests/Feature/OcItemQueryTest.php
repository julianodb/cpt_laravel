<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\App\Util\MercadoPublico;
use App\OcType;
use App\OcItemQuery;

class OcItemQueryTest extends TestCase
{
    
    use RefreshDatabase;
    
    /**
     * Insert many OcItemQuery.
     *
     * @return void
     */
    public function testManyInsertions()
    {
        $sample = file_get_contents("tests/ocitemquery.json");
        $json = json_decode($sample);

        for($i=0;$i<100;$i++) {
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

        $this->assertEquals(100,OcItemQuery::all()->count());
    }
}
