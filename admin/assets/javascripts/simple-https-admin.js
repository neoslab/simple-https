(function($)
{
	'use strict';
    $(function()
    {
        if($('.input-status').length > 0)
        {
            $('.input-status').on('click',function()
            {
                if($(this).is(':checked'))
    		    {
    		       	$("#handler-maintenance").show(100);
    		    }
    		    else
    		    {
    		    	$("#handler-maintenance").hide(500);
    		    }
    		});

    		if($("#handler-maintenance.show").length)
    		{
    		    $("#handler-maintenance").show();
    		}
        }
    });
})(jQuery);