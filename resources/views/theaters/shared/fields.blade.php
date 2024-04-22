@php
 $disabledStr = $readonlyData ?? false ? 'disabled' : '';
@endphp

<div>
    <label for="inputName">Name</label>
    <input type="text" name="name" id="inputName" {{ $disabledStr }} value="{{ $theater->name }}">
</div>
<div>
    <label for="inputPhotoFilename">Photo Filename</label>
    <input type="file" name="photo_filename" id="inputPhotoFilename" {{ $disabledStr }} value="{{ $theater->photo_filename }}">
</div>

