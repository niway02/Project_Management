@extends('layout')

@section('content')
    <div class="container my-5">
        <h1 class="text-center mb-4">Material Selection</h1>
        <form action="{{ route('materialcosts.materialSelection') }}" method="POST">
            @csrf
            <div class="row">
        @foreach($materials as $material)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $material->item }}</h5>
                        <p class="card-text">
                            Unit: {{ $material->unitMeasure->name }}<br>
                            Quantity: {{ $material->quantity }}<br>
                            Rate with VAT: {{ $material->rate_with_vat }}
                        </p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="selected_materials[]" value="{{ $material }}">
                            <label class="form-check-label">Select</label>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary">Continue</button>
    </div>
        </form>
    </div>
    <script src="{{ asset('js/material-selection.js') }}">

        // Save the selected checkboxes when the user interacts with them
$('.form-check-input').on('change', function() {
    var selectedMaterialsJSON = JSON.stringify($('.form-check-input:checked').map(function() {
        return $(this).val();
    }).get());
    sessionStorage.setItem('selectedMaterialsJSON', selectedMaterialsJSON);
});

// Restore the selected checkboxes when the user navigates back to the page
$(document).ready(function() {
    var selectedMaterialsJSON = sessionStorage.getItem('selectedMaterialsJSON');
    if (selectedMaterialsJSON) {
        var selectedMaterials = JSON.parse(selectedMaterialsJSON);
        $('.form-check-input').each(function() {
            this.checked = selectedMaterials.includes($(this).val());
        });
    }
});

window.addEventListener('popstate', function(event) {
    if (event.state && event.state.selectedMaterialsJSON) {
        var selectedMaterials = JSON.parse(event.state.selectedMaterialsJSON);
        $('.form-check-input').each(function() {
            this.checked = selectedMaterials.includes($(this).val());
        });
    }
});

// Save the selected checkboxes when the user interacts with them
$('.form-check-input').on('change', function() {
    var selectedMaterialsJSON = JSON.stringify($('.form-check-input:checked').map(function() {
        return $(this).val();
    }).get());
    history.pushState({ selectedMaterialsJSON: selectedMaterialsJSON }, document.title);
});
    </script>
@endsection