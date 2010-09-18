/* ************************************************************************

vraiLoad - a chainable lazy loader


License:
MIT: http://www.opensource.org/licenses/mit-license.php

Authors:
* Yëco (http://elBleg.com)
Use:
sLoad.load([
'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',
'http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js'
'your-script.js'
]);

************************************************************************ */


var vraiLoad = {
    _loadScript: function(u, c) {
        var h = document.getElementsByTagName('head')[0];
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.src = u;
        if (c) {
            script.onreadystatechange = function() {
                if (this.readyState == 'loaded') c();
            }
            s.onload = c;
        }
        h.appendChild(s);
    },

    load: function(n, i) {
        if (!i) i = 0;
        if (n[i]) {
            vraiLoad._loadScript(
            n[i],
            function() {
                vraiLoad.load(n, i + 1);
            }
            )
        }
    }
};