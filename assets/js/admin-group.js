//todo@me:複数のjqueryバージョンで正しく動くか確認する
(function($, ajaxurl){
    $('.delete-job').on('click', function(ev){
        var yes = confirm("本当に削除してよろしいですか?");
        if(!yes){
            ev.preventDefault();
        }
    });
    $('.delete-group').on('click', function(ev){
        var name = $("#group-name").html();
        var yes = confirm("[" + name + "]の求人をすべて削除してもよろしいですか?");
        if(!yes){
            ev.preventDefault();
        }
    });
}(jQuery, ajaxurl));