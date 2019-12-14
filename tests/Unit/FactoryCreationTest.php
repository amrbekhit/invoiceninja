<?php

namespace Tests\Unit;

use App\Factory\ClientContactFactory;
use App\Factory\ClientFactory;
use App\Factory\CloneInvoiceFactory;
use App\Factory\InvoiceFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use App\Models\Client;
use App\Models\User;
use App\Utils\Traits\MakesHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;
use Tests\MockAccountData;
use Tests\TestCase;

/**
 * @test
 */
class FactoryCreationTest extends TestCase
{
    use MakesHash;
    use DatabaseTransactions;
    use MockAccountData;

    public function setUp() :void
    {
    
        parent::setUp();
    
        Session::start();

        $this->faker = \Faker\Factory::create();

        Model::reguard();

        $this->makeTestData();
        
    }

    /**
     * @test
     * @covers      App\Factory\ProductFactory
     */
    public function testProductionCreation()
    {
        $product = ProductFactory::create($this->company->id, $this->user->id);
        $product->save();

        $this->assertNotNull($product);

        $this->assertInternalType("int", $product->id);
    }

    /**
     * @test
     * @covers      App\Factory\InvoiceFactory
     */
    
    public function testInvoiceCreation()
    {
        $client = ClientFactory::create($this->company->id, $this->user->id);

        $client->save();

        $invoice = InvoiceFactory::create($this->company->id,$this->user->id);//stub the company and user_id
        $invoice->client_id = $client->id;
        $invoice->save();

        $this->assertNotNull($invoice);

        $this->assertInternalType("int", $invoice->id);
    }

    /**
     * @test
     * @covers App\Factory\CloneInvoiceFactory
     */
    public function testCloneInvoiceCreation()
    {
        $client = ClientFactory::create($this->company->id, $this->user->id);

        $client->save();

        $invoice = InvoiceFactory::create($this->company->id,$this->user->id);//stub the company and user_id
        $invoice->client_id = $client->id;
        $invoice->save();

        $this->assertNotNull($invoice);

        $this->assertInternalType("int", $invoice->id);


        $clone = CloneInvoiceFactory::create($invoice, $this->user->id);
        $clone->save();

        $this->assertNotNull($clone);

        $this->assertInternalType("int", $clone->id);
        

    }

    /**
     * @test
     * @covers App\Factory\ClientFactory
     */
    public function testClientCreate()
    {
        $cliz = ClientFactory::create($this->company->id, $this->user->id);

        $cliz->save();

        $this->assertNotNull($cliz);

        $this->assertInternalType("int", $cliz->id);
    }

    /**
     * @test
     * @covers App\Factory\ClientContactFactory
     */
    public function testClientContactCreate()
    {

        $cliz = ClientFactory::create($this->company->id, $this->user->id);

        $cliz->save();

        $this->assertNotNull($cliz->contacts);
        $this->assertEquals(1, $cliz->contacts->count());
        $this->assertInternalType("int", $cliz->contacts->first()->id);

    }

    /**
     * @test
     * @covers App\Factory\UserFactory
     */
    public function testUserCreate()
    {
        $new_user = UserFactory::create();
        $new_user->email = $this->faker->email;
        $new_user->save();

        $this->assertNotNull($new_user);

        $this->assertInternalType("int", $new_user->id);

    }


}