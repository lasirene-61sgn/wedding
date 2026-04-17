@extends('layouts.host')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm mx-auto border-0" style="max-width: 600px;">
        <div class="card-header bg-secondary text-white py-3">
            <h4 class="mb-0">Edit Save the Date</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('host.savedate.update', $savedate->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Link to Invitation</label>
                    <select name="invitation_id" class="form-select @error('invitation_id') is-invalid @enderror" required>
                        @foreach(\App\Models\Invitation::where('host_id', Auth::id())->get() as $inv)
                            <option value="{{ $inv->id }}" {{ $savedate->invitation_id == $inv->id ? 'selected' : '' }}>
                                {{ $inv->bride_name }} & {{ $inv->groom_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Change Message Template</label>
                    <select id="template_selector" class="form-select mb-2">
                        <option value="">-- Choose a template to replace current --</option>
                        <option value="We can't wait to celebrate our special day with you! Please save the date.">Warm & Classic</option>
                        <option value="Save the date! We're getting married and we want you there to celebrate with us.">Modern & Fun</option>
                        <option value="A beautiful journey is about to begin. Please mark your calendars for our wedding.">Elegant & Formal</option>
                        <option value="custom">-- Clear message --</option>
                    </select>
                    
                    <label class="form-label">Message</label>
                    <textarea name="message" id="message_box" class="form-control @error('message') is-invalid @enderror" rows="3">{{ old('message', $savedate->message) }}</textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label d-block fw-bold">Current Image</label>
                    <img src="{{ asset('storage/' . $savedate->image) }}" class="img-thumbnail mb-3 shadow-sm" style="max-height: 150px;">
                    
                    <label class="form-label d-block fw-bold">Replace Image (Optional)</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                    <small class="text-muted">Leave empty to keep the current image.</small>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">Update Details</button>
                    <a href="{{ route('host.savedate.index') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('template_selector').addEventListener('change', function() {
        const messageBox = document.getElementById('message_box');
        if (this.value === 'custom') {
            messageBox.value = '';
        } else if (this.value !== '') {
            messageBox.value = this.value;
        }
    });
</script>
@endsection