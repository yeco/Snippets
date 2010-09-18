var cookieHandler = {
       set: function (name, value, hours, path) {
               var expires = this.getHoursFromNow(24);
               if (hours) expires = this.getHoursFromNow(hours);
               path = path || '/';
               document.cookie = name +'='+ escape(value) +'; expires='+ expires+'; path='+ path;
       },
       getHoursFromNow: function (hours) {
               return new Date(new Date().getTime() + hours * 3600000);
       },
       get: function (name) {
               name += '=';
               var c, cs = document.cookie.split(';');
               for (var i = 0, len = cs.length; i < len; i++) {
                       c = cs[i];
                       while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                       if (c.indexOf(name) == 0) {
                               return unescape(c.substring(name.length, c.length));
                       }
               }
       },
       delete: function( name, path, domain ) {
        if ( Get_Cookie( name ) ) document.cookie = name + "=" +
        ( ( path ) ? ";path=" + path : "") +
        ( ( domain ) ? ";domain=" + domain : "" ) +
        ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
       }
};