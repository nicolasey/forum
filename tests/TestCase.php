<?php
namespace Nicolasey\Forum\Tests;

use Orchestra\Testbench\TestCase as Base;
use Illuminate\Database\Schema\Blueprint;
use Nicolasey\Forum\Tests\Models\User;

class TestCase extends Base
{
    protected $tempDirectory = __DIR__."/temp";

    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function checkRequirements()
    {
        parent::checkRequirements();

        collect($this->getAnnotations())->filter(function ($location) {
            return in_array('!Travis', array_get($location, 'requires', []));
        })->each(function ($location) {
            getenv('TRAVIS') && $this->markTestSkipped('Travis will not run this test.');
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            \Nicolasey\Forum\ForumServiceProvider::class,
            \Nicolasey\Forum\ForumEventServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->tempDirectory.'/database.sqlite',
            'prefix' => '',
        ]);
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
        $app['config']->set('forum.author.class', User::class);
    }

    protected function setUpDatabase()
    {
        $this->resetDatabase();
        $this->createTables("users");
        $this->createForumTables();
    }

    protected function resetDatabase()
    {
        file_put_contents($this->tempDirectory.'/database.sqlite', null);
    }

    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function (string $tableName) {
            $this->app['db']->connection()->getSchemaBuilder()->create($tableName, function (Blueprint $table) use ($tableName) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->unsignedSmallInteger("nb_posts")->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        });
    }

    protected function createForumTables()
    {
        include_once __DIR__.'/../database/migrations/2018_09_28_000000_create_forums_table.php';
        (new \CreateForumsTable)->up();

        include_once __DIR__.'/../database/migrations/2018_09_28_000002_create_topics_table.php';
        (new \CreateTopicsTable)->up();

        include_once __DIR__.'/../database/migrations/2018_09_28_000003_create_topics_participants_table.php';
        (new \CreateTopicParticipantsTable)->up();

        include_once __DIR__.'/../database/migrations/2018_09_28_000001_create_posts_table.php';
        (new \CreatePostsTable)->up();
    }
}