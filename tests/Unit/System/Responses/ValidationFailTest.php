<?php declare (strict_types = 1);

namespace Tests\Unit\System\Responses;

use Tests\BaseTestCase;
use App\System\Responses\ValidationFail;

class ValidationFailTest extends BaseTestCase
{
    /** @test */
    public function that_it_works()
    {
        $responsable = new ValidationFail([
            'name'  => [
                'Name is required',
            ],
            'email' => [
                'Email is required',
            ],
        ]);

        $response = $responsable->toResponse();

        $body = json_decode((string) $response->getBody(), true);

        $this->assertArrayHasKey('errors', $body);
        $this->assertArrayHasKey('name', $body['errors']);
        $this->assertArrayHasKey('email', $body['errors']);
    }
}
