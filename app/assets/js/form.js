new Vue({
    el: '#vue-app-form',
    data: {
        form: {
            name: '',
            icon: '',
            url: '',
            poster: ''
        },
        server: 'http://eplayer.softhem.se',
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

        startTimer: function(command) {
            let arr = command.cmd.split(' ');
            let duration = Number(arr[1]);
            let id = command.id;
            this.timer = {
                id : id,
                duration: duration,
                elapsed: duration * 60
            };
            var app = this;
            clearInterval(app.timer.tHandle);
            this.timer.tHandle = setInterval(function () {
                app.timer.elapsed = app.timer.elapsed - 1;
            }, 1000);
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

                if(command.cmd.includes('SLP')) {
                    let status = app.startTimer(command);
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
        },
        submit: function (e) {
            e.preventDefault();
            let params = $.param(this.form);
            this.$http.get(this.server + '?' + params)
                .then(function (response) {
                    console.log('SET CMD STATUS:', response.data);
                    if(response.data.status === true) {
                        this.form = {
                            name: '',
                            icon: '',
                            url: '',
                            poster: ''
                        }
                    }
                });
        }
    }

});