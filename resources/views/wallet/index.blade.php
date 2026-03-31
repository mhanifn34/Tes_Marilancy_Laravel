@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3>Riwayat Transaksi Wallet</h3>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Job</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->job->title ?? 'N/A' }}</td>
                                        <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if($transaction->type == 'income')
                                                <span class="badge badge-success">Income</span>
                                            @else
                                                <span class="badge badge-danger">Expense</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center">Belum ada transaksi.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection