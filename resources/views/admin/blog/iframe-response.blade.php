<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Response</title>
</head>
<body>
<script>
// Отправляем сообщение родительскому окну с данными ответа
if (window.parent && window.parent.handleEditResponse) {
    window.parent.postMessage({
        type: '{{ $responseType ?? "blog-edit-response" }}',
        data: @json($jsonResponse ?? ['success' => false, 'errors' => ['Неизвестная ошибка']])
    }, '*');
}
</script>
</body>
</html>