<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Account ID</th>
        <th>Account</th>
        <th>AppStatus ID</th>
        <th>AppStatus Status</th>
        <th>Date Submitted</th>
        <th>Payment ID</th>
        <th>Transaction ID</th>
        <th>Promo Code</th>
        <th>Total Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($applications as $x => $app)
        @foreach($app->payments as $y => $payment)
        <tr>
            <td>{{ $app->id }}</td>
            <td>{{ $app->account_id }}</td>
            <td>{{ $app->account->account_name }}</td>
            <td>{{ $app->appStatus->id }}</td>
            <td>{{ $app->appStatus->application_status }}</td>
            <td>{{ $app->appStatus->application_submit_date }}</td>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->transaction_id }}</td>
            <td>{{ $payment->promo_code }}</td>
            <td>{{ $payment->total_amount }}</td>
            <td>{{ $payment->created_at }}</td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>