new Vue({
    el: '#vue-app',
    data: {
        channels: [],
        commands: {},
        playingUrlId: 0,
        server: 'http://192.168.1.30'
    },

    created: function () {
        var api = this.$http;
        var app = this;

        this.$http.get(this.server + '/?data=channel')
            .then(function (response) {
                this.channels = response.data;
                let id = this.getId();
                if( ! isNaN(id)) {
                    let currentChannel = this.channels[id - 1];
                    this.play(currentChannel.url, currentChannel.poster);
                }
            });

        setInterval(function () {
            console.log('I am reading commands from server');
            api.get(app.server + '?data=command')
                .then(function (response) {
                    this.commands = response.data;
                    console.log('COMMAND:', this.commands);
                    this.executeCommands(this.commands);
                });
        }, 4000);
    },

    methods: {
        getId : function () {
                var vars = {};
                var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
                    vars[key] = value;
                });
                return vars['id'] ? vars['id'] : false;
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
        mute: function(command) {
            let arr = command.cmd.split(' ');
            let increment = Number(arr[1]);
            let video = document.getElementById('video');
            video.muted = increment;
            video.play();
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

                if(command.cmd.includes('MUT')) {
                    let status = app.mute(command);
                    if(status === true) {
                        app.setCommandStatus(command, 0);
                    }
                }
            })
        },
        setCommandStatus: function (command, status) {
            this.$http.get(this.server + '/?cmdid='+command.id+'&cmdstatus='+status)
                .then(function (response) {
                    console.log('SET CMD STATUS:', response.data);
                });
        }
    }

});