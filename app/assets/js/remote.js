new Vue({
    el: '#vue-app-remote',
    data: {
    },

    created: function () {

    },

    methods: {
        getId : function () {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars['id'] ? vars['id'] : false;
        },


        next : function () {
            let cmdString = "NEXT 1";
            this.addCommand(cmdString);
            return true;
        },
        pre: function() {
            let cmdString = "PRE 1";
            this.addCommand(cmdString);
            return true;
        },

        mute: function() {
            let cmdString = "MUT 1";
            this.addCommand(cmdString);
            return true;
        },
        unmute: function() {
            let cmdString = "MUT 0";
            this.addCommand(cmdString);
            return true;
        },
        volumeUp: function() {
            let cmdString = "VOL 1";
            console.log('Volume Up: ' + cmdString);
            this.addCommand(cmdString);
            return true;
        },
        volumeDown: function() {
            let cmdString = "VOL -1";
            this.addCommand(cmdString);
            return true;
        },

        addCommand: function (cmdString) {
            this.$http.get('http://192.168.1.30/?cmdadd='+cmdString)
                .then(function (response) {
                    console.log('SET CMD STATUS:', response.data);
                });
        }
    }

});