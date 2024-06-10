<!-- resources/views/upload_form.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tải file signature PDF</title>
</head>
<body>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <form action="{{ route('upload_sig') }}" method="post" enctype="multipart/form-data">
        @csrf

        <label for="pdf_file">Chọn file PDF:</label>
        <input type="file" name="pdf_file" id="pdf_file">
        <br><br>
        <button type="submit">Upload file PDF</button>
    </form>
</body>
</html>
