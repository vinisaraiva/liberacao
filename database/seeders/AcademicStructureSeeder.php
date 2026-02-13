<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Serie;
use App\Models\Turma;

class AcademicStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curso = Course::create(['nome' => 'Ensino Médio']);

        $serie1 = Serie::create(['nome' => '1ª Série', 'ordem' => 1]);
        $serie2 = Serie::create(['nome' => '2ª Série', 'ordem' => 2]);
        $serie3 = Serie::create(['nome' => '3ª Série', 'ordem' => 3]);

        $turma1A = Turma::create(['curso_id' => $curso->id, 'serie_id' => $serie1->id, 'nome' => 'A']);
        Turma::create(['curso_id' => $curso->id, 'serie_id' => $serie1->id, 'nome' => 'B']);
        Turma::create(['curso_id' => $curso->id, 'serie_id' => $serie2->id, 'nome' => 'A']);
        Turma::create(['curso_id' => $curso->id, 'serie_id' => $serie3->id, 'nome' => 'A']);

        // Create Students
        \App\Models\Student::create([
            'nome' => 'João Silva',
            'matricula' => '2024001',
            'curso_id' => $curso->id,
            'serie_id' => $serie1->id,
            'turma_id' => $turma1A->id,
        ]);

        \App\Models\Student::create([
            'nome' => 'Maria Oliveira',
            'matricula' => '2024002',
            'curso_id' => $curso->id,
            'serie_id' => $serie1->id,
            'turma_id' => $turma1A->id,
        ]);
    }
}
