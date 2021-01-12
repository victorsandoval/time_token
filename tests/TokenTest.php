<?php
namespace TimeToken\Tests;

use PHPUnit\Framework\TestCase;
use TimeToken\TimeToken;

class TokenTest extends TestCase
{
    public function testGenerateToken(): void
    {
        $class = new TimeToken();
        $token = $class->generateTimeToken(5, '$@#$RQFSDASD', 'D$#@$%$');
        $this->assertEquals(strlen($token), 32);
    }

    public function testValidToken(): void
    {
        $class = new TimeToken();
        $token = $class->generateTimeToken(5, '$@#$RQFSDASD', 'D$#@$%$');
        $valid = $class->tokenIsValid($token, '$@#$RQFSDASD', 'D$#@$%$');

        $this->assertTrue($valid);
    }

    public function testExpiredToken(): void
    {
        $class = new TimeToken();
        $token = $class->generateTimeToken(2, '$@#$RQFSDASD', 'D$#@$%$');

        sleep(3);
        $valid = $class->tokenIsValid($token, '$@#$RQFSDASD', 'D$#@$%$');

        $this->assertFalse($valid);
    }

}
