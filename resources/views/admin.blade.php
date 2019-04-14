<!DOCTYPE html>
<html>
<head>
    <title>Lydia (Admin)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/resources/css/uikit.min.css" />
    <link rel="stylesheet" href="/resources/css/main.css" />
    <script src="/resources/js/uikit.min.js"></script>
    <script src="/resources/js/uikit-icons.min.js"></script>
</head>
<body>
<div class="uk-flex-center" uk-grid>
    <div class="admin-lydia">
        <h1 class="uk-heading-divider"><span>All Lydia requests</span></h1>
        <table class="uk-table uk-table-striped uk-table-hover">
            <caption></caption>
            <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($requests as $request)
            <tr>
                <td><a href="https://homologation.lydia-app.com/collect/payment/{{ $request->lydia_request_uuid  }}/auto" target="_blank">{{ $request->lydia_request_id }}</a></td>
                <td>
                    @if($request->status === \App\Enums\LydiaRequestStatuses::PAID)
                        <span class="uk-label uk-label-success">{{ $request->status }}</span>
                    @elseif($request->status === \App\Enums\LydiaRequestStatuses::INITIATED)
                        <span class="uk-label">{{ $request->status }}</span>
                    @elseif($request->status === \App\Enums\LydiaRequestStatuses::CANCELLED)
                        <span class="uk-label uk-label-danger">{{ $request->status }}</span>
                    @elseif($request->status === \App\Enums\LydiaRequestStatuses::EXPIRED )
                        <span class="uk-label uk-label-warning">{{ $request->status }}</span>
                    @endif
                </td>
                <td>{{ $request->firstname }}</td>
                <td>{{ $request->lastname }}</td>
                <td>{{ $request->email }}</td>
                <td class="uk-text-right">{{ $request->amount }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>