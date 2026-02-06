<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\OrderService;
use App\Interfaces\Repositories\AdminOrderRepositoryInterface;
use App\Interfaces\Repositories\UserOrderRepositoryInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Models\Order;
use Symfony\Component\HttpKernel\Exception\HttpException;

//no need refresh database

class OrderTest extends TestCase
{
    protected OrderService $service;
    protected $adminRepo, $userRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRepo = Mockery::mock(AdminOrderRepositoryInterface::class);

        $this->userRepo = Mockery::mock(UserOrderRepositoryInterface::class);

        $this->service = new OrderService($this->adminRepo, $this->userRepo);
    
    }

    private function fakePaginator( array $items = [], int $total = 1, int $perPage = 5, int $page = 1): LengthAwarePaginator
    {
        return new LengthAwarePaginator(collect($items), $total, $perPage, $page);
    }


    public function test_it_returns_page_number_when_highlighted_order_exists(){

        // Simulate latest order IDs
        $this->adminRepo->shouldReceive('getLatestOrderIds')
            ->once()
            ->andReturn(collect([10, 9, 8, 7, 6, 5, 4]));

        // Fake paginator
        $paginator = $this->fakePaginator(
            [new Order(['id' => 10])], // items // Collection for current page
            7,           // total records
            5,           // Items per page
            1            // current page
        );

        // Simulate paginated result
        $this->adminRepo->shouldReceive('getAdminOrderByStatus')
            ->once()
            ->with(5, 1)
            ->andReturn($paginator);

        // Act
        $result = $this->service->adminOrderIndex(6);

        // Assert
        $this->assertEquals(1, $result['page']);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result['orders']);
        $this->assertEquals(1, $result['orders']->currentPage());
        $this->assertEquals(5, $result['orders']->perPage());
    }

    public function test_it_returns_page_null_when_highlighted_order_not_exists(){

        // Fake paginator
        $paginator = $this->fakePaginator(
            [new Order()], // items // Collection for current page
            7,           // total records
            5,           // Items per page
            1          // current page // paginator still needs valid page
        );

        // Simulate paginated result
        $this->adminRepo->shouldReceive('getAdminOrderByStatus')
            ->once()
            ->with(5, null)
            ->andReturn($paginator);

        // Act
        $result = $this->service->adminOrderIndex(null);

        // Assert
        $this->assertNull($result['page']);
        $this->assertInstanceOf(LengthAwarePaginator::class, $result['orders']);
        $this->assertEquals(1, $result['orders']->currentPage());
        $this->assertEquals(5, $result['orders']->perPage());
        $this->assertSame($paginator, $result['orders']);

    }

    #[DataProvider('statusProvider')]
    public function test_status_mapping(?string $inputStatus, array $expectedDbStatuses, string $expectedView, string $expectedNormalized){
        
        $userId = 1;

        $paginator = $this->fakePaginator(
            [new Order(['id' => 1])], 1, 5);

        $this->userRepo->shouldReceive('getUserOrderByStatus')//create simulation repository so it not hit db / never execute real query method
                ->once()
                ->with($userId, $expectedDbStatuses)
                ->andReturn($paginator);//return fake result

        $result = $this->service->userOrderIndex($userId, $inputStatus);//run services   

        //Assert
        $this->assertEquals($expectedView, $result['view']);
        $this->assertEquals($expectedNormalized, $result['statusNormalized']);
        $this->assertSame($paginator, $result['orders']);
        
    }

    public static function statusProvider(): array
    {
        $pendingShipment = ['pending', 'Confirmed', 'Processing', 'Ready for Delivery'];

        return [
                [
                    null,
                    $pendingShipment,
                    'partials.pending_shipment',
                    'pending'
                ],
                [
                    'pending',
                    $pendingShipment,
                    'partials.pending_shipment',
                    'pending'
                ],
                [
                    'Confirmed',
                    $pendingShipment,
                    'partials.pending_shipment',
                    'confirmed'
                ],
                [
                    'Processing',
                    $pendingShipment,
                    'partials.pending_shipment',
                    'processing'
                ],
                [
                    'Ready for Delivery',
                    $pendingShipment,
                    'partials.pending_shipment',
                    'ready for delivery'
                ],
                [
                    'pending payment',
                    ['pending payment'],
                    'partials.pending_payment',
                    'pending payment'
                ],
                [
                    'on the way',
                    ['On the Way'],
                    'partials.on_the_way',
                    'on the way'
                ],
                [
                    'completed',
                    ['Completed'],
                    'partials.order_completed',
                    'completed'
                ],
                [
                    'returned',
                    ['Returned'],
                    'partials.returned_goods_or_refunds',
                    'returned'
                ],
                [
                    'cancelled',
                    ['Cancelled'],
                    'partials.cancelled',
                    'cancelled'
                ],
        ];
    }

    public function test_it_updates_order_status_when_order_exists()
    {
        $orderId = 1;
        $status = 'completed';

        // Create fake Order object
        $order = Mockery::mock(Order::class);

        $this->adminRepo->shouldReceive('findById')
            ->once()
            ->with($orderId)
            ->andReturn($order);

        $this->adminRepo->shouldReceive('updateStatus')
            ->once()
            ->with($order, $status);

        $this->service->adminUpdateStatus($status, $orderId);

    }

    public function test_admin_update_status_abort_when_order_not_found()
    {
        $orderId = 1;
        $status = 'completed';

    $this->adminRepo->shouldReceive('findById')
        ->once()
        ->with($orderId)
        ->andReturn(null);

    $this->expectException(HttpException::class);

    $this->service->adminUpdateStatus($status, $orderId);

    }

    public function test_it_deletes_order_when_order_exists()
    {
        $orderId = 1;
        
        // Create fake Order object
        
        $order = Mockery::mock(Order::class);

        $this->adminRepo->shouldReceive('findById')
            ->once()
            ->with($orderId)
            ->andReturn($order);

        $this->adminRepo->shouldReceive('delete')
            ->once()
            ->with($order);
    
        $this->service->adminDeleteOrder($orderId);


    }

    public function test_admin_delete_order_abort_when_order_not_found()
    {
        $orderId = 1;

    $this->adminRepo->shouldReceive('findById')
        ->once()
        ->with($orderId)
        ->andReturn(null);

    $this->expectException(HttpException::class);

    $this->service->adminDeleteOrder($orderId);

    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();

        $this->addToAssertionCount(1);

    }

}
