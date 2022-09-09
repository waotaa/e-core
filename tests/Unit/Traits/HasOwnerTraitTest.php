<?php

namespace Tests\Unit\Traits;

use Vng\EvaCore\Models\Role;
use Tests\Fixtures\ModelWithHasOwnerTrait;
use Tests\TestCase;

class HasOwnerTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->useTestUsers();
    }

    private function getTestUser()
    {
        return $this->getUser(Role::ROLES['instrument-manager-organisation']);
    }

    /** @test */
    public function model_without_owner(): void
    {
        $model = new ModelWithHasOwnerTrait();
        $this->assertFalse($model->hasOwner());
        $this->assertFalse($model->isUserMemberOfOwner($this->getTestUser()));
    }
}
