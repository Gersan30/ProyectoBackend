<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
</head>
<body>
    <h1>Search Results</h1>
    @if(isset($error))
        <p>Error: {{ $error }}</p>
    @elseif(!empty($results))
        <p>Google Shopping URL: <a href="{{ $results['google_shopping_url'] }}">{{ $results['google_shopping_url'] }}</a></p>
        <p>La Casa del Electrodoméstico Product ID: {{ $results['product_id'] }}</p>
    @else
        <p>No results found.</p>
    @endif
</body>
</html>

