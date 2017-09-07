// JavaScript Document
jQuery(document).ready(function(){
jQuery("div.add_con").append('<input type="submit" value="+" id="add1" class="plus add" />').prepend('<input type="submit" value="-" id="minus1" class="minus add" />');
        jQuery(".plus").click(function()
        {
            var currentVal = parseInt(jQuery(this).prev(".qty").val());
            if (!currentVal || currentVal=="" || currentVal == "NaN") currentVal = 1;
          jQuery(this).prev(".qty").val(currentVal + 1);

        });
        jQuery(".minus").click(function()
       {
            var currentVal = parseInt(jQuery(this).next(".qty").val());
            if (currentVal == "NaN") currentVal = 1;
            if (currentVal > 1)
            {
                jQuery(this).next(".qty").val(currentVal - 1);
            }
        });

        });