function sample(){
    var rent_id= jQuery('#rent_id').val();
    var amount= jQuery('#amount').val();
    var total = jQuery('#total').val();
    console.log('rent',rent_id);
    console.log('amount',amount);
    console.log('total',total);
    
    jQuery.ajax({
        type:'POST',
        url:'payment_process.php',
        data:"amount=" + amount + '&rent_id='+rent_id+'&total='+total,
        success:function(result){
            var options = {
                "key": "rzp_test_jOgzwF2bu1fdVA", // Enter the Key ID generated from the Dashboard
                "amount": amount*100, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                "currency": "INR",
                "name": "Acme Corp",
                "description": "Test Transaction",
                "image": "https://example.com/your_logo",
                "handler": function (response){
                    jQuery.ajax({
                        type:'POST',
                        url:'payment_process.php',
                        data:'payment_id='+response.razorpay_payment_id,
                        success:function(result){
                            window.location.href='../index.php'
                        }
                    });
                }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    }
})
}
