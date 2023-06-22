use App\Models\Order;
use ConsoleTVs\Charts\Facades\Charts;
use Carbon\Carbon;

public function index()
{
    $monthlyEarnings = Order::selectRaw('SUM(total_price) as earnings, DATE_FORMAT(created_at, "%Y-%m") as month')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('earnings', 'month');

    $monthlyOrderCounts = Order::selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m") as month')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month');

    // Create the earnings chart
    $earningsChart = Charts::database($monthlyEarnings, 'bar', 'highcharts')
        ->title('Monthly Earnings')
        ->elementLabel('Earnings')
        ->dimensions(800, 400)
        ->responsive(true)
        ->groupByMonth();

    // Create the order count chart
    $orderCountChart = Charts::database($monthlyOrderCounts, 'bar', 'highcharts')
        ->title('Monthly Order Counts')
        ->elementLabel('Order Count')
        ->dimensions(800, 400)
        ->responsive(true)
        ->groupByMonth();

    return view('statistics.index', compact('earningsChart', 'orderCountChart'));
}