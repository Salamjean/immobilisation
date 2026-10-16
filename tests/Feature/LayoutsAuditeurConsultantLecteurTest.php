<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutsAuditeurConsultantLecteurTest extends TestCase
{
    use RefreshDatabase;

    public function test_auditeur_interface_has_same_modern_sidebar_and_navbar_style()
    {
        $auditeur = User::factory()->create([
            'role' => User::ROLE_AUDITEUR,
            'actif' => true,
            'name' => 'Auditeur Test',
        ]);

        $response = $this->actingAs($auditeur)->get(route('auditeur.dashboard'));

        $response->assertStatus(200);
        // Doit avoir la sidebar détachable moderne et la navbar #155E3B
        $response->assertSee('id="mainSidebar"', false);
        $response->assertSee('navbarSidebarToggle', false);
        $response->assertSee('navbarUserDropdownBtn', false);
        $response->assertSee('border-color: #155E3B;', false);
        $response->assertSee('background-color: #155E3B;', false);
        $response->assertSee('Audit', false);
        $response->assertSee('Journal d\'Audit Intègre', false);
    }

    public function test_consultant_interface_has_same_modern_sidebar_and_navbar_style()
    {
        $consultant = User::factory()->create([
            'role' => User::ROLE_CONSULTANT,
            'actif' => true,
            'name' => 'Consultant Test',
        ]);

        $response = $this->actingAs($consultant)->get(route('auditeur.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('id="mainSidebar"', false);
        $response->assertSee('navbarSidebarToggle', false);
        $response->assertSee('navbarUserDropdownBtn', false);
        $response->assertSee('border-color: #155E3B;', false);
        $response->assertSee('background-color: #155E3B;', false);
        $response->assertSee('Consultant', false);
        $response->assertSee('Synthèse & Indicateurs', false);
    }

    public function test_lecteur_interface_has_same_modern_sidebar_and_navbar_style()
    {
        $lecteur = User::factory()->create([
            'role' => User::ROLE_LECTEUR,
            'actif' => true,
            'name' => 'Lecteur Test',
        ]);

        $response = $this->actingAs($lecteur)->get(route('gestionnaire.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('id="mainSidebar"', false);
        $response->assertSee('navbarSidebarToggle', false);
        $response->assertSee('navbarUserDropdownBtn', false);
        $response->assertSee('border-color: #155E3B;', false);
        $response->assertSee('background-color: #155E3B;', false);
        $response->assertSee('Lecteur', false);
        $response->assertSee('Mode Consultation Seule', false);
    }
}
