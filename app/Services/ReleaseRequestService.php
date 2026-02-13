<?php

namespace App\Services;

use App\Models\ReleaseRequest;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;

class ReleaseRequestService
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Create a new release request.
     *
     * @param Student $student
     * @param User $user
     * @param array $data
     * @return ReleaseRequest
     * @throws \Exception
     */
    public function createGenericRequest(Student $student, User $user, array $data): ReleaseRequest
    {
        // Check for existing active release
        $existing = ReleaseRequest::where('student_id', $student->id)
            ->where('status', 'waiting')
            ->where('valid_until', '>', Carbon::now())
            ->first();

        if ($existing) {
            throw new \Exception("Já existe uma liberação ativa para este aluno (ID: {$existing->id}).");
        }

        $request = ReleaseRequest::create([
            'student_id' => $student->id,
            'created_by_user_id' => $user->id,
            'created_by_name_snapshot' => $user->name,
            'sector' => $data['sector'] ?? 'Não informado',
            'motivo' => $data['motivo'],
            'observacao' => $data['observacao'] ?? null,
            'valid_until' => Carbon::now()->addMinutes(30), // Valid for 30 minutes by default
            'status' => 'waiting',
        ]);

        $this->auditService->log($user, null, 'create_request', $request, $data);

        return $request;
    }

    /**
     * Confirm student release at portaria.
     *
     * @param ReleaseRequest $request
     * @param \App\Models\Terminal $terminal Current terminal model
     * @return ReleaseRequest
     */
    public function confirmRelease(ReleaseRequest $request, \App\Models\Terminal $terminal): ReleaseRequest
    {
        $request->update([
            'status' => 'released',
            'confirmed_at' => Carbon::now(),
            'confirmed_terminal_id' => $terminal->id,
            'confirmed_terminal_name_snapshot' => $terminal->name,
        ]);

        $this->auditService->log(null, $terminal, 'confirm_release', $request);

        return $request;
    }

    /**
     * Cancel an active release request.
     *
     * @param ReleaseRequest $request
     * @param \App\Models\User $user
     * @return ReleaseRequest
     * @throws \Exception
     */
    public function cancelRelease(ReleaseRequest $request, \App\Models\User $user): ReleaseRequest
    {
        if ($request->status !== 'waiting') {
            throw new \Exception("Apenas liberações pendentes podem ser canceladas.");
        }

        // Check if user is admin or the creator
        if ($user->role !== 'ADMIN' && $request->created_by_user_id !== $user->id) {
            throw new \Exception("Você não tem permissão para cancelar esta liberação.");
        }

        $request->update(['status' => 'cancelled']);

        $this->auditService->log($user, null, 'cancel_request', $request);

        return $request;
    }
}
