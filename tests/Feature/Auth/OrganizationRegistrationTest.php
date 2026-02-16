<?php

namespace Tests\Feature\Auth;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class OrganizationRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/registro-organizacion');

        $response
            ->assertOk()
            ->assertSeeText('Crear Cuenta de Organización');
    }

    public function test_new_organization_and_admin_user_can_register(): void
    {
        $component = Volt::test('pages.auth.register-organization')
            ->set('organization_name', 'Acme Labs')
            ->set('organization_email', 'contacto@acme.test')
            ->set('organization_phone', '555-1234')
            ->call('nextStep')
            ->set('name', 'Admin Acme')
            ->set('email', 'admin@acme.test')
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->call('registerOrganization');

        $component->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();

        $organization = Organization::where('name', 'Acme Labs')->first();
        $this->assertNotNull($organization);

        $user = User::where('email', 'admin@acme.test')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->organization_id);
        $this->assertTrue($user->hasRole('admin'));
    }
}
