// to get current year
function getYear() {
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    document.querySelector("#displayYear").innerHTML = currentYear;
}

getYear();


// client section owl carousel
$(".client_owl-carousel").owlCarousel({
    loop: true,
    margin: 20,
    dots: false,
    nav: true,
    navText: [],
    autoplay: true,
    autoplayHoverPause: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 2
        }
    }
});



/** google_map js **/
function myMap() {
    var mapProp = {
        center: new google.maps.LatLng(40.712775, -74.005973),
        zoom: 18,
    };
    var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
}

// ajax call to subsribe form
$(document).ready(function() {
    $("#subscribeForm").submit(function(e) {
        var email = $("#email").val();
        if (email == "") {
            $("#error").html("Email is required");
        } else {
            $.ajax({
                url: "/be/api/subscribe",
                type: "POST",
                data: {
                    email: email
                },
                success: function(data) {
                    $("#error").html(data);
                }
            });
        }
    });
});

$(document).ready(function() {
    $("#contact-form").submit(function(e) {
        const email = $("#email").val();
        const message = $("#message").val();
        const subject = $("#subject").val();
        const name = $("#name").val();
        const privacy = $("#privacy").is(":checked");
        if (email == "") {
            $("#error").html("Email is required");
        } else {
            $.ajax({
                url: "/api/contact/send",
                type: "POST",
                data: {
                    email: email,
                    message: message,
                    name: name,
                    subject: subject,
                    privacy: privacy
                },
                success: function(data) {
                    $("#contact-form").trigger("reset");
                    $("#error").html(data);
                }
            });
        }
    });
});

// FAQ Toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq_item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq_question');
        
        question.addEventListener('click', function() {
            const isActive = item.classList.contains('active');
            
            // Close all other FAQ items
            faqItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });
            
            // Toggle current item
            if (isActive) {
                item.classList.remove('active');
            } else {
                item.classList.add('active');
            }
        });
    });
});