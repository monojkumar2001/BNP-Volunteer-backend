<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\Events;
use App\Models\News;
use App\Models\Opinion;
use App\Models\User;
use App\Models\Volunteer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalUsers = User::count();
        $newUsersCount = User::whereDate('created_at', $today)->count();

        $totalVolunteers = Volunteer::count();
        $pendingVolunteers = Volunteer::where('status', 0)->count();

        $totalOpinions = Opinion::count();
        $unreadOpinions = Opinion::where('status', 0)->count();

        $totalContacts = ContactUs::count();
        $unreadContacts = ContactUs::where('status', 0)->count();

        $totalEvents = Events::count();
        $upcomingEvents = Events::whereDate('event_date', '>=', $today)->count();

        $totalNews = News::count();
        $publishedNews = News::where('status', 1)->count();

        $latestContacts = ContactUs::latest()->take(5)->get();
        $latestOpinions = Opinion::latest()->take(5)->get();
        $latestVolunteers = Volunteer::latest()->take(5)->get();

        // Empty chart data
        $ageData = [];
        $genderData = [];
        $occupationData = [];
        $maritalStatusData = [];

        return view('dashboard', compact(
            'totalUsers',
            'newUsersCount',
            'totalVolunteers',
            'pendingVolunteers',
            'totalOpinions',
            'unreadOpinions',
            'totalContacts',
            'unreadContacts',
            'totalEvents',
            'upcomingEvents',
            'totalNews',
            'publishedNews',
            'latestContacts',
            'latestOpinions',
            'latestVolunteers',
            'ageData',
            'genderData',
            'occupationData',
            'maritalStatusData',
        ));
    }
}
