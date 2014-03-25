    $(document).ready(function(){  
         $(".faqanswer").hide();
         $(".faqquestion").click(function(){  
            $(this).toggleClass("active").next().slideToggle("slow");  
            return false;  
        });  
    });  