<x-app-layout>
    <style>
        .pur {
            width: 100%;
            margin-bottom: 8px;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-9">
        <div class="container">
            <div class="seller-dash-nav mb-4">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ \Route::currentRouteName() == 'seller.dashboard' ? 'active' :'' }}" href="{{ route('seller.dashboard') }}">Seller Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ \Route::currentRouteName() == 'dashboard' ? 'active' :'' }}" href="{{ route('dashboard') }}">User Dashboard</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-3">
                    <x-dashboard-side-bar />
                </div>
                <div class="col-9">
                @if (session('success'))
                <h4 class="text-center text-primary mt-3">
                    {{session('success')}}
                </h4>
                @endif
                @if (session('error'))
                <h4 class="text-center text-danger mt-3">
                    {{session('error')}}
                </h4>
                @endif
                <div class="card">
                    <div class="card-header">Transaction History</div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $key=> $transaction)
                                    <tr>
                                        <th>{{ $key+1 }}</th>
                                        <th>$ {{ number_format($transaction->amount/100, 2, ".", ",") }}</th>
                                        <th>{{ $transaction->type == 'add' ? ( $transaction->sale_type == 0 ? "Product Sale" : ($transaction->sale_type == 1 ? "Service Sale" : "Course Sale")) : 'Withdrawn' }}</th>
                                        <th>{{ $transaction->created_at->format('M d, Y') }}</th>
                                    </tr>                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .blance-title{
            color: rgb(1, 119, 189);
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</x-app-layout>
