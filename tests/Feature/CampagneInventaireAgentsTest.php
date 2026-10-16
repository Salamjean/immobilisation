<?php

namespace Tests\Feature;

use App\Models\CampagneInventaire;
use App\Models\InventaireEquipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampagneInventaireAgentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_agents_on_create_page()
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'actif' => true]);
        
        $response = $this->actingAs($admin)->get(route('admin.inventaires.create'));
        
        $response->assertStatus(200);
        $response->assertSee('Agents d\'Inventaire Attribués', false);
        $response->assertSee('Superviseur de la Mission', false);
    }

    public function test_gestionnaire_can_view_agents_on_create_page()
    {
        $gestionnaire = User::factory()->create(['role' => User::ROLE_GESTIONNAIRE, 'actif' => true]);
        
        $response = $this->actingAs($gestionnaire)->get(route('gestionnaire.inventaires.create'));
        
        $response->assertStatus(200);
        $response->assertSee('Agents d\'Inventaire Attribués', false);
        $response->assertSee('Superviseur de la Mission', false);
    }

    public function test_roles_filtering_for_agents_and_superviseurs()
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'actif' => true]);
        
        $agentUser = User::factory()->create(['role' => User::ROLE_AGENT_INVENTAIRE, 'actif' => true, 'name' => 'Agent Unique Test']);
        $autreUser = User::factory()->create(['role' => User::ROLE_GESTIONNAIRE, 'actif' => true, 'name' => 'Gestionnaire Non Agent']);
        
        $auditeurUser = User::factory()->create(['role' => User::ROLE_AUDITEUR, 'actif' => true, 'name' => 'Auditeur Superviseur Test']);
        $consultantUser = User::factory()->create(['role' => User::ROLE_CONSULTANT, 'actif' => true, 'name' => 'Consultant Superviseur Test']);
        $lecteurUser = User::factory()->create(['role' => User::ROLE_LECTEUR, 'actif' => true, 'name' => 'Lecteur Superviseur Test']);

        $response = $this->actingAs($admin)->get(route('admin.inventaires.create'));
        
        $agents = $response->viewData('agents');
        $superviseurs = $response->viewData('superviseurs');

        // Les agents ne doivent contenir que le rôle agent_inventaire
        $this->assertTrue($agents->contains('id', $agentUser->id));
        $this->assertFalse($agents->contains('id', $autreUser->id));
        foreach ($agents as $a) {
            $this->assertEquals(User::ROLE_AGENT_INVENTAIRE, $a->role);
        }

        // Les superviseurs ne doivent contenir que auditeur, consultant, lecteur
        $this->assertTrue($superviseurs->contains('id', $auditeurUser->id));
        $this->assertTrue($superviseurs->contains('id', $consultantUser->id));
        $this->assertTrue($superviseurs->contains('id', $lecteurUser->id));
        $this->assertFalse($superviseurs->contains('id', $agentUser->id));
        $this->assertFalse($superviseurs->contains('id', $autreUser->id));
        foreach ($superviseurs as $s) {
            $this->assertContains($s->role, [User::ROLE_AUDITEUR, User::ROLE_CONSULTANT, User::ROLE_LECTEUR]);
        }
    }

    public function test_validation_fails_when_no_agent_selected_by_admin()
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first() ?? User::factory()->create(['role' => User::ROLE_ADMIN, 'actif' => true]);

        $response = $this->actingAs($admin)->post(route('admin.inventaires.store'), [
            'code_campagne' => 'TEST-INV-NO-AGENT-' . uniqid(),
            'intitule' => 'Campagne test sans agent',
            'type_campagne' => 'annuel',
            'date_debut' => '2026-10-05',
            'date_fin' => '2026-11-05',
            'agents' => [],
        ]);

        $response->assertSessionHasErrors('agents');
    }

    public function test_validation_fails_when_no_agent_selected_by_gestionnaire()
    {
        $gestionnaire = User::where('role', User::ROLE_GESTIONNAIRE)->first() ?? User::factory()->create(['role' => User::ROLE_GESTIONNAIRE, 'actif' => true]);

        $response = $this->actingAs($gestionnaire)->post(route('gestionnaire.inventaires.store'), [
            'code_campagne' => 'TEST-INV-NO-AGENT-GEST-' . uniqid(),
            'intitule' => 'Campagne test sans agent gestionnaire',
            'type_campagne' => 'annuel',
            'date_debut' => '2026-10-05',
            'date_fin' => '2026-11-05',
            'agents' => [],
        ]);

        $response->assertSessionHasErrors('agents');
    }

    public function test_campaign_created_and_agents_assigned_by_admin()
    {
        $admin = User::where('role', User::ROLE_ADMIN)->first() ?? User::factory()->create(['role' => User::ROLE_ADMIN, 'actif' => true]);
        $agents = User::where('role', User::ROLE_AGENT_INVENTAIRE)->take(2)->get();
        
        if ($agents->isEmpty()) {
            $agents = User::take(2)->get();
        }

        $code = 'TEST-INV-' . uniqid();

        $response = $this->actingAs($admin)->post(route('admin.inventaires.store'), [
            'code_campagne' => $code,
            'intitule' => 'Campagne Test Affectation Admin',
            'type_campagne' => 'annuel',
            'date_debut' => '2026-10-05',
            'date_fin' => '2026-11-05',
            'agents' => $agents->pluck('id')->toArray(),
        ]);

        $response->assertSessionHasNoErrors();
        
        $campagne = CampagneInventaire::where('code_campagne', $code)->first();
        $this->assertNotNull($campagne);

        $equipes = InventaireEquipe::where('campagne_id', $campagne->id)->pluck('user_id')->toArray();
        foreach ($agents as $agent) {
            $this->assertContains($agent->id, $equipes);
        }

        // Clean up
        InventaireEquipe::where('campagne_id', $campagne->id)->delete();
        $campagne->delete();
    }

    public function test_campaign_created_and_agents_assigned_by_gestionnaire()
    {
        $gestionnaire = User::where('role', User::ROLE_GESTIONNAIRE)->first() ?? User::factory()->create(['role' => User::ROLE_GESTIONNAIRE, 'actif' => true]);
        $agents = User::where('role', User::ROLE_AGENT_INVENTAIRE)->take(2)->get();
        
        if ($agents->isEmpty()) {
            $agents = User::take(2)->get();
        }

        $code = 'TEST-INV-GEST-' . uniqid();

        $response = $this->actingAs($gestionnaire)->post(route('gestionnaire.inventaires.store'), [
            'code_campagne' => $code,
            'intitule' => 'Campagne Test Affectation Gestionnaire',
            'type_campagne' => 'annuel',
            'date_debut' => '2026-10-05',
            'date_fin' => '2026-11-05',
            'agents' => $agents->pluck('id')->toArray(),
        ]);

        $response->assertSessionHasNoErrors();
        
        $campagne = CampagneInventaire::where('code_campagne', $code)->first();
        $this->assertNotNull($campagne);

        $equipes = InventaireEquipe::where('campagne_id', $campagne->id)->pluck('user_id')->toArray();
        foreach ($agents as $agent) {
            $this->assertContains($agent->id, $equipes);
        }

        // Clean up
        InventaireEquipe::where('campagne_id', $campagne->id)->delete();
        $campagne->delete();
    }
}
