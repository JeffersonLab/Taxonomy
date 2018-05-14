/**
 * Created by theo on 6/1/17.
 */
// Is there some other way to get our taxonomy namespace
// exposed after this script has been processed
// by webpack?
window.taxonomy = taxonomy = {};

/**
 * Posts an ajax update to set and order the
 * terms beneath the specified parent.
 */
taxonomy.reorderTerms = function (parent, terms) {
    var sortedIds = _.map(terms, function (term) {
        return _.replace(term, 'term-', '');
    });
    var parentId = _.replace(parent, 'term-', '');

    $.post(laroute.route('api.terms.sort'),
        $.param({
            "parent_id": parentId,
            "term_ids[]": sortedIds
        }))
        .fail(function (jqxhr) {
            bootbox.alert(jQuery.parseJSON(jqxhr.responseText));
        });

},


    /**
     * Extract every data-* key and value from the supplied
     * DOM element and return it as a query string.
     */
    taxonomy.dataToQueryString = function (domElem) {
        var count = 0;
        var qStr = '';
        $.each(domElem.data(), function (n, v) {
            if (count === 0) {
                qStr += '?'
            } else {
                qStr += '&'
            }
            qStr += n + '=' + v;
            count++;
        });
        //console.log(qStr);
        return qStr;

    };

taxonomy.deleteItemDialog = function () {
    var wrapperElem = $(this).parent('.actions-wrapper');
    //var deleteQuery = taxonomy.dataToQueryString(wrapperElem);
    var deleteUrl = laroute.route('api.terms.delete',{'id':wrapperElem.data('id')});
    var confirm = $(this).data('confirm');
    bootbox.confirm("Are you sure you want to delete <b>" + confirm + "</b>?<br /><br />"
        + "Press OK if you want to proceed with removal.",
        function (result) {
            console.log(result);
            //return false;
            if (result == false) {
                return true;
            } else {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        'method': 'delete',
                    },
                    dataType: "json",
                    success: function (result) {
                        window.location.reload();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        bootbox.alert("Failed to delete.  Server response was: <br />\n " + jqXHR.responseText, function () {
                        });
                    }
                });
            }
        });
};

/**
 * Initialize and then display a modal dialog containing
 * a form body loaded via ajax.
 */
taxonomy.editItemDialog = function () {
    var dialogDiv = $('#editItem');
    var dialogBodyDiv = $('#editItem div.modal-body');
    var dialogNoticeDiv = $('#editItem div.modal-notice');
    dialogBodyDiv.html('');
    dialogNoticeDiv.html('');

    var wrapperElem = $(this).parent('.actions-wrapper');
    var formQuery = taxonomy.dataToQueryString(wrapperElem);
    var formUrl = laroute.route('api.forms') + formQuery;
    console.log(formUrl);

    dialogBodyDiv.load(formUrl, taxonomy.dialogLoaded);

    dialogDiv.modal({keyboard: true});
    dialogDiv.modal({show: true});

    //Assign a submit handler
    //@todo add validation client-side
    $("button#submit").click(function () {
        var form = dialogBodyDiv.children('form').first();
        var postData = form.serialize();
        console.log(postData);
        taxonomy.submitItemDialog(form);
    });
};

//
// /**
//  * Initialize and then display a modal dialog containing
//  * a form body loaded via ajax.
//  */
// taxonomy.editUsersDialog = function () {
//
//     var wrapperElem = $(this).parent('.actions-wrapper');
//     var formQuery = taxonomy.dataToQueryString(wrapperElem) + '&form=users';
//     var formUrl = laroute.route('api.forms') + formQuery;
//     taxonomy.dialog(formUrl);
// };

/**
 * @param url where the form content for the dialog can be fetched
 */
taxonomy.dialog = function(url){
    console.log('Fetch dialog from '+url);
    var dialogDiv = $('#editItem');
    var dialogBodyDiv = $('#editItem div.modal-body');
    var dialogNoticeDiv = $('#editItem div.modal-notice');
    dialogBodyDiv.html('');
    dialogNoticeDiv.html('');

    dialogBodyDiv.load(url, taxonomy.dialogLoaded);

    dialogDiv.modal({keyboard: true});
    dialogDiv.modal({show: true});

    //Assign a submit handler
    //@todo add validation client-side
    $("button#submit").click(function () {
        var form = dialogBodyDiv.children('form').first();
        var postData = form.serialize();
        console.log(postData);
        taxonomy.submitItemDialog(form);
    });

};


taxonomy.submitItemDialog = function (form) {
    console.log(form.attr('action'));
    $.post(form.attr('action'),
        form.serialize(),
        function (data) {
            console.log("saved!");
            taxonomy.setSuccessNotice('Saved');
        })
        .done(function () {
            window.location.reload();
        })
        .fail(function (jqxhr) {
            var data = jQuery.parseJSON(jqxhr.responseText);
            var msg = "Submission failure:";
            msg += '<ul>'
            $.each(data, function (n, v) {
                msg += '<li>' + n + ': ' + v + '</li>';
                console.log('failure ' + v);
            });
            msg += '</ul>'
            taxonomy.setErrorNotice(msg);
        });

    return false;
};


taxonomy.setSuccessNotice = function (msg, status) {
    $('#editItem div.modal-notice').removeClass('bg-danger');
    $('#editItem div.modal-notice').addClass('bg-success');
    $('#editItem div.modal-notice').html(msg);
};

taxonomy.setErrorNotice = function (msg, status) {
    $('#editItem div.modal-notice').removeClass('bg-success');
    $('#editItem div.modal-notice').addClass('bg-danger');
    $('#editItem div.modal-notice').html(msg);
};

/**
 * Enhance the new content loaded into the modal
 * dialogs DOM.
 */
taxonomy.dialogLoaded = function () {
    //$('.select2').select2({width:'20em'});
};