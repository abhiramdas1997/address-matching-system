<form action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="client_data">Client Data (TXT):</label>
        <input type="file" name="client_data" required>
    </div>
    <br>
    <div>
        <label for="listing_data">Listing Data (TXT):</label>
        <input type="file" name="listing_data" required>
    </div>
    <br>
    <button type="submit">Upload</button>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>
