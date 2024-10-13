<form action="/compare" method="GET">
    <label for="type">Select Matching Type:</label>
    <select name="type" id="type" onchange="this.form.submit()">
        <option value="string" {{ $type === 'string' ? 'selected' : '' }}>String Matching</option>
        <option value="fuzzy" {{ $type === 'fuzzy' ? 'selected' : '' }}>Fuzzy Matching</option>
    </select>
</form>
<a href="/export-csv">Export to CSV</a>
<table border="1">
    <thead>
        <tr>
            <th>Client Address</th>
            <th>Listing Address</th>
            <th>Match Type</th>
            <th>Similarity (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($matches as $match)
            <tr>
                <td>{{ $match['client_address'] }}</td>
                <td>{{ $match['listing_address'] }}</td>
                <td>{{ $match['match_type'] }}</td>
                <td>{{ $match['similarity'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
