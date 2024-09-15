<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;

class PermissionsHelper
{
    protected function checkPermission(): bool{
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            return true;
        }

        return false;
    }

    public function seeHiddeItems($model): void
    {
        $checkPermission = $this->checkPermission();

        if (!$checkPermission) {
            $model->where('visible', true);
        }
    }

    public function seeHiddeItem($model): bool{
        $checkPermission = $this->checkPermission();

        if ($checkPermission) {
            return true;
        }

        if (!$model->visible) {
            abort(403, 'No tienes permiso para ver este recurso.');
        }
        return false;
    }
}
