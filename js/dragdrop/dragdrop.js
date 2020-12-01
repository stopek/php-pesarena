var $j = jQuery.noConflict();

$j(document).ready(function () {
    $j(function () {
        $j("#prawa ul.one").sortable({
            opacity: 0.6, cursor: 'move', update: function () {
                var order = $j(this).sortable("serialize") + '&action=updateRecordsListings';
                $j.post("updateDB.php", order, function (theResponse) {
                    $j("#contentRight").html(theResponse);
                });
            }
        });
    });
});	