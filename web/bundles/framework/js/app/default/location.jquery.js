jQuery( document ).ready(function() {

    var country = $("#country");
    var region = $("#region");
    var city = $("#city");
    var alt_city = $("#alt_city");

    $('#alt_city_div').fadeOut();

    // console.log('Document ready');
    // console.log('Country name ('+country.attr('name')+')');
    // console.log('Country value ('+country.val()+')');
    // console.log('Regiom value ('+region.val()+')');
    // console.log('City value ('+city.val()+')');
    if ( country.val() == '' )
    {
        //console.log('Country ('+country.val()+')');
        $("option", region).remove();
        region.append('<option value="">'+l_select_country+' ...</option>');
        $(region).attr("disabled", "disabled");
        $("option", city).remove();
        $(region).attr("disabled", "disabled");
        $("option", city).remove();
        city.append('<option value="">'+l_select_country+' ...</option>');
        $(city).attr("disabled", "disabled");
    }
    else
    {
        if ( region.val() == '' )
        {
            $("option", city).remove();
            $(city).attr("disabled", "disabled");
        }
        else
        {
            if ( city.val() == "-")
            {
                $('#alt_city_div').fadeIn();
                //$(alt_city).attr("required", "required"); // We don't use HTML5 validations anymore
            }
        }
    }

});

$(function() {

	$(country).change(function(event) {
        $(region).removeAttr('disabled');
        $(region).empty().append($('<option>', {
            value: '',
            text: l_working
        }));
        $(region).attr("disabled", "disabled");
        if ( $(city).length ) {
            $(city).removeAttr('disabled');
            $(city).empty();
            $(city).append('<option value="">'+l_select_region+' ...</option>');
            $(city).attr("disabled", "disabled");
            //$('#alt_city_div').fadeOut();
        }
		//console.log('url_to_call regions', url_regions_call, 'country 2a', $(country).val(), 'lang', user_locale,'api_key', api_key);
        var api_data = {data: {'country_code_2a': $(country).val(),'lang':user_locale,'api_key':api_key}};
		$.ajax({
            type: 'post',
			url: url_regions_call,
            data: JSON.stringify(api_data),
            //contentType: "application/json",
            dataType: 'json',
            //traditional: true,
            beforeSend: function(){
                //$('#submit_profile').attr("disabled","disabled");
                //$('#profileForm').css("opacity",".5");
            },
        })
			.done(function(regions) {
			    //console.log(regions);
                if( regions.status == 'OK' )
                {
                    // console.log('Regions is OK');
                    loadSelectLocation( region, regions );
                }
                else if( regions.status == 'KO' )
                {
                    // console.log('Regions is KO');
                    $(region).empty().append('<option value="">'+regions.msg+'</option>');
                }
                else
                {
                    // console.log('Regions is NOT working, status != OK or KO');
                    $(region).empty().append('<option value="">'+l_general_error+'</option>');
                }
                $(region).removeAttr('disabled');
			});
	});

	$(region).change(function(event) {
        $(city).removeAttr('disabled');
        $(city).empty().append($('<option>', {
            value: '',
            text: l_working
        }));
        $(city).attr("disabled", "disabled");
        //console.log('url_to_call cities', url_cities_call, 'country 2a', $(country).val(),'region_code', $(region).val(), 'lang', user_locale,'api_key', api_key);
        var api_data = {data: {'country_code_2a': $(country).val(),'region_code': $(region).val(),'lang':user_locale,'api_key':api_key}};
        $.ajax({
                type: 'post',
                url: url_cities_call,
                data: JSON.stringify(api_data),
                dataType: 'json',
                beforeSend: function(){
                    //$('#submit_profile').attr("disabled","disabled");
                    //$('#profileForm').css("opacity",".5");
                },
            })
            .done(function (cities) {
                //console.log(cities);
                if( cities.status == 'OK' )
                {
                    // console.log('Cities is OK');
                    loadSelectLocation( city, cities );
                }
                else if( cities.status == 'KO' )
                {
                    // console.log('Cities is KO');
                    $(city).empty().append('<option value="">'+cities.msg+'</option>');
                }
                else
                {
                    // console.log('Cities is NOT working, status != OK or KO');
                    $(cities).empty().append('<option value="">'+l_general_error+'</option>');
                }
                $(city).removeAttr('disabled');
            })
    });

	// Show alternative city id it is the case.
	$(city).change(function(event) {
		if ($("option:selected", this).val() == "-") {
            $('#alt_city_div').fadeIn();
            //$(alt_city).attr("required", "required"); // We don't use HTML5 validations anymore
		} else {
            $('#alt_city_div').fadeOut();
            //$(alt_city).removeAttr('required'); // We don't use HTML5 validations anymore
		}
	});

    $(this).submit(function (event) {
        if ( $(city).val() != "-" ) {
            $(alt_city).val('');
        }
    });

});

function loadSelectLocation(objeto, data)
{
	var miselect = $(objeto);
	miselect.find('option').remove();
	miselect.empty();

    if( miselect.attr('name').indexOf("region") != -1){
        miselect.append('<option value="">'+l_select_region+'</option>');
        miselect.append('<option disabled>'+'&#x2500;'.repeat(16)+'</option>');
    }
    if( miselect.attr('name').indexOf("city") != -1){
        miselect.append('<option value="0">'+l_select_city+'</option>');
        miselect.append('<option disabled>'+'&#x2500;'.repeat(16)+'</option>');
        miselect.append('<option value="-">'+l_not_in_list+ '</option>');
        miselect.append('<option disabled>'+'&#x2500;'.repeat(16)+'</option>');
    }

    var temp = [];
    $.each( data.msg, function( index, value ){
        // console.log('Key ('+index+')'+'Value ('+value.name+')');
        temp.push({k:index, v:value.name});
    });
    temp.sort(function(a,b){
        if(a.v > b.v){ return 1}
        if(a.v < b.v){ return -1}
        return 0;
    });
    $.each(temp, function(key, obj) {
        miselect.append($("<option></option>")
            .attr("value", obj.k).text(obj.v));
    });
}
