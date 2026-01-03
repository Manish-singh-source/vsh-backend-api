<form method="POST" action="{{ route('rekognition.detect') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" accept="image/*">
    <button type="submit">Detect Faces</button>
</form>

