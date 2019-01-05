new Vue({
    el: '#vue-app',
    data: {
        name: 'Alex',
        users: {},
        channels: [],
        commands: {},
        playingUrlId: 0
    },

    created: function () {
        var api = this.$http;

        this.$http.get('http://localhost:8181/?data=channel')
            .then(function (response) {
                this.channels = response.data;
                console.log('CHANNELS:', this.channels);
                window.channels = this.channels;
            });

        setInterval(function () {
            console.log('I am reading commands from server');
            api.get('http://localhost:8181/?data=command')
                .then(function (response) {
                    this.commands = response.data;
                    console.log('COMMAND:', this.commands);
                    this.executeCommands(this.commands);
                });
        }, 4000);
    },

    methods: {
        getSiteId : function () {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars['site'] ? vars['site'] : false;
        },

        play : function(url) {
            console.log('I am playing a url ' + url);
            let video = document.getElementById('video');
            if(Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource(url);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED,function() {
                    video.play();
                });
            }
            else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = 'https://video-dev.github.io/streams/x36xhzz/x36xhzz.m3u8';
                video.addEventListener('loadedmetadata',function() {
                    video.play();
                });
            }
        },

        startVideo: function (e) {
            console.log('I am starting a video' + $(e.target).data('target'));
            var url = $(e.target).data('target');
            this.playingUrlId = this.channels.findIndex(function(ch) {return ch.url === url});
            window.playingUrlId = this.playingUrlId;
            window.url = url;
            let video = document.getElementById('video');
            if(Hls.isSupported()) {
                var hls = new Hls();
                hls.loadSource(url);
                hls.attachMedia(video);
                hls.on(Hls.Events.MANIFEST_PARSED,function() {
                    video.play();
                });
            }
            else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = 'https://video-dev.github.io/streams/x36xhzz/x36xhzz.m3u8';
                video.addEventListener('loadedmetadata',function() {
                    video.play();
                });
            }
        },
        next : function (command) {
            let arr = command.cmd.split(' ');
            let increment = arr[1];

            let currentId = this.playingUrlId !== undefined ? this.playingUrlId : -1;
            let nextChannelId = Number(currentId) + Number(increment);
            if(nextChannelId >= this.channels.length) {
                nextChannelId = 0;
            }
            let channel = this.channels[nextChannelId];
            this.playingUrlId = nextChannelId;
            this.play(channel.url);
            return true;

        },
        pre: function(increment) {
            console.log('I am doing pre with increment: ' + increment);
        },
        executeCommands: function (commands) {
            var app = this;
            console.log('I am execting commands: ' , commands);
            commands.forEach(function (command) {
                if(command.cmd.includes('NEXT')) {
                    let status = app.next(command);
                    if(status === true) {
                        app.setCommandStatus(command, 0);
                    }
                }

                if(command.cmd.includes('PRE')) {
                    let arr = command.cmd.split(' ');
                    app.pre(arr[1]);
                }
            })
        },
        setCommandStatus: function (command, status) {
            this.$http.get('http://localhost:8181/?command='+command.id+'&status='+status)
                .then(function (response) {
                    console.log('SET CMD STATUS:', response.data);
                });
        }
    }

});