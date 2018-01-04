<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Facades\App\Util\MercadoPublico;

class TestOcListQuery extends TestCase
{
    use RefreshDatabase;

    public function setUp() {
        parent::setup();
        $this->sample = file_get_contents("tests/oclistquery.json");
        //$json = json_decode($this->sample);
    }
    
    /**
     * Happy path for storing new OcListQuery.
     *
     * @return void
     */
    public function testStoreOcListQuery()
    {
        MercadoPublico::shouldReceive('get')->andReturn($this->sample);
        $response = $this->post('/oc_list_queries',['date'=>'01/01/2017']);
        $response->assertStatus(302);
        $response->assertRedirect('oc_list_queries');

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTrue()
    {
        $this->assertTrue(true);
    }
}
