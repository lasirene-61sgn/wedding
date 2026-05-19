// ===== LIVE PREVIEW FUNCTION =====
    function refreshPreview() {
        // Update names
        $('#p_bride').text($('input[name="bride_name"]').val() || "Bride");
        $('#p_groom').text($('input[name="groom_name"]').val() || "Groom");
        
        // Format and update date/time
        let rawDate = $('input[name="wedding_date"]').val();
        let formattedDate = rawDate ? new Date(rawDate).toLocaleDateString('en-GB').split('/').join('/') : "--/--/----";
        $('#p_date').text("📅 Date: " + formattedDate);
        $('#p_time').text("🕐 Time: " + ($('input[name="wedding_time"]').val() || "--:--"));
        
        // Update theme
        let theme = $('#theme_selector').val();
        $('#invitation_card').removeClass('theme-classic theme-royal theme-floral').addClass('theme-' + theme);

        // Update venue details
        let opt = $('#venue_dropdown option:selected');
        if($('#venue_dropdown').val()) {
            $('#p_v_name').text(opt.data('name'));
            $('#p_v_area').text(opt.data('area'));
            $('#p_v_landmark').text(opt.data('landmark') ? '(' + opt.data('landmark') + ')' : '');
            $('#p_v_address').text(opt.data('addr'));
            $('#p_v_district').text(opt.data('district'));
            $('#p_v_state').text(opt.data('state'));
            $('#p_v_pin').text(opt.data('pin'));
            
            if(opt.data('map')) {
                $('#p_v_map').attr('href', opt.data('map')).show();
            } else {
                $('#p_v_map').hide();
            }
            
            $('#p_venue_placeholder').hide();
            $('#p_venue_box').fadeIn(300);
        } else {
            $('#p_venue_box').hide();
            $('#p_venue_placeholder').show();
        }
    }

    // Attach live preview to all inputs
    $('.watch-input, #theme_selector, #venue_dropdown').on('input change', refreshPreview);

    // ===== SWAP LOGIC FOR INVITER =====
    function applySwap(val) {
        let brideSection = $('#bride_card_container');
        let groomSection = $('#groom_card_container');
        if(val.includes('groom')) {
            groomSection.insertBefore(brideSection);
        } else {
            brideSection.insertBefore(groomSection);
        }
    }
    applySwap($('#invite_dropdown').val());
    $('#invite_dropdown').on('change', function() { 
        applySwap($(this).val()); 
        refreshPreview(); 
    });

    // ===== PINCODE API LOGIC =====
    function fetchPin(pin, prefix, callback = null) {
        if(pin.length === 6) {
            $.getJSON(`https://api.postalpincode.in/pincode/${pin}`, function(data) {
                if(data[0].Status === "Success") {
                    let res = data[0].PostOffice;
                    $(`#${prefix}_district`).val(res[0].District);
                    $(`#${prefix}_state`).val(res[0].State);
                    $(`#${prefix}_circle`).val(res[0].Circle);
                    $(`#${prefix}_area`).empty();
                    res.forEach(o => $(`#${prefix}_area`).append(`<option value="${o.Name}">${o.Name}</option>`));
                    if(callback) callback();
                }
            });
        }
    }
    $('#q_v_pin').on('keyup', function() { fetchPin($(this).val(), 'q_v'); });

    // ===== MODAL: ADD VENUE =====
    $('#btn_add_venue').click(function() {
        $('#venueForm')[0].reset();
        $('#q_v_id').val('');
        $('#modalTitle').text('Add New Venue');
        $('#q_v_area').empty();
        $('#venueModal').modal('show');
    });

    // ===== MODAL: EDIT VENUE =====
    $('#btn_edit_venue').click(function() {
        let selected = $('#venue_dropdown option:selected');
        let id = selected.val();
        if(!id) { alert('Please select a venue to edit'); return; }

        $('#q_v_id').val(id);
        $('#q_v_name').val(selected.data('name'));
        $('#q_v_pin').val(selected.data('pin'));
        $('#q_v_addr').val(selected.data('addr'));
        $('#q_v_wedding_location').val(selected.data('landmark'));
        $('#q_v_location_map').val(selected.data('map'));
        $('#modalTitle').text('Edit Venue');

        fetchPin(selected.data('pin').toString(), 'q_v', function() {
            $('#q_v_area').val(selected.data('area'));
        });

        $('#venueModal').modal('show');
    });

    // ===== AJAX: SAVE VENUE (Create or Update) =====
    $('#venueForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#q_v_id').val();
        let url = id ? `/host/venue/${id}` : "{{ route('host.venue.store') }}";
        let method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: method,
                venue_name: $('#q_v_name').val(),
                pincode: $('#q_v_pin').val(),
                area_name: $('#q_v_area').val(),
                district: $('#q_v_district').val(),
                state: $('#q_v_state').val(),
                circle: $('#q_v_circle').val(),
                country: $('#q_v_country').val(),
                venue_address: $('#q_v_addr').val(),
                wedding_location: $('#q_v_wedding_location').val(),
                location_map: $('#q_v_location_map').val()
            },
            success: function(res) {
                if(id) {
                    // Update existing option
                    let opt = $(`#venue_dropdown option[value="${id}"]`);
                    opt.text(res.venue_name);
                    opt.data('name', res.venue_name)
                       .data('pin', res.pincode)
                       .data('area', res.area_name)
                       .data('addr', res.venue_address)
                       .data('landmark', res.wedding_location)
                       .data('map', res.location_map);
                } else {
                    // Append new option
                    $('#venue_dropdown').append(`<option value="${res.id}" 
                        data-name="${res.venue_name}" data-pin="${res.pincode}" 
                        data-area="${res.area_name}" data-addr="${res.venue_address}"
                        data-landmark="${res.wedding_location}" data-map="${res.location_map}" 
                        selected>${res.venue_name}</option>`);
                }
                $('#venueModal').modal('hide');
                refreshPreview(); // Update preview with new venue
            },
            error: function() { alert('Error saving venue. Please check your data.'); }
        });
    });

    // ===== INITIALIZE PREVIEW ON LOAD =====
    $(document).ready(function() {
        refreshPreview();
    });
