jQuery( document ).ready(function()
{
    //*********************************************************************
    //**** Account group ***********************************************
    //*********************************************************************
    // if adding user show account's group select
    if ( $("#account_id").val() === '0' )
    {
        $("#account_fields_div").css("display", "block");
    }
    else
    {
        $("#account_fields_div").css('display', 'none');
    }

});

$(function()
{
    //*********************************************************************
    //**** Account change ***********************************************
    //*********************************************************************
    $("#account_id").on( 'change', function() {
        if ( $("#account_id").val() === '0' )
        {
            //console.log('New account');
            $("#account_fields_div").css('display', 'block');
        }
        else
        {
            $("#account_fields_div").css('display', 'none');
        }
    });

    //*********************************************************************
    //**** DOB Change ***********************************************
    //*********************************************************************
    // $("#user_profile_dob").change(function()
    // {
    //     if ( $('#user_profile_dob').val() != '' )
    //     {
    //         // Age calculations
    //         var today = new Date();
    //         var birthDate = new Date($('#user_profile_dob').val());
    //         var age = today.getFullYear() - birthDate.getFullYear();
    //         var m = today.getMonth() - birthDate.getMonth();
    //         if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
    //             age--;
    //         }
    //         $('#age').val(age);
    //         $('#age_display').html(age+' '+l_age);
    //         $('#age_display').show();
    //     }
    //     //if user is a student and is minor show tutor's tab
    //     if ( $("#user_group").val() == '4' && age < 18 )
    //     {
    //         $("#tutor-tab").css("display", "block");
    //     }
    //     else
    //     {
    //         $("#tutor-tab").css('display', 'none');
    //     }
    // });

});