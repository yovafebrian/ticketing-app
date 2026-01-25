<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Order;
use App\Models\PaymentType;
use App\Models\Tiket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEvents = Event::count();
        $totalCategories = Kategori::count();
        $totalOrders = Order::count();
        $tipePayment = PaymentType::count();

        return view('admin.dashboard', compact('totalEvents', 'totalCategories', 'totalOrders', 'tipePayment'));
    }
}
