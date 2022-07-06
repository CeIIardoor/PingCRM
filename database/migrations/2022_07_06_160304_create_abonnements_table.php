<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbonnementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abonnements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id')->index();
            $table->integer('collaborateur_id')->index();
            $table->string('numero_ligne', 25);
            $table->string('nom', 50);
            $table->string('prenom', 50);
            $table->string('derniere_affectation', 100)->nullable();
            $table->string('ville', 50);
            $table->string('CIN', 20);
            $table->string('dernier_grade', 100)->nullable();
            $table->string('', 50);
            $table->enum('type_abonnement',['Mobile','Fixe','Internet'])->nullable();
            $table->string('type_forfait', 50);
            $table->enum('operateur',['IAM','ORANGE','INWI'])->nullable();
            $table->string('periode_engagement', 50);
            $table->string('montant_forfait', 50);
            $table->enum('telephone',['Oui','Non'])->default('Non');
            $table->date('date_debut');
            $table->date('date_cloture');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abonnements');
    }
}
