<?php

namespace App\Policies;

use App\Models\DoctorAvailability;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Auth\Access\Response;

class AvailabilityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Doctors can view their own availability schedules
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DoctorAvailability $doctorAvailability): bool
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

        if ($doctorAvailability->subdomain_id !== $subdomain->id) {
            return false;
        }

        // Must be the owner of the availability schedule
        return $doctorAvailability->doctor_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Doctors can create availability schedules in their subdomain
        return $user->isDoctor() && TenantContext::getCurrentSubdomain() !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DoctorAvailability $doctorAvailability): bool
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

        if ($doctorAvailability->subdomain_id !== $subdomain->id) {
            return false;
        }

        // Must be the owner of the availability schedule
        return $doctorAvailability->doctor_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DoctorAvailability $doctorAvailability): bool
    {
        return $this->update($user, $doctorAvailability);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DoctorAvailability $doctorAvailability): bool
    {
        return $this->update($user, $doctorAvailability);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DoctorAvailability $doctorAvailability): bool
    {
        return $this->delete($user, $doctorAvailability);
    }
}
