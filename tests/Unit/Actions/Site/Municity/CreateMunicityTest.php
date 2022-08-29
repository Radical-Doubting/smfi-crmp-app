<?php

namespace Tests\Unit\Actions\Site\Municity;

use App\Actions\Site\Municity\CreateMunicity;
use App\Models\Site\Municity;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateMunicityTest extends TestCase
{
    public function testShouldCreateMunicity()
    {
        $mockMunicity = $this->partialMock(Municity::class, function (MockInterface $mock) {
            $mock->shouldReceive('save')->once()->andReturn(true);
        });

        $createdMunicity = CreateMunicity::run($mockMunicity, [
            'name' => 'Santa Rosa',
            'province_id' => 1,
            'region_id' => 1,
        ]);

        $this->assertEquals($createdMunicity->name, 'Santa Rosa');
        $this->assertEquals($createdMunicity->province_id, 1);
        $this->assertEquals($createdMunicity->region_id, 1);
    }
}
