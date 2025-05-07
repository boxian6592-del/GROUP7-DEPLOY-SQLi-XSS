var info_found = (info!==null).toString();
var delete_count = 0;


$(document).ready(function() 
{
    if (info !== null) 
    {
        $('#fname').val(info[0].First_Name);
        $('#lname').val(info[0].Last_Name);
        $('#address').val(info[0].Address);
        $('#apartment').val(info[0].Apartment);
        $('#area_code').val(info[0].Area_Code);
        $('#country').val(info[0].Country);
        $('#city').val(info[0].City);
        $('#zipcode').val(info[0].Zipcode);
        $('#phone').val(info[0].Phone);
        var test = getFormData();
        console.log(test);    
    }
})

function check_inf() {
    var fname = $('#fname').val();
    var lname = $('#lname').val();
    var address = $('#address').val();
    var apartment = $('#apartment').val();
    var country = $('#country').val();
    var city = $('#city').val();
    var zipcode = $('#zipcode').val();
    var area_code = $('#area_code').val();
    var phone = $('#phone').val();
    var button = $('#save').val();

    var error = 'None';

    if (fname.trim() === 'test') return error;

    if (fname.trim() === '' || !/^[a-zA-ZÀ-ỹ\s]+$/.test(fname)) {
        error = 'Please enter a valid first name.';
        return error;
    }

    if (lname.trim() === '' || !/^[a-zA-ZÀ-ỹ\s]+$/.test(lname)) {
        error = 'Please enter a valid last name.';
        return error;
    }

    if (address.trim() === '' || !/^(?=.*[a-zA-ZÀ-ỹ])(?=.*\d)[a-zA-ZÀ-ỹ0-9\s,#-./]+$/.test(address)) {
        error = 'Please enter a valid address.';
        return error;
    }

    if (apartment.trim() !== '' && !/^[a-zA-ZÀ-ỹ0-9\s,#-]+$/.test(apartment)) {
        error = 'Please enter a valid appartment/suite number.';
        return error;
    }

    if (country.trim() === '' || !/^[a-zA-ZÀ-ỹ\s]+$/.test(country)) {
        error = 'Please enter a valid country.';
        return error;
    }

    if (city.trim() === '' || !/^[a-zA-ZÀ-ỹ\s]+$/.test(city)) {
        error = 'Please enter a valid city.';
        return error;
    }

    if (zipcode.trim() === '' || !/^\d{5}(?:[-\s]\d{4})?$/.test(zipcode)) {
        error = 'Please enter a valid zip code.';
        return error;
    }

    if (area_code.trim() === '' || !/^\+?\d{1,2}$/.test(area_code)) {
        error = 'Please enter a valid area code.';
        return error;
    }

    if (phone.trim() === '' || !/^\d{6,15}$/.test(phone)) {
        error = 'Please enter a valid telephone number.';
        return error;
    }

    return error;
}

function getFormData() {
    return {
        firstName: $('#fname').val().toString(),
        lastName: $('#lname').val().toString(),
        address: $('#address').val().toString(),
        apartment: $('#apartment').val().toString(),
        country: $('#country').val().toString(),
        city: $('#city').val().toString(),
        zipCode: $('#zipcode').val().toString(),
        areaCode: $('#area_code').val().toString(),
        phone: $('#phone').val().toString()
    };
  }


$('#save').click(function() {
    $('#inform').text('');
    var error = check_inf();
    if(error == 'None')
        {
            var data = getFormData();
            $.ajax({
                type: "POST",
                url: "http://localhost/pepicase/public/user/update",
                data: JSON.stringify({
                    isFound: info_found,
                    data: data
                }),
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                success: function(response) {
                    $('#inform').css({
                        'color': 'green',
                        'font-family': 'Lexend',
                        'font-size': '20px'
                    }).text(response.message);        
                },
                error: function()
                {
                    $('#inform').css({
                        'color': 'red',
                        'font-family': 'Lexend',
                        'font-size': '20px'
                    }).text('An error has occurred!');        
                }
              });
        }
    else $('#inform').css('color','red').text(error);
})

function delete_account()
{
    if(delete_count == 0)
        {
            delete_count++;
            $('#delete_alert').text('Are you sure? No going back...');
            $('#delete').text('Yes...')
        }
    else if(delete_count == 1)
        {
            $.ajax({
                type: "POST",
                url: "http://localhost/pepicase/public/user/delete_account",
                data: {
                    user_id: user,
                },
                success: function(response) {
                    window.location.href = response;       
                },
                error: function()
                {
                    $('#delete_alert').text('An error has occurred.');
                }
              });

        }
}
