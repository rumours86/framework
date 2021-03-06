<?php

namespace Illuminate\Tests\Integration\Database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

/**
 * @group integration
 */
class EloquentCollectionFreshTest extends DatabaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
        });
    }

    public function test_eloquent_collection_fresh()
    {
        User::insert([
            ['email' => 'laravel@framework.com'],
            ['email' => 'laravel@laravel.com'],
        ]);

        $collection = User::all();

        User::whereKey($collection->pluck('id')->toArray())->delete();

        $this->assertEmpty($collection->fresh()->filter());
    }
}

class User extends Model
{
    protected $guarded = [];
}
