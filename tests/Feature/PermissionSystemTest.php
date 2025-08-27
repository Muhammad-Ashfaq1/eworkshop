<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;
use App\Models\DefectReport;

class PermissionSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create permissions
        $permissions = [
            'create_users', 'read_users', 'update_users', 'delete_users',
            'create_locations', 'read_locations', 'update_locations', 'delete_locations',
            'create_vehicles', 'read_vehicles', 'update_vehicles', 'delete_vehicles',
            'create_defect_reports', 'read_defect_reports', 'update_defect_reports', 'delete_defect_reports',
            'export_data', 'view_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $admin = Role::create(['name' => 'admin']);
        $deo = Role::create(['name' => 'deo']);

        // Assign permissions to roles
        $superAdmin->givePermissionTo(Permission::all());
        
        $admin->givePermissionTo([
            'create_users', 'read_users', 'update_users', 'delete_users',
            'create_locations', 'read_locations', 'update_locations',
            'create_vehicles', 'read_vehicles', 'update_vehicles',
            'create_defect_reports', 'read_defect_reports', 'update_defect_reports',
            'export_data', 'view_reports'
        ]);

        $deo->givePermissionTo([
            'read_locations', 'read_vehicles', 'read_defect_reports',
            'create_defect_reports', 'view_reports'
        ]);
    }

    public function test_super_admin_can_perform_defect_report_crud()
    {
        // Create super admin user
        $superAdmin = User::factory()->create();
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $superAdmin->assignRole($superAdminRole);

        // Create a defect report
        $defectReport = DefectReport::factory()->create([
            'created_by' => User::factory()->create()->id,
        ]);

        // Test that super admin can view defect reports
        $this->actingAs($superAdmin)
            ->get('/defect-reports')
            ->assertStatus(200);

        // Test that super admin can view defect report listing
        $this->actingAs($superAdmin)
            ->get('/defect-reports/listing')
            ->assertStatus(200);

        // Test that super admin can edit defect report (returns JSON)
        $this->actingAs($superAdmin)
            ->get("/defect-reports/{$defectReport->id}/edit")
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        // Test that super admin can delete defect report
        $this->actingAs($superAdmin)
            ->delete("/defect-reports/{$defectReport->id}")
            ->assertStatus(200);
    }

    public function test_super_admin_can_update_defect_report()
    {
        // Create super admin user
        $superAdmin = User::factory()->create();
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $superAdmin->assignRole($superAdminRole);

        // Create a defect report
        $defectReport = DefectReport::factory()->create([
            'created_by' => User::factory()->create()->id,
        ]);

        // Test that super admin can update defect report
        $this->actingAs($superAdmin)
            ->put("/defect-reports/{$defectReport->id}", [
                'driver_name' => 'Updated Driver Name',
                'date' => '2025-08-28',
                'type' => 'defect_report',
                'works' => [
                    [
                        'work' => 'Updated work description',
                        'type' => 'defect'
                    ]
                ]
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        // Verify the update was successful
        $this->assertDatabaseHas('defect_reports', [
            'id' => $defectReport->id,
            'driver_name' => 'Updated Driver Name',
        ]);
    }

    public function test_super_admin_has_all_permissions()
    {
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $this->assertTrue($user->can('create_users'));
        $this->assertTrue($user->can('delete_locations'));
        $this->assertTrue($user->can('export_data'));
        $this->assertTrue($user->can('delete_defect_reports'));
    }

    public function test_admin_cannot_delete_master_data()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->can('create_locations'));
        $this->assertTrue($user->can('update_locations'));
        $this->assertFalse($user->can('delete_locations'));

        $this->assertTrue($user->can('create_vehicles'));
        $this->assertTrue($user->can('update_vehicles'));
        $this->assertFalse($user->can('delete_vehicles'));
    }

    public function test_deo_can_only_view_and_create_defect_reports()
    {
        $user = User::factory()->create();
        $user->assignRole('deo');

        $this->assertTrue($user->can('read_locations'));
        $this->assertFalse($user->can('create_locations'));
        $this->assertFalse($user->can('update_locations'));

        $this->assertTrue($user->can('create_defect_reports'));
        $this->assertTrue($user->can('read_defect_reports'));
        $this->assertFalse($user->can('update_defect_reports'));
        $this->assertFalse($user->can('delete_defect_reports'));
    }
}
