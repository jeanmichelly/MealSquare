/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var cache = {};
        
function addFormField(selector) {
    var collectionHolder = $('#' + selector.attr('data-target'));
    var prototype = collectionHolder.attr('data-prototype');
    var form = prototype.replace(/__name__/g, collectionHolder.children().length);

    collectionHolder.append(form);
}

function removeFormField(selector) {
    var name = selector.attr('data-related');
    $('*[data-content="'+name+'"]').remove();
}


