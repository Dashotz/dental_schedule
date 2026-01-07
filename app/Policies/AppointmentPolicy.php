<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Doctors can view appointments in their subdomain
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Must be a doctor
        if (!$user->isDoctor()) {
            return false;
        }

        // Must belong to the same subdomain
        $subdomain = TenantContext::getCurrentSubdomain();
        if (!$subdomain) {
            return false;
        }

        if ($appointment->subdomain_id !== $subdomain->id) {
            return false;
        }

        // Additional check: user must be the assigned doctor or creator
        if ($appointment->doctor_id && $appointment->doctor_id !== $user->id && 
            $appointment->created_by !== $user->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Doctors can create appointments in their subdomain
        return $user->isDoctor() && TenantContext::getCurrentSubdomain() !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Must be a doctor
        if (!$user->isDoctor()) {
            return false;
        }

        // Must belong to the same subdomain
        $subdomain = TenantContext::getCurrentSubdomain();
        if (!$subdomain) {
            return false;
        }

        if ($appointment->subdomain_id !== $subdomain->id) {
            return false;
        }

        // Additional check: user must be the assigned doctor or creator
        if ($appointment->doctor_id && $appointment->doctor_id !== $user->id && 
            $appointment->created_by !== $user->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return $this->update($user, $appointment);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return $this->update($user, $appointment);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $this->delete($user, $appointment);
    }
}
