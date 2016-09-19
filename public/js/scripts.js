var file_show=0;
$(document).ready(function () {
    $("a.modelid").on("click", function () {
        console.log("click");
        var html1 = $(this).html();
        detail_id(html1);
    });
    $("span.del").on("click", function (event) {
        var parent = event.currentTarget.parentNode;
        var id2 = parent.parentNode.firstChild.firstChild.innerHTML;
        console.log(id2);
        if (confirm("Do you really want to delete this entry?")) {
            post("https://budde.ws/delete.php", {id: id2});
        }
    });
    $(".edit").hide();
    $("#edit").on("click", function () {
        $(".descr").hide();
        $(".edit").show();
        file_show=1;
    });
    $("#profile").on("click",function(){
        if(file_show==1) {
            $("#imgload").trigger("click");
        }
    });
    var users= new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: 'https://budde.ws/search.php?q=%QUERY',
            wildcard: '%QUERY'
        }
        });
    users.initialize();
    $('#typeahead').typeahead(
        {
            hint: true,
            autoselect: true,
            highlight: true,
            minLength: 1
        },
        {
            displayKey: "full_name",
            source: users.ttAdapter(),
            templates: {
                empty: "no models found yet",
                suggestion: _.template("<p id=\"sug\"><b><%- full_name %>,</b>  <a id=\"model_id_type\"> <%- id %></a></p>")
            }
            })
    .on("typeahead:selected", function (eventObject, suggestion, name) {
        detail_id(suggestion.id);
    });
    $("#model").hide();
});
function detail_id(id_q) {
    // get places matching query (asynchronously)
    var parameters = {
        id: id_q
    };
    //noinspection JSUnresolvedFunction
    $.getJSON("https://budde.ws/model.php", parameters)
        .done(function (data) {
            $("#name").html(data[0].full_name);
            console.log(data[0].full_name);
            var age=data[0].age;
            $("#age3").html("age: "+age);
            $("#age2").attr("value", age);
            $("#id_form").attr("value", data[0].id);
            var path = "https://budde.ws/uploads/" + data[0].file;
            $("#profile").attr("src", path);
            $("#model").show();
            window.location = "#model";

        })
        .fail(function (jqXHR, textStatus, errorThrown) {

            // log error to browser's console
            console.log(errorThrown.toString());
        });
}
function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
