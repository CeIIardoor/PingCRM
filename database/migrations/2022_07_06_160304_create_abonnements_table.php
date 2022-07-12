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
            $table->string('ville', 50)->nullable();
            $table->string('CIN', 20)->nullable();
            $table->string('dernier_grade', 100)->nullable();
            $table->enum('type_abonnement',['Mobile','Fixe','Internet'])->nullable();
            $table->string('type_forfait', 50)->nullable();
            $table->enum('operateur',['IAM','ORANGE','INWI'])->nullable();
            $table->string('periode_engagement', 50);
            $table->string('montant_forfait', 50);
            $table->enum('telephone',['Oui','Non'])->default('Non')->nullable();
            $table->date('date_debut'->nullable());
            $table->date('date_cloture')->nullable();
            $table->string('gestionnaire')->nullable();
            $table->string('derniere_province')->nullable();
            $table->text('commentaire')->nullable();
            $table->string('num_contrat_operateur')->nullable();

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
