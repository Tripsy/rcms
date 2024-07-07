<?php

namespace Tests\Feature;

use Tests\TestCase;

class HelpersTest extends TestCase
{
    /**
     * Test isValidHttpResponseCode function.
     *
     * @return void
     */
    public function testIsValidHttpResponseCode()
    {
        // Valid HTTP response code
        $this->assertTrue(isValidHttpResponseCode(200));
        $this->assertTrue(isValidHttpResponseCode(404));

        // Invalid HTTP response code
        $this->assertFalse(isValidHttpResponseCode(999));
        $this->assertFalse(isValidHttpResponseCode(150));
    }

    /**
     * Test returnValidHttpResponseCode function.
     *
     * @return void
     */
    public function testReturnValidHttpResponseCode()
    {
        // Valid provided code should be returned
        $this->assertEquals(200, returnValidHttpResponseCode(200, 500));
        $this->assertEquals(404, returnValidHttpResponseCode(404, 500));

        // Invalid provided code should return fallback code
        $this->assertEquals(500, returnValidHttpResponseCode(999, 500));
        $this->assertEquals(500, returnValidHttpResponseCode(150, 500));
    }
}
