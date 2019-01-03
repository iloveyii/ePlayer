new Vue({
    el: '#vue-app',
    data: {
        name: 'Alex',
        users: {},
        urls: []
    },

    created: function () {
        var api = this.$http;
        console.log(this.getSiteId());

        this.$http.get('http://localhost:8888/index.php?data=urls&site='+this.getSiteId())
            .then(function (response) {
                this.urls = response.data;
                this.urls = this.urls.map(function (url, index) {
                    url.status = 2;
                    api.get('http://localhost:8888/index.php?data=statuses&debug=3&site=' + url.id)
                        .then(function (response) {
                            if (response.data) {
                                var key = Object.keys(response.data);
                                var value = response.data[key];
                                this.urls[index].status = value === true ? 1 : 0;

                                this.urls = this.urls.slice();
                            }
                        });
                    return url;
                });
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