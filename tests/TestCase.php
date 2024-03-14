<?php

use App\Models\User;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    protected $userRepository;

    // Set up the repository instance for testing.
    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository(new User());
    }

    // Test Case 1: Creating a customer user
    public function test_create_customer_user()
    {
        // Sample customer data
        $customerData = [
            'role' => env('CUSTOMER_ROLE_ID'),
            'name' => 'Test Customer',
            'email' => 'testcustomer@example.com',
            "more" => "attributes"
        ];

        $createdUser = $this->userRepository->createOrUpdate(null, $customerData);

        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertDatabaseHas('users', [
            'name' => 'Test Customer',
            'email' => 'testcustomer@example.com'
        ]);
        $this->assertTrue($createdUser->hasRole(env('CUSTOMER_ROLE_ID')));
    }

    // Test Case 2: Updating a translator user meta
    public function test_update_translator_meta()
    {
        // Find existing translator (assume you have one set up)
        $translator = User::where('user_type', env('TRANSLATOR_ROLE_ID'))->firstOrFail();

        $updatedMeta = [
            'translator_type' => 'certified',
        ];

        $updatedUser = $this->userRepository->createOrUpdate($translator->id, $updatedMeta);

        $this->assertInstanceOf(User::class, $updatedUser);
        $this->assertDatabaseHas('user_meta', [
            'user_id' => $translator->id,
            'translator_type' => 'certified'
        ]);
    }
}
