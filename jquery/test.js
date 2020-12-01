var box =
    {
        addbox:
            {
                msg: "dodaj wpis",
                msg_success: "dane zostaly dodane. dziekujemy",
                msg_error: "blad podczas wykonywania operacji",
                onsubmit: function () {
                    var addbox = $("#addbox");
                    var text = addbox.val();

                    var data = {
                        'tresc': '',
                        'block': true
                    };

                    data.tresc = text;


                    $.ajax({
                        type: "POST",
                        url: 'api.php?opt=add',
                        data: data,
                        dataType: "xml",
                        success: function (xml) {
                            //alert(box.addbox.msg_success);
                            addbox.val(box.addbox.msg);

                            addbox.blur();
                            addbox.focus();

                            document.title = "shoutbox - dodano wpis";
                            arena.query();
                        },
                        error: function () {
                            arena.ajax_error();
                        }
                    });
                    return false;
                },
                blur: function () {
                    if ($(this).val() == '') $(this).val(box.addbox.msg);
                },
                focus: function () {
                    if ($(this).val() == box.addbox.msg) $(this).val('');
                },
                onkeypress: function (e) {
                    var length = $("#addbox").val().length;

                    if (e.which == 13) {
                        $("#addform").submit();
                        return false;
                    }
                }
            }
    }


var arena =
    {
        ajax_error: function () {
            var info = $("#info");
            info.text(box.addbox.msg_error);
            info.show();
        },
        query: function () {
            $.ajax({
                type: "POST",
                url: 'api.php?opt=get',
                dataType: "xml",
                data: {'block': true},
                success: function (document) {
                    arena.parse(document);
                },
                error: function () {
                    arena.ajax_error();
                }
            });
        },
        parse: function (document) {
            var tresc = '';
            var lista = $("#lista");

            var show = "<ul>";
            $(document).find("one").each(function () {
                tresc = $(this).find("tresc").text();

                show += "<li>" + tresc + "</li>";
            });
            show += "</ul>";


            lista.html(show);
            return false;

        },
        setup: function () {

            var content = $("#content");
            content.append('<form id="addform">' +
                '<textarea id="addbox"></textarea>' +
                '<input type="submit" value="wyslij" />' +
                '</form>');

            content.append('<div id="lista"></div>');
            content.append('<div id="info"></div>');

            var addform = $("#addform", content);
            addform.submit(box.addbox.onsubmit);


            var addbox = $("#addbox", content);
            addbox.blur(box.addbox.blur);
            addbox.focus(box.addbox.focus);
            addbox.val(box.addbox.msg);
            addform.keypress(box.addbox.onkeypress);


            window.setTimeout(function () {
                addbox.blur();
                arena.query();
            }, 500);
            setInterval(function () {
                arena.query();
            }, 500);


        }
    }


$(document).ready(arena.setup);
