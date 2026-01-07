<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use App\Services\Tenant\TenantContext;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Doctors can view patients in their subdomain
        return $user->isDoctor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patient $patient): bool
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

        return $patient->subdomain_id === $subdomain->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Doctors can create patients in their subdomain
        return $user->isDoctor() && TenantContext::getCurrentSubdomain() !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patient $patient): bool
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

        return $patient->subdomain_id === $subdomain->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patient $patient): bool
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

        return $patient->subdomain_id === $subdomain->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Patient $patient): bool
    {
        return $this->update($user, $patient);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Patient $patient): bool
    {
        return $this->delete($user, $patient);
    }
}
