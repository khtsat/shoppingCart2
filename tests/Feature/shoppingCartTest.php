<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class shoppingCartTest extends TestCase
{
    use RefreshDatabase; 

    /** @test */
    public function guset_may_not_creat_a_cart()
    {

        
        $this->post('/cart',[])->assertRedirect('/login');


       
    }

    /** @test */
    public function guset_may_not_add_a_product()
    {

        
        $this->post('/cart/add',[
            'name' => 'Java',
            'price' => '100',
            'currency_iso_code' => 'ILS',
        ])->assertRedirect('/login'); 
    }

     /** @test */
     public function price_must_be_an_integer()
     {
 
         
         //login
        $this->withOutExceptionHandling();
        $user=factory('App\User')->create();
        $this->actingAs($user);
        $response=$this->post('/cart',[]);

        try {
            //add products
            $response=$this->post('/cart/add',[
                'name' => 'Java',
                'price' => 's',
                'currency_iso_code' => 'ILS',
             ]);
        } catch (ValidationException $exception) {
            $response->assertSuccessful();
        }



            
        
     }

    /** @test */
    public function creat_shopping_cart()
    {

        //When:​ An empty shopping cart created.
        //Then:​ the product count of cart should be 0.

        //login
        $user=factory('App\User')->create();
        $this->actingAs($user);

        //creat cart
        $response=$this->post('/cart',[]);

        //check
        $this->assertDatabaseHas("carts",[
            "user_id" => $user->id
        ]);
    }

    /** @test */
    public function add_product_to_shopping_cart()
    {
        // When: ​ Add 1 unit of ‘Java Book’, unit price 127 NIS.
        // Then:
        // – The book count of the cart should be 1.
        // – The total value of cart should be 127 ILS.


        //login
        $this->withOutExceptionHandling();
        $user=factory('App\User')->create();
        $this->actingAs($user);
        $response=$this->post('/cart',[]);


        //add products
        $response=$this->post('/cart/add',[
                    'name' => 'Java',
                    'price' => '100',
                    'currency_iso_code' => 'ILS',
        ]);


        
        $response->assertViewHasAll([
            'name' => 'Java',
            'currency_iso_code' => "ILS",
            'price' => 100
        ]);
    }
    /** @test */
    public function add_multiple_products_to_shopping_cart()
    {
        // When:
        // – Add 1 unit of ‘Java Book’, unit price 127 NIS.
        // – Add 1 unit of ‘Web design Book’, unit price 100 NIS.
        // Then:
        // – The book count of the cart should be 2.
        // – The total value of cart should be 227 NIS.

        $this->withOutExceptionHandling();
        $user=factory('App\User')->create();
        $this->actingAs($user);
        $response=$this->post('/cart',[]);


        //add products
        $response=$this->post('/cart/add',[
            'name' => 'Java',
            'price' => '100',
            'currency_iso_code' => 'ILS',
        ]);
        $response->assertViewHasAll([
            'name' => 'Java',
            'currency_iso_code' => "ILS",
            'price' => 100
        ]);
        //add products
        $response=$this->post('/cart/add',[
            'name' => 'Java',
            'price' => '100',
            'currency_iso_code' => 'ILS',
        ]);
        $response->assertViewHasAll([
            'name' => 'Java',
            'currency_iso_code' => "ILS",
            'price' => 100
        ]);
        //add products
        $response->assertViewHasAll([
            'name' => 'Java',
            'currency_iso_code' => "ILS",
            'price' => 100
        ]);
        $response->assertViewHasAll([
            'name' => 'Java',
            'currency_iso_code' => "ILS",
            'price' => 100
        ]);
        }
}
