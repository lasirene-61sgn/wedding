@extends('layouts.host')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm mx-auto border-0" style="max-width: 600px;">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0">Create Save the Date</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('host.savedate.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Link to Invitation (For Countdown)</label>
                    <select name="invitation_id" class="form-select @error('invitation_id') is-invalid @enderror" required>
                        <option value="">Select an Invitation</option>
                        @foreach($invitations as $invitation)
                            <option value="{{ $invitation->id }}" {{ old('invitation_id') == $invitation->id ? 'selected' : '' }}>
                                {{ $invitation->bride_name }} & {{ $invitation->groom_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('invitation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Select a Message Template</label>
                    <select id="template_selector" class="form-select mb-2">
                        <option value="">-- Choose a template --</option>
                        <option value="We can't wait to celebrate our special day with you! Please save the date.">Warm & Classic</option>
                        <option value="Save the date! We're getting married and we want you there to celebrate with us.">Modern & Fun</option>
                        <option value="A beautiful journey is about to begin. Please mark your calendars for our wedding.">Elegant & Formal</option>
                        <option value="custom">-- Write my own message --</option>
                    </select>
                    
                    <label class="form-label">Final Message</label>
                    <textarea name="message" id="message_box" class="form-control @error('message') is-invalid @enderror" rows="3" placeholder="Select above or type here...">{{ old('message') }}</textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Upload Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" required>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Save the Date</button>
                    <a href="{{ route('host.savedate.index') }}" class="btn btn-light">Back</a>
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
            messageBox.focus();
        } else if (this.value !== '') {
            messageBox.value = this.value;
        }
    });
</script>
@endsection