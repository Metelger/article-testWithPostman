<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Criação da tabela 'contacts' com Blueprint
        Schema::create('contacts', function (Blueprint $table) {
            // Coluna 'id': chave primária autoincremento
            $table->uuid('id')->primary();
            
            // Coluna 'name': armazena o nome do contato (tipo string)
            $table->string('name');
            
            // Coluna 'phone': armazena dados do telefone em formato JSON
            $table->json('phone');
            
            // Coluna 'email': armazena o endereço de e-mail do contato, único
            $table->string('email')->unique();
            
            // Coluna 'document': armazena o número de documento do contato, único
            $table->string('document')->unique();
            
            // Colunas 'created_at' e 'updated_at': timestamps para rastrear criação e atualização
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Dropa (exclui) a tabela 'contacts' se ela existir
        Schema::dropIfExists('contacts');
    }
}
