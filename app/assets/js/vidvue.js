new Vue({
    el: '#vue-app',
    data: {
        name: 'Alex',
        users: {},
        channels: []
    },

    created: function () {
        console.log('VIDEO VUE');

        this.$http.get('http://localhost:8181/?data=channel')
            .then(function (response) {
                this.channels = response.data;
                console.log('CHANNELS:', this.channels);
            });
    },

    methods: {
        getSiteId : function () {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars['site'] ? vars['site'] : false;
        }
    }

});