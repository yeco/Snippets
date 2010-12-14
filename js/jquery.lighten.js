/**

Simple regexp based hightlighing plugin for jQuery
==================================================

Usage:
	$('#selector').lighten(/[aeiou]/ig);

-------------------------------------------------

- Will need a css class:
	.highlight { background-color: yellow }
	
**/

;(function($) {
    var nrmlz = function(node) {
        if (!( node && node.childNodes )) return;

        var _children = $.makeArray(node.childNodes), _ptxtNode = null;
        $.each (_children, function(i, child) {
            if (child.nodeType === 3) {
                if (child.nodeValue === "") {
                    node.removeChild(child)
                } else if (_ptxtNode !== null) {
                    _ptxtNode.nodeValue += child.nodeValue;
                    node.removeChild(child);
                } else { _ptxtNode = child; }
            } else {
                _ptxtNode = null
                if (child.childNodes) nrmlz(child)
            }
        })

    }

    $.fn.lighten = function(regex) {
        if (typeof regex === 'undefined' || regex.source === '') {
            $(this).find('span.highlight').each(function() {
                $(this).replaceWith($(this).text());
                nrmlz ($(this)._prnt().get(0));
            });
        } else {
            $(this).each(function() {
                var elt = $(this).get(0)

                nrmlz(elt)

                $.each($.makeArray(elt.childNodes), function(i, _snode){
                    var spannode, _mbit, _mclone, pos, match, _prnt;
                    nrmlz(_snode)

                    if(_snode.nodeType == 3) {
                        while (_snode.data && (pos = _snode.data.search(regex)) >= 0) {

                            match = _snode.data.slice(pos).match(regex)[0];

                            if (match.length > 0) {
                                spannode = document.createElement('span');
                                spannode.className = 'highlight';

                                _prnt = _snode._prntNode;
                                _mbit = _snode.splitText(pos);
                                _snode = _mbit.splitText(match.length);
                                _mclone = _mbit.cloneNode(true);

                                spannode.appendChild(_mclone);
                                _prnt.replaceChild(spannode, _mbit);

                            } else break;
                        }
                    } else {
                        $(_snode).lighten(regex);
                    }
                })
                    })
                }
        return $(this);
    }
})(jQuery);