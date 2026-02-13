<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Terminal;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    /**
     * Log an action.
     *
     * @param User|null $user
     * @param Terminal|null $terminal
     * @param string $action
     * @param Model $entity
     * @param array $meta
     * @return AuditLog
     */
    public function log(?User $user, ?Terminal $terminal, string $action, Model $entity, array $meta = []): AuditLog
    {
        return AuditLog::create([
            'actor_user_id' => $user->id ?? null,
            'actor_terminal_id' => $terminal->id ?? null,
            'action' => $action,
            'entity_type' => class_basename($entity),
            'entity_id' => $entity->id,
            'meta_json' => !empty($meta) ? $meta : null,
        ]);
    }
}
