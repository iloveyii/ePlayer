const { app, BrowserWindow } = require('electron');

const path = require('path');

const url = require('url');

// init
let win;

function createWindow() {
    win = new BrowserWindow({width: 1600, height:1200, icon: __dirname + '/app/assets/img/favicon.png'});
    win.loadURL(url.format({
        pathname: path.join(__dirname, 'home.html'),
        protocol: 'file',
        slashes: true
    }));

    // open dev tools
    // win.webContents.openDevTools();
    win.on('closed', () => {
        win = null;
    });
}

// Create
app.on('ready', createWindow);

// Close
app.on('window-all-closed', ()=> {
    if(process.platform !== 'darwin') {
        app.quit();
    }
});