<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $permissions=  [
        
            ['name'=>'add product'],
            ['name'=>'edit product'],
            ['name'=>'delete product'],
            ['name'=>'view product'],

            ['name'=>'add category'],
            ['name'=>'edit category'],
            ['name'=>'delete category'],
            ['name'=>'view category'],
              
            ['name'=>'add order'],
            ['name'=>'edit order'],
            ['name'=>'delete order'],
            ['name'=>'view order'],


            ['name'=>'add shop'],
            ['name'=>'edit shop'],
            ['name'=>'delete shop'],
            ['name'=>'view shop'],


            ['name'=>'add language'],
            ['name'=>'edit language'],
            ['name'=>'delete language'],
            ['name'=>'view language'],

            ['name'=>'add agent'],
            ['name'=>'edit agent'],
            ['name'=>'delete agent'],
            ['name'=>'view agent'],

            ['name'=>'view revenue'],
            ['name'=>'view sale'],
            ['name'=>'view dashboard'],


            
            ['name'=>'add customer'],
            ['name'=>'edit customer'],
            ['name'=>'delete customer'],
            ['name'=>'view customer'],

             
            ['name'=>'edit setting'],
            ['name'=>'view setting'],





        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
 
        }
       
       
    }
}
