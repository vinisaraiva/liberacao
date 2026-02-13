<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class StudentService
{
    /**
     * Create or update a student on demand.
     *
     * @param array $data
     * @return Student
     */
    public function upsertOnDemand(array $data): Student
    {
        $matricula = $data['matricula'];

        $student = Student::updateOrCreate(
        ['matricula' => $matricula],
        [
            'nome' => $data['nome'],
            'nome_busca' => $this->normalizeName($data['nome']),
            'curso_id' => $data['curso_id'],
            'serie_id' => $data['serie_id'],
            'turma_id' => $data['turma_id'],
        ]
        );

        return $student;
    }

    /**
     * Find a student by matricula.
     *
     * @param string $matricula
     * @return Student|null
     */
    public function findByMatricula(string $matricula): ?Student
    {
        return Student::where('matricula', $matricula)->first();
    }

    /**
     * Search students by name.
     *
     * @param string $term
     * @param int $limit
     * @return Collection
     */
    public function search(string $term, int $limit = 10): Collection
    {
        $normalizedTerm = $this->normalizeName($term);

        return Student::where('nome_busca', 'like', "%{$normalizedTerm}%")
            ->orWhere('matricula', 'like', "%{$term}%")
            ->limit($limit)
            ->get();
    }

    /**
     * Normalize name for search (uppercase, remove accents).
     *
     * @param string $name
     * @return string
     */
    public function normalizeName(string $name): string
    {
        return Str::upper(Str::ascii($name));
    }
}
