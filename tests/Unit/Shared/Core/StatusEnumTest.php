<?php

namespace Tests\Unit\Shared\Core;

use PHPUnit\Framework\TestCase;
use App\Shared\Core\Enums\StatusEnum;

class StatusEnumTest extends TestCase
{
    public function test_active_status_has_correct_value(): void
    {
        $this->assertEquals('active', StatusEnum::ACTIVE->value);
    }

    public function test_all_statuses_have_labels(): void
    {
        foreach (StatusEnum::cases() as $status) {
            $this->assertNotEmpty($status->label());
            $this->assertIsString($status->label());
        }
    }

    public function test_all_statuses_have_colors(): void
    {
        foreach (StatusEnum::cases() as $status) {
            $this->assertNotEmpty($status->color());
            $this->assertIsString($status->color());
        }
    }

    public function test_active_label_is_active(): void
    {
        $this->assertEquals('Active', StatusEnum::ACTIVE->label());
    }

    public function test_active_color_is_success(): void
    {
        $this->assertEquals('success', StatusEnum::ACTIVE->color());
    }

    public function test_suspended_color_is_danger(): void
    {
        $this->assertEquals('danger', StatusEnum::SUSPENDED->color());
    }

    public function test_pending_color_is_warning(): void
    {
        $this->assertEquals('warning', StatusEnum::PENDING->color());
    }

    public function test_status_can_be_created_from_value(): void
    {
        $status = StatusEnum::from('active');
        $this->assertEquals(StatusEnum::ACTIVE, $status);
    }

    public function test_status_try_from_returns_null_for_invalid(): void
    {
        $status = StatusEnum::tryFrom('invalid');
        $this->assertNull($status);
    }
}
