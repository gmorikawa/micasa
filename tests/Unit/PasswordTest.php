<?php

use Tests\TestCase;

use App\Core\Auth\PlainPassword;
use App\Core\Auth\HashedPassword;
use App\Core\Auth\PasswordHasher;

class PasswordTest extends TestCase
{
    private PasswordHasher $passwordHasher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->passwordHasher = app(PasswordHasher::class);
    }

    public function testSuccessForCorrectPassword()
    {
        $plainPassword = new PlainPassword('mysecretpassword');
        $hashedPassword = $this->passwordHasher->hash($plainPassword);

        $this->assertNotEquals($plainPassword->value, $hashedPassword->value);
        $this->assertTrue($this->passwordHasher->verify($plainPassword, $hashedPassword));
    }

    public function testFailureForIncorrectPassword()
    {
        $plainPassword = new PlainPassword('mysecretpassword');
        $hashedPassword = $this->passwordHasher->hash($plainPassword);

        $wrongPlainPassword = new PlainPassword('wrongpassword');

        $this->assertNotEquals($plainPassword->value, $hashedPassword->value);
        $this->assertFalse($this->passwordHasher->verify($wrongPlainPassword, $hashedPassword));
    }
}