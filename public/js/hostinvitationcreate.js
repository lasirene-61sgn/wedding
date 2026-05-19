function refreshPreview() {
        $('#p_bride').text($('input[name="bride_name"]').val() || "Bride");
        $('#p_groom').text($('input[name="groom_name"]').val() || "Groom");
        $('#p_date').text("📅 Date: " + ($('input[name="wedding_date"]').val() || "--/--/----"));
        $('#p_time').text("🕐 Time: " + ($('input[name="wedding_time"]').val() || "--:--"));
        
        let theme = $('#theme_selector').val();
        $('#invitation_card').removeClass('theme-classic theme-royal theme-floral').addClass('theme-' + theme);

        // Venue Detailed Preview
        let opt = $('#venue_dropdown option:selected');
        if($('#venue_dropdown').val()) {
            $('#p_v_name').text(opt.data('name'));
            $('#p_v_area').text(opt.data('area'));
            $('#p_v_landmark').text(opt.data('landmark') ? '(' + opt.data('landmark') + ')' : '');
            $('#p_v_address').text(opt.data('address'));
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

    $('.watch-input, #theme_selector, #venue_dropdown').on('input change', refreshPreview);

    $('#invite_dropdown').on('change', function() {
        let inviter = $(this).val();
        if(inviter === 'groomparents' || inviter === 'groom') {
            $('#groom_card_container').insertBefore('#bride_card_container');
        } else {
            $('#bride_card_container').insertBefore('#groom_card_container');
        }
        refreshPreview();
    });

    // Secure Pincode Fetch (HTTPS)
    $('#q_v_pin').on('keyup', function() {
        let pin = $(this).val();
        if(pin.length === 6) {
            $.getJSON(`https://api.postalpincode.in/pincode/${pin}`, function(data) {
                if(data[0].Status === "Success") {
                    let post = data[0].PostOffice;
                    $('#q_v_district').val(post[0].District);
                    $('#q_v_state').val(post[0].State);
                    $('#q_v_circle').val(post[0].Circle);
                    $('#q_v_area').empty();
                    post.forEach(o => $('#q_v_area').append(`<option value="${o.Name}">${o.Name}</option>`));
                }
            });
        }
    });

    // AJAX Venue Store (Forcing Secure Route)
    $('#quickVenueForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: window.LaravelRoutes.hostVenueStore,
            method: "POST",
            data: {
                _token: window.LaravelRoutes.csrfToken || $('input[name="_token"]').val(),
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
                let newOpt = `<option value="${res.id}" selected 
                    data-name="${res.venue_name}" data-pin="${res.pincode}" 
                    data-area="${res.area_name}" data-district="${res.district}" 
                    data-state="${res.state}" data-landmark="${res.wedding_location}" 
                    data-address="${res.venue_address}" data-map="${res.location_map}">
                    ${res.venue_name}
                </option>`;
                $('#venue_dropdown').append(newOpt);
                $('#addVenueModal').modal('hide');
                $('#quickVenueForm')[0].reset();
                refreshPreview();
            }
        });
    });

    // Initialize preview
    $(document).ready(function() {
        refreshPreview();
    });