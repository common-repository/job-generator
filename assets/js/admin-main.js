//todo@me:複数のjqueryバージョンで正しく動くか確認する
(function($, ajaxurl){
    var updateJob = function(id){

    };
    $('.update-job').on('click', function(){
        var id = $(this).data('group-id');
        var nonce = $(this).parent('td').find("#_wpnonce").val();
        var ref = $(this).parent('td').find("input[name='_wp_http_referer']").val();
        var data = {
            'action': 'hwjb_update_jobs_by_group',
            'group_id': id,
            '_wpnonce':nonce,
            '_wp_http_referer':ref
        };
        $body = $("body");
        $body.addClass("loading");
        jQuery.post(ajaxurl, data, function(response) {
            $body = $("body");
            $body.removeClass("loading");
            response = JSON.parse(response);
            if(response.success === true){
                $("#successNotice .msg").html("更新が完了しました。");
                $("#successNotice").show();
            }
        });
    });
    $('#hwjt').selectize();
    $('#locations').selectize();
}(jQuery, ajaxurl));