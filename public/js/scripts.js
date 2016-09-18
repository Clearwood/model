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
    $("#edit").on("click", function (event) {
        elements.getElementsByClassName("descr");
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.display = "none";
        }
        elements.getElementsByClassName("edit");
        for (var i = 0; i < elements.length; i++) {
            elements[i].style.display = "block";
        }
    });
    $("profile").on("click",function(){
       $("#imgload").trigger("click");
    });
    var users= new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace("username"),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: 'https://budde.ws/search.php?query=%QUERY'
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
            /*
            templates: {
                empty: "no models found yet",
                suggestion: _.template("<p id=\"sug\"><b><%- full_name %>,</b>  <a id=\"model_id_type\"> <%- id %></a></p>")
            },
            */
            name: 'users',
            displayKey: 'full_name',
            source: users.ttAdapter()
            /*
            source: function (query, process) {
                console.log(query);
                return $.get('https://budde.ws/search.php', { query: query }, function (data) {
                    return process(data); //if JSON is [ "options" : { ...}
                    console.log("data");
                    console.log(data);
                });
                var parameters = {
                    query: query
                };
                console.log(query);
                $.getJSON("https://budde.ws/search.php", parameters)
                    .done(function (data) {
                        return data;
                        console.log(data);
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {

                        // log error to browser's console
                        console.log(errorThrown.toString());
                    });
                    */
            })
    .on("typeahead:selected", function (eventObject, suggestion, name) {
        detail_id(suggestion.id);
    });
});
function search(query, cb) {
    // get persons matching query (ajax)
    var parameters = {
        query: query
    };
    $.getJSON("search.php", parameters)
        .done(function (data) {

            /*
             console.log(query);
             console.log (cb);
             console.log(data);

             var newData = [];
             $.each(data, function(){

             newData.push(this.full_name);
             newData.push(this.id);
             });
             console.log(newData);
             */
            // call typeahead's callback
            cb(data);
        })
        .fail(function (jqXHR, textStatus, errorThrown) {

            // log error to browser's console
            console.log(errorThrown.toString());
        });
}
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
            $("#age2").attr("value", data[0].age);
            var path = "https://budde.ws/uploads/" + data[0].file;
            $("#profile").attr("src", path);
            window.location = "#model";
            // call typeahead's callback with search results (i.e., places)
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
