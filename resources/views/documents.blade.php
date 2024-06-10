<!-- resources/views/documents.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài liệu</title>
</head>
<body>
    <h1>Tài liệu</h1>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <table border="1">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Ký</th>
                <th>Thông tin</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $document)
                <tr>
                    <td>{{ $document->title }}</td>
                    <td>@if ($document->is_signature) <p>Đã ký</p> @endif</td>
                    <td>@foreach($document->users as $user)
                        <li>{{ $user->id }} - {{ $user->name }} - {{ $user->created_at->format('H:i:s d-m-Y') }}</li>
                    @endforeach</td>
                    <td>
                        <a href="{{ route('documents.sign', $document->id) }}">Ký</a> |
                        <a href="{{ route('documents.view', ['id' => $document->id, 'path0' => 0]) }}">Xem</a> |
                        <a href="{{ route('documents.view', ['id' => $document->id, 'path0' => 1]) }}">Xem bản gốc</a>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
