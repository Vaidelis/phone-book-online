<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Handlers\SharedPhoneBook;

use App\Http\Services\Handlers\FinalResponseHandler;
use App\Http\Services\Handlers\PhoneBook\AlreadySharedHandler;
use App\Http\Services\Handlers\PhoneBook\FindPhoneBookHandler;
use App\Http\Services\Handlers\SharedPhoneBook\SharePhoneBookHandler;
use App\Http\Services\Handlers\SharedPhoneBook\SharedPhoneBookShareHandler;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class SharedPhoneBookShareHandlerTest extends TestCase
{
    private MockInterface $findPhoneBookHandler;
    private SharedPhoneBookShareHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->findPhoneBookHandler = Mockery::mock(FindPhoneBookHandler::class);
        $alreadySharedHandler = Mockery::mock(AlreadySharedHandler::class);
        $sharePhoneBookHandler = Mockery::mock(SharePhoneBookHandler::class);
        $finalResponseHandler = Mockery::mock(FinalResponseHandler::class);

        $this->findPhoneBookHandler->shouldReceive('setNext')
            ->once()
            ->with($alreadySharedHandler)
            ->andReturnSelf();

        $this->findPhoneBookHandler->shouldReceive('setNext')
            ->once()
            ->with($sharePhoneBookHandler)
            ->andReturnSelf();

        $this->findPhoneBookHandler->shouldReceive('setNext')
            ->once()
            ->with($finalResponseHandler)
            ->andReturnSelf();

        $this->handler = new SharedPhoneBookShareHandler(
            $this->findPhoneBookHandler,
            $alreadySharedHandler,
            $sharePhoneBookHandler,
            $finalResponseHandler
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public static function shareDataProvider(): array
    {
        $cases = [];
        $phoneBookId = 1;
        $validated = ['shared_user_id' => 2];

        // Case 1: Successful share
        $exception = null;
        $expectedResult = [
            'success' => true,
            'message' => 'Phone book shared successfully',
            'statusCode' => Response::HTTP_OK
        ];

        $cases['successful share'] = [$phoneBookId, $validated, $exception, $expectedResult];

        // Case 2: Database error
        $exception = new \Exception('Database connection failed');
        $expectedResult = [
            'success' => false,
            'message' => 'Failed to share phone book: Database connection failed',
            'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR
        ];

        $cases['database error'] = [$phoneBookId, $validated, $exception, $expectedResult];

        // Case 3: Missing user id
        $validated = [];
        $exception = new \Exception('Undefined array key "shared_user_id"');
        $expectedResult = [
            'success' => false,
            'message' => 'Failed to share phone book: Undefined array key "shared_user_id"',
            'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR
        ];

        $cases['missing user id'] = [$phoneBookId, $validated, $exception, $expectedResult];

        return $cases;
    }

    #[DataProvider('shareDataProvider')]
    public function testShare(
        int $phoneBookId,
        array $validated,
        ?\Exception $exception,
        array $expectedResult
    ): void {
        $context = [
            'id' => $phoneBookId,
            'validated' => $validated
        ];

        if ($exception) {
            $this->findPhoneBookHandler->shouldReceive('handle')
                ->once()
                ->with($context)
                ->andThrow($exception);
        } else {
            $this->findPhoneBookHandler->shouldReceive('handle')
                ->once()
                ->with($context)
                ->andReturn($expectedResult);
        }

        $result = $this->handler->share($phoneBookId, $validated);

        $this->assertEquals($expectedResult, $result);

        Mockery::close();
    }
}
