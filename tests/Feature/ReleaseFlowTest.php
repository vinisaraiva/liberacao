<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReleaseFlowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_search_and_create_release_request()
    {
        // 1. Retrieve Seeded Admin
        $admin = User::first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found. Did you run seeders?');
        }

        $this->actingAs($admin);

        // 2. Retrieve Seeded Student
        $student = Student::first();
        if (!$student) {
            $this->markTestSkipped('No students found. Did you run seeders?');
        }

        // Test Search
        $response = $this->getJson("/api/students/search?q={$student->nome}");
        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $student->id]);

        // 3. Create Release Request (on-demand registration)
        $requestData = [
            'matricula' => $student->matricula, // Use existing student to test update
            'nome' => 'STUDENT UPDATED',
            'curso_id' => $student->curso_id,
            'serie_id' => $student->serie_id,
            'turma_id' => $student->turma_id,
            'motivo' => 'Teste Automatizado',
            'observacao' => 'Criado via teste'
        ];

        $response = $this->postJson('/api/release-requests', $requestData);
        $response->assertStatus(201)
            ->assertJson(['status' => 'waiting']);

        $this->assertDatabaseHas('students', [
            'matricula' => $student->matricula,
            'nome' => 'STUDENT UPDATED'
        ]);

        $requestId = $response->json('id');

        // Test Business Rule: Cannot create second active release
        $response = $this->postJson('/api/release-requests', $requestData);
        $response->assertStatus(500); // Exception thrown by service

        // 4. Setup Terminal for Confirmation
        $terminal = \App\Models\Terminal::create([
            'name' => 'Terminal Teste',
            'token_hash' => 'test-token-123',
            'is_active' => true
        ]);

        // Check Portaria List
        $response = $this->getJson('/api/release-requests?status=waiting');
        $response->assertStatus(200);

        // Ensure our request is in the paginated response 'data' array
        $data = $response->json('data');
        $ids = array_column($data, 'id');
        $this->assertTrue(in_array($requestId, $ids), "Request ID {$requestId} not found in waiting list.");

        // 5. Confirm Release (Missing Token)
        $response = $this->postJson("/api/release-requests/{$requestId}/confirm");
        $response->assertStatus(401);

        // 6. Confirm Release (Valid Token)
        $response = $this->postJson("/api/release-requests/{$requestId}/confirm", [], [
            'X-Terminal-Token' => 'test-token-123'
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('release_requests', [
            'id' => $requestId,
            'status' => 'released',
            'confirmed_terminal_id' => $terminal->id
        ]);

        // Verify Audit Logs
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'create_request',
            'entity_type' => 'ReleaseRequest',
            'entity_id' => $requestId,
            'actor_user_id' => $admin->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'confirm_release',
            'entity_type' => 'ReleaseRequest',
            'entity_id' => $requestId,
            'actor_terminal_id' => $terminal->id
        ]);
    }
}
