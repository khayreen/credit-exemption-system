<?php

namespace App\Enums;

enum UserRole: string
{
    case STUDENT = 'student';
    case ACADEMIC_ADVISOR = 'academic_advisor';
    case COORDINATOR = 'coordinator';
    case RESOURCE_PERSON = 'resource_person';
    case HEA = 'hea_personnel';
    case EXTERNAL_LECTURER = 'external_lecturer';
    case ADMIN = 'admin';

    /**
     * Get human-readable label for the role
     */
    public function label(): string
    {
        return match($this) {
            self::STUDENT => 'Student',
            self::ACADEMIC_ADVISOR => 'Academic Advisor',
            self::COORDINATOR => 'Program Coordinator',
            self::RESOURCE_PERSON => 'Resource Person',
            self::HEA => 'HEA Personnel',
            self::EXTERNAL_LECTURER => 'External Lecturer',
            self::ADMIN => 'System Administrator',
        };
    }

    /**
     * Get short badge code for the role
     */
    public function badge(): string
    {
        return match($this) {
            self::STUDENT => 'STU',
            self::ACADEMIC_ADVISOR => 'AA',
            self::COORDINATOR => 'PC',
            self::RESOURCE_PERSON => 'RP',
            self::HEA => 'HEA',
            self::EXTERNAL_LECTURER => 'EXT',
            self::ADMIN => 'ADM',
        };
    }

    /**
     * Get Bootstrap color class for the role
     */
    public function color(): string
    {
        return match($this) {
            self::STUDENT => 'info',
            self::ACADEMIC_ADVISOR => 'primary',
            self::COORDINATOR => 'success',
            self::RESOURCE_PERSON => 'warning',
            self::HEA => 'danger',
            self::EXTERNAL_LECTURER => 'secondary',
            self::ADMIN => 'dark',
        };
    }

    /**
     * Check if the role requires HEA approval
     */
    public function requiresHeaApproval(): bool
    {
        return in_array($this, [
            self::ACADEMIC_ADVISOR,
            self::COORDINATOR,
            self::RESOURCE_PERSON,
        ]);
    }

    /**
     * Check if the role requires admin approval
     */
    public function requiresAdminApproval(): bool
    {
        return $this === self::HEA;
    }

    /**
     * Check if this is an admin role
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Get all roles that require HEA approval
     */
    public static function heaApprovalRoles(): array
    {
        return [
            self::ACADEMIC_ADVISOR,
            self::COORDINATOR,
            self::RESOURCE_PERSON,
        ];
    }

    /**
     * Get all roles as values (strings)
     */
    public static function heaApprovalRoleValues(): array
    {
        return array_map(fn($role) => $role->value, self::heaApprovalRoles());
    }

    /**
     * Get all staff roles (non-student, non-external, non-admin)
     */
    public static function staffRoles(): array
    {
        return [
            self::ACADEMIC_ADVISOR,
            self::COORDINATOR,
            self::RESOURCE_PERSON,
            self::HEA,
        ];
    }

    /**
     * Get all administrative roles
     */
    public static function adminRoles(): array
    {
        return [
            self::ADMIN,
        ];
    }

    /**
     * Try to create from string value, returns null if invalid
     */
    public static function tryFromString(?string $value): ?self
    {
        if ($value === null) {
            return null;
        }

        return self::tryFrom($value);
    }
}
