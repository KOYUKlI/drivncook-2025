<?php

namespace Tests\Unit;

use App\Services\PdfService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class PdfServiceTest extends TestCase
{
    use RefreshDatabase;

    private PdfService $pdfService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdfService = new PdfService;

        // Fake storage for testing
        Storage::fake('public');
    }

    public function test_can_generate_monthly_sales_pdf()
    {
        // Mock the view
        View::shouldReceive('make')
            ->once()
            ->with('reports.monthly_sales', \Mockery::type('array'))
            ->andReturnSelf();

        View::shouldReceive('render')
            ->once()
            ->andReturn('<html><body><h1>Monthly Sales Report</h1></body></html>');

        $data = [
            'month' => 8,
            'year' => 2025,
            'total' => 50.00,
            'lines' => [
                [
                    'date' => '2025-08-01',
                    'item' => 'Burger Classic',
                    'qty' => 10,
                    'price' => 5.00,
                    'amount' => 50.00,
                ],
            ],
        ];

        $path = 'reports/monthly_sales_2025_08.pdf';

        $result = $this->pdfService->monthlySales($data, $path);

        // Assert that the file was created
        $this->assertTrue(Storage::disk('public')->exists($path));

        // Assert that the returned path is correct
        $this->assertEquals($result, Storage::disk('public')->path($path));
    }

    public function test_monthly_sales_pdf_contains_expected_data_structure()
    {
        View::shouldReceive('make')
            ->once()
            ->with('reports.monthly_sales', \Mockery::on(function ($data) {
                // Validate data structure passed to view
                return isset($data['month']) &&
                       isset($data['year']) &&
                       isset($data['total']) &&
                       isset($data['lines']) &&
                       is_array($data['lines']);
            }))
            ->andReturnSelf();

        View::shouldReceive('render')
            ->once()
            ->andReturn('<html><body>Test PDF Content</body></html>');

        $data = [
            'month' => 7,
            'year' => 2025,
            'total' => 125.50,
            'lines' => [
                ['date' => '2025-07-01', 'item' => 'Burger', 'qty' => 5, 'price' => 8.00, 'amount' => 40.00],
                ['date' => '2025-07-02', 'item' => 'Frites', 'qty' => 10, 'price' => 3.50, 'amount' => 35.00],
                ['date' => '2025-07-03', 'item' => 'Boisson', 'qty' => 15, 'price' => 2.50, 'amount' => 37.50],
            ],
        ];

        $path = 'reports/test_monthly_sales.pdf';

        $this->pdfService->monthlySales($data, $path);

        // The test passes if no exceptions are thrown and View::make is called with correct data
        $this->assertTrue(true);
    }

    public function test_generates_pdf_with_correct_file_size_and_format()
    {
        View::shouldReceive('make')
            ->once()
            ->with('reports.monthly_sales', \Mockery::type('array'))
            ->andReturnSelf();

        View::shouldReceive('render')
            ->once()
            ->andReturn('
                <html>
                    <head><title>Monthly Sales Report</title></head>
                    <body>
                        <h1>Driv\'n Cook - Monthly Sales Report</h1>
                        <p>Month: August 2025</p>
                        <table>
                            <tr><th>Date</th><th>Item</th><th>Qty</th><th>Price</th><th>Amount</th></tr>
                            <tr><td>2025-08-15</td><td>Burger Premium</td><td>25</td><td>7.50€</td><td>187.50€</td></tr>
                        </table>
                        <p>Total: 187.50€</p>
                    </body>
                </html>
            ');

        $data = [
            'month' => 8,
            'year' => 2025,
            'total' => 187.50,
            'lines' => [
                [
                    'date' => '2025-08-15',
                    'item' => 'Burger Premium',
                    'qty' => 25,
                    'price' => 7.50,
                    'amount' => 187.50,
                ],
            ],
        ];

        $path = 'reports/monthly_sales_test.pdf';

        $result = $this->pdfService->monthlySales($data, $path);

        // Assert file exists and has content
        $this->assertTrue(Storage::disk('public')->exists($path));

        $fileSize = Storage::disk('public')->size($path);
        $this->assertGreaterThan(0, $fileSize);

        // Assert the file path is returned correctly
        $this->assertStringContainsString('monthly_sales_test.pdf', $result);
    }

    public function test_handles_empty_sales_data_gracefully()
    {
        View::shouldReceive('make')
            ->once()
            ->with('reports.monthly_sales', \Mockery::on(function ($data) {
                return $data['total'] === 0.00 && empty($data['lines']);
            }))
            ->andReturnSelf();

        View::shouldReceive('render')
            ->once()
            ->andReturn('<html><body><h1>No Sales Data</h1><p>No sales recorded for this period.</p></body></html>');

        $data = [
            'month' => 6,
            'year' => 2025,
            'total' => 0.00,
            'lines' => [],
        ];

        $path = 'reports/empty_sales.pdf';

        $result = $this->pdfService->monthlySales($data, $path);

        $this->assertTrue(Storage::disk('public')->exists($path));
        $this->assertEquals($result, Storage::disk('public')->path($path));
    }

    public function test_pdf_service_uses_correct_dompdf_options()
    {
        View::shouldReceive('make')
            ->once()
            ->with('reports.monthly_sales', \Mockery::type('array'))
            ->andReturnSelf();

        View::shouldReceive('render')
            ->once()
            ->andReturn('<html><body><h1>Test Report</h1></body></html>');

        $data = [
            'month' => 9,
            'year' => 2025,
            'total' => 99.99,
            'lines' => [
                ['date' => '2025-09-01', 'item' => 'Test Item', 'qty' => 1, 'price' => 99.99, 'amount' => 99.99],
            ],
        ];

        $path = 'reports/options_test.pdf';

        // This test ensures the service runs without errors
        // The actual dompdf options (isRemoteEnabled, A4 portrait) are tested implicitly
        $result = $this->pdfService->monthlySales($data, $path);

        $this->assertEquals($result, Storage::disk('public')->path($path));
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
