@extends('layouts.app')

@section('titulo')
Registrate en DevStagram
@endsection

@section('contenido')
    <div class="md:flex">
        <div class="md:w-1/2">
            <p>Imagen Aqui</p>
        </div>
        <div class="md:w-1/2">
            <form action="">
                <div>
                    <label for="" id="name" class="mb-2 block uppercase text-gray-500 font-bold">
                        Nombre
                    </label>
                    <input 
                        type="text"
                        name="name"
                        placeholder="Tu nombre"
                        class="border p-3 w-full rounded-lg"
                    />
                </div>
            </form>

        </div>
    </div>
@endsection