(function(a){activityReply={init:function(){a(document).on("click",".row-actions a.reply",activityReply.open);a(document).on("click","#bp-activities-container a.cancel",activityReply.close);a(document).on("click","#bp-activities-container a.save",activityReply.send);a(document).on("keyup","#bp-activities:visible",function(b){if(27==b.which){activityReply.close()}})},open:function(c){var b=a("#bp-activities-container").hide();a(this).parents("tr").after(b);b.fadeIn("300");a("#bp-activities").focus();return false},close:function(b){a("#bp-activities-container").fadeOut("200",function(){a("#bp-activities").val("").blur();a("#bp-replysubmit .error").html("").hide();a("#bp-replysubmit .waiting").hide()});return false},send:function(c){a("#bp-replysubmit .error").hide();a("#bp-replysubmit .waiting").show();var b={};b["_ajax_nonce-bp-activity-admin-reply"]=a('#bp-activities-container input[name="_ajax_nonce-bp-activity-admin-reply"]').val();b.action="bp-activity-admin-reply";b.content=a("#bp-activities").val();b.parent_id=a("#bp-activities-container").prev().data("parent_id");b.root_id=a("#bp-activities-container").prev().data("root_id");a.ajax({data:b,type:"POST",url:ajaxurl,error:function(d){activityReply.error(d)},success:function(d){activityReply.show(d)}});return false},error:function(b){var c=b.statusText;a("#bp-replysubmit .waiting").hide();if(b.responseText){c=b.responseText.replace(/<.[^<>]*?>/g,"")}if(c){a("#bp-replysubmit .error").html(c).show()}},show:function(c){var d,e,b;if(typeof(c)=="string"){activityReply.error({responseText:c});return false}b=wpAjax.parseAjaxResponse(c);if(b.errors){activityReply.error({responseText:wpAjax.broken});return false}b=b.responses[0];a("#bp-activities-container").fadeOut("200",function(){a("#bp-activities").val("").blur();a("#bp-replysubmit .error").html("").hide();a("#bp-replysubmit .waiting").hide();a("#bp-activities-container").before(b.data);e=a("#activity-"+b.id);d=e.closest(".widefat").css("backgroundColor");e.animate({backgroundColor:"#CEB"},300).animate({backgroundColor:d},300)})}};a(document).ready(activityReply.init)})(jQuery);