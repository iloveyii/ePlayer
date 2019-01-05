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

        play : function(url, posterUrl) {
            console.log('I am playing a url ' + url);
            let video = document.getElementById('video');
            video.setAttribute('poster', posterUrl);
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
            var channel = this.channels.find(function(ch) {return ch.url === url});
            if(channel !== undefined) {
                this.playingUrlId = channel.id;
                this.play(channel.url, channel.poster);
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
            this.play(channel.url, channel.poster);
            return true;
        },
        pre: function(command) {
            let arr = command.cmd.split(' ');
            let increment = arr[1];
            console.log('I am doing pre with increment: ' + increment);

            let currentId = this.playingUrlId !== undefined ? this.playingUrlId : -1;
            let nextChannelId = Number(currentId) - Number(increment);
            if(nextChannelId < 0) {
                nextChannelId = this.channels.length - 1;
            }
            let channel = this.channels[nextChannelId];
            this.playingUrlId = nextChannelId;
            this.play(channel.url, channel.poster);
            return true;
        },
        volume: function(command) {
            let arr = command.cmd.split(' ');
            let increment = Number(arr[1]);
            let video = document.getElementById('video');
            let volume = video.volume + (increment * 0.1);
            volume = Number(volume.toFixed(1));
            console.log('I am setting volume to : '+ volume, command);
            if(volume > 1) {
                video.volume = 1;
                return true;
            }
            if(volume < 0) {
                video.volume = 0;
                return true;
            }

            console.log('Final volume is: ' + volume);
            video.volume = volume;
            return true;
        },
        executeCommands: function (commands) {
            var app = this;
            console.log('I am executing commands: ' , commands);
            commands.forEach(function (command) {
                if(command.cmd.includes('NEXT')) {
                    let status = app.next(command);
                    if(status === true) {
                        app.setCommandStatus(command, 0);
                    }
                }

                if(command.cmd.includes('PRE')) {
                    let status = app.pre(command);
                    if(status === true) {
                        app.setCommandStatus(command, 0);
                    }
                }

                if(command.cmd.includes('VOL')) {
                    let status = app.volume(command);
                    if(status === true) {
                        app.setCommandStatus(command, 0);
                    }
                }
            })
        },
        setCommandStatus: function (command, status) {
            this.$http.get('http://localhost:8181/?cmdid='+command.id+'&cmdstatus='+status)
                .then(function (response) {
                    console.log('SET CMD STATUS:', response.data);
                });
        }
    }

});