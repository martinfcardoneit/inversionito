<?php

namespace Database\Seeders;

use App\Models\usuario;
use App\Models\Usuario as ModelsUsuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */ 
    public function run(): void
    {
        //NO OLVIDAR MODIFICAR EN CONFIG/AUTH ---provider cambiar el model por defecto user al modelo Usuario que es el que creamos nosotros
        //usuario no administrador
        usuario::create([
            'nombre' => 'juangarre',
            'email' => 'juangarre@gm.com',
            'password' => bcrypt('admin'),

        ]);

        //usuario administrador
        usuario::create([
            'nombre' => 'robertosanchez',
            'email' => 'beatrixx@gm.com',
            'password' => bcrypt('admin'),
            

        ]);
    }
}
